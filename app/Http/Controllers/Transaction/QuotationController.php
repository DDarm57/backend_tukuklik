<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\Constant;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Marketing\CouponController;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CustomerAddresses;
use App\Models\Quotation;
use App\Models\QuotationChannel;
use App\Models\QuotationNegotiation;
use App\Models\ShippingMethod;
use App\Models\Status;
use App\Models\TransactionActivity;
use App\Models\TransactionChannel;
use App\Repositories\QuotationRepository;
use App\Repositories\UserRepository;
use App\Services\NotificationService;
use App\Services\ProductService;
use App\Services\QuotationService;
use App\Traits\GenerateNumber;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class QuotationController extends Controller
{
    use GenerateNumber;

    public function requestForQuotationIndex(Request $request)
    {
        if($request->ajax()){
            $userId = null;
            if(Auth::user()->hasRole('Customer')) {
                $userId = Auth::user()->id;
            }
            $filter = $request->input('filter');
            $filter['type'] = 'RFQ';
            $rfqQuery = QuotationRepository::quotation($filter, $userId);
            return DataTables::of($rfqQuery)
            ->addIndexColumn()
            ->addColumn('grand_total', function($rfqQuery) {
                return Helpers::formatRupiah($rfqQuery->grand_total);
            })
            ->make(true);
        }
        $data['status'] = Status::all();
        return view('dashboard.transaction.rfq', $data);
    }

    public function showRfqForm()
    {
        $carts = Cart::where('user_id', Auth::user()->id);
        $merchant = $carts->get()[0]->productSku->product->merchant ?? abort(404);
        $customerAddress = UserRepository::customerAddress(Auth::user()->id, 1);
        $merchantAddress = UserRepository::merchantAddress($merchant->id);

        $formatCustomerAddress = Helpers::formatAddress($customerAddress->first());
        $formatMerchantAddress = Helpers::formatAddress($merchantAddress->first());

        $data['produk'] = $carts->get();  
        $data['user']  = Auth::user();
        $data['date']  = Carbon::now()->format('d F Y H:i:s');
        $data['merchant'] = $merchant;
        $data['customerAddress'] = $formatCustomerAddress;
        $data['merchantAddress'] = $formatMerchantAddress;
        $data['customerAddressId'] = $customerAddress->first()->id ?? '';
        $data['merchantAddressId'] = $merchantAddress->first()->id ?? '';
        $data['subTotal'] = $carts->sum('subtotal');
        $data['grandTotal'] = $carts->sum('total_price');
        $data['taxTotal']   = $carts->sum('tax_amount');
        $data['paymentType'] = Helpers::paymentType();
        $data['coupons'] = Coupon::latest()->get();

        return view('dashboard.transaction.form.rfq-form', $data);
    } 

    public function submitRFQ(Request $request)
    {
        $this->validate($request, [
            'purpose_of'        => 'required|max:50',
            'shipping_address'  => 'required',
            'billing_address'   => 'required',
            'user_pic'          => 'required|max:255',
            'user_phone'        => 'required|max:20',
            'notes_for_merchant'=> 'nullable|max:255',
            'payment_type'      => 'required',
            'coupon_code' => [
                'nullable',
                Rule::exists('coupons', 'coupon_code')->where(function($q) {
                    $q->where('start_date', '<=', Carbon::today()->toDateString());
                    $q->where('end_date', '>=', Carbon::today()->toDateString());
                }),
            ]
        ]);

        try {

            DB::beginTransaction();

            $data = Helpers::requestExcept($request);
            $number = $this->generateRFQNumber();

            $shippingAddress = CustomerAddresses::where('id', $request->customer_address_id)->first();
            $billingAddress = CustomerAddresses::where('id', $request->billing_address_id)->first();

            $data['discount'] = 0;

            if($request->coupon_code != '') {
                try { 
                    $coupon = App::make(CouponController::class);
                    $request->merge(['rfq_number' => $number]);
                    $disc = $coupon->applyCoupon($request);
                    $data['discount'] = $disc;
                } catch(Exception $e) {
                    throw $e;
                }
            }
            
            $data['shipping_address'] = $shippingAddress;
            $data['billing_address'] =  $billingAddress;
            $data['number'] = $number;
            $data['user_id'] = Auth::user()->id;
            QuotationService::saveAddress($data);

            $carts = Cart::where('user_id', Auth::user()->id);
            $data['carts'] = $carts->get();
            QuotationService::saveProductRFQ($data);

            $merchant = $carts->first()->productSku->product->merchant;
            $data['merchant_id'] = $merchant->id;
            $data['is_merchant_pkp'] = $merchant->is_pkp;
            $data['date'] = Carbon::now();
            $data['deadline_date'] = Carbon::now()->addDay(Helpers::generalSetting()->expired_rfq ?? 1);
            $data['termin'] = $request->termin ?? 0;
            $data['status'] = 1; // Menunggu Persetujuan Penjual
            QuotationService::saveRFQ($data);

            $quotation = Quotation::query()->where('number', $number)->first();
            NotificationService::quotation($number, 'created');

            DB::commit();
            Session::flash('success', 'Permintaan Penawaran Berhasil Diajukan!');
            return response()->json([
                'status'    => 'success', 
                'message'   => 'Produk berhasil dibuat'
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateRFQ(Request $request, $number)
    {
        
    }

    public function deleteRFQ($id) 
    {
        $quote = Quotation::where('id', $id);
        $channel = TransactionChannel::where('rfq_number', $quote->first()->number);
        DB::beginTransaction();
        try {
            if($channel->count() == 0) {
                $quote->delete();
                DB::commit();
                return response()->json(['message' => 'RFQ berhasil dihapus']);
            }   
            return response()->json(['message' => 'RFQ gagal dihapus, quote telah terbentuk']);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function editRFQ($number)
    {
        $data = $this->detailQuote($number);
        return view('dashboard.transaction.update.rfq', $data);
    }

    private function detailQuote($number)
    {
        $quotation = Quotation::query()->where('number', $number);
        if($quotation->count() == 0){
            return redirect()->back();
        }
        $quote = $quotation->first();
        $merchant = $quote->merchant;
        $merchantAddress = UserRepository::merchantAddress($merchant->id);
        // dd($merchantAddress->first());
        
        $activities =   $quote->activities()
                        ->orderByDesc('id')
                        ->where(function($q) use($number) {
                            $query = $q->where('number', 'LIKE', '%RFQ%');
                            if(substr($number,0,2) == "QN"){
                                $query = $query->orWhere('number', 'LIKE', '%QN%');
                            }
                            return $query;
                        })
                        ->get();
                        
        if(substr($number,0,2) == "QN") {
            $number = $quote->channel->rfq_number;
        }

        $shippingAddress = QuotationRepository::shippingAddress($number);
        $billingAddress = QuotationRepository::billingAddress($number);

        $data['produk'] = $quote->productRequests()->get();  
        $data['user']  = $quote->user;
        $data['date']  = Carbon::parse($quote->date)->translatedFormat('d F Y H:i:s');
        $data['merchant'] = $merchant;
        $data['shippingAddress'] = Helpers::formatAddress($shippingAddress->first());
        $data['billingAddress'] = Helpers::formatAddress($billingAddress->first());
        $data['merchantAddress'] = Helpers::formatAddress($merchantAddress->first());
        $data['quote'] = $quote;
        $data['paymentType'] = $quote->payment_type;
        $data['choosePaymentType'] = Helpers::paymentType();
        $data['shippingMethod'] = ShippingMethod::all();
        $data['grandTotal'] = $quote->grand_total;
        $data['activities'] = $activities;
        $data['transType'] = substr($quote->number,0,2) == "QN" ? "Penawaran" : "Permintaan Penawaran";
        $data['allowEdited'] = $this->allowEdited($quote->status, $quote->number);
        return $data;
    }

    private function allowEdited($status, $number)
    {
        if(substr($number, 0,2) == "QN"){
            if(Auth::user()->getRoleNames()[0] == "Customer") {
                return $status == 2 ? true : false;
            } else {
                return $status == 3 ? true : false;
            }
        }else {
            return $status == 1 ? true : false;
        }
    }

    public function showQuotation($number)
    {
        $data = $this->detailQuote($number);
        if(substr($number,0,2) == "QN"){
            return view('dashboard.transaction.show.quotation', $data);            
        }
        return view('dashboard.transaction.show.rfq', $data);
    }

    public function quotationIndex(Request $request)
    {
        if($request->ajax()){
            $userId = null;
            if(Auth::user()->hasRole('Customer')) {
                $userId = Auth::user()->id;
            }
            $filter = $request->input('filter');
            $filter['type'] = 'QN';
            $qnQuery = QuotationRepository::quotation($filter, $userId);
            return DataTables::of($qnQuery)
            ->addIndexColumn()
            ->addColumn('grand_total', function($qnQuery) {
                return Helpers::formatRupiah($qnQuery->grand_total);
            })
            ->make(true);
        }
        $data['status'] = Status::all();
        return view('dashboard.transaction.quotation', $data);
    }

    public function submitQuotation(Request $request, $number)
    {
        $this->validate($request, [
            'payment_type'      => 'required',
            'termin'            => 'required_if:payment_type,==,Term Of Payment',
            'shipping_method'   => 'required',
            'shipping_fee'      => 'required|numeric',
            'date_estimation'   => 'required|numeric|max:30',
            'base_price.*'      => 'required',
            'ppn.*'             => 'required',
            'pph.*'             => 'required'
        ]);

        DB::beginTransaction();

        try {
        
            $data = Helpers::requestExcept($request);
            $data['quotationNumber'] = $this->generateQuotationNumber();
            $data['rfqNumber'] = $number;
            $data['date'] = Carbon::now();
            $data['deadline_date'] = Carbon::now()->addDay(Helpers::generalSetting()->expired_quotation ?? 1);
            
            QuotationService::saveQuote($data);

            $quotation = Quotation::query()->where('number', $data['quotationNumber'])->first();
            NotificationService::quotation($data['quotationNumber'], 'created');

            DB::commit();
            Session::flash('success', 'Penawaran Berhasil Dibuat!');
            
            return response()->json([
                'status'    => 'success', 
                'message'   => 'Produk berhasil dibuat'
            ]);

        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function rejectRFQ(Request $request, $number)
    {
        $this->validate($request, [
            'rejected_reason' => 'required'
        ]);

        $quotation = Quotation::where('number', $number)->first();
        $quotation->status = 14;
        $quotation->save();

        $channel = TransactionChannel::where('rfq_number', $number)->first();

        $quotation->activities()->create([
            'channel_id'    => $channel->id,
            'number'        => $number,
            'description'   => sprintf(Constant::RFQ_REJECTED,$request->rejected_reason)
        ]);

        NotificationService::quotation($number, 'rejected', [
            'rejected_reason'   => $request->rejected_reason
        ]);

        Session::flash('success', 'Permintaan berhasil ditolak');
        return response()->json(['status' => 'success']);
    }

    public function rejectQuotation(Request $request, $number)
    {
        $this->validate($request, [
            'rejected_reason' => 'required'
        ]);

        $quotation = Quotation::where('number', $number)->first();
        $quotation->status = 13;
        $quotation->save();

        $channel = TransactionChannel::where('quotation_number', $number)->first();

        ProductService::removeStockHold($quotation);

        $quotation->activities()->create([
            'channel_id'    => $channel->id,
            'number'        => $number,
            'description'   => sprintf(Constant::QUOTATION_REJECTED, $request->rejected_reason)
        ]);

        Session::flash('success', 'Penawaran Berhasil Ditolak');

        return response()->json(['status' => 'success']);
    }

    public function sendNegotiateMessage(Request $request, $number)
    {
        $this->validate($request, [
            'description'   => 'required'
        ]);

        DB::beginTransaction();

        try {
            $quotation = Quotation::where('number', $number)->first();
            $quotation->status = 3;
            $quotation->save();
    
            $quotation->negotiations()->create([
                'user_id'       => Auth::user()->id,
                'number'        => $number,
                'description'   => $request->description
            ]);

            $additionalNotif = [
                'user'          => Auth::user(),
                'description'   => $request->description
            ];

            NotificationService::negotiation($number, 'updated', $additionalNotif);
    
            DB::commit();
            Session::flash('success', 'Pesan Negosiasi Berhasil Dikirim');
            return response()->json(['status' => 'success']);

        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function submitNegotiation(Request $request, $number)
    {
        $this->validate($request, [
            'description'   => 'required'
        ]);

        DB::beginTransaction();

        try {
            $quotation = Quotation::where('number', $number)->first();
            $quotation->status = 3;
            $quotation->save();
    
            $quotation->negotiations()->create([
                'user_id'       => Auth::user()->id,
                'number'        => $number,
                'description'   => $request->description
            ]);
    
            $quotation->activities()->create([
                'channel_id'    => $quotation->channel_id,
                'number'        => $number,
                'description'   => Constant::NEGOTIATION_CREATED
            ]);

            NotificationService::negotiation($number, 'created');

            DB::commit();
            Session::flash('success', 'Negosiasi Berhasil Diajukan Ke Penjual');
            return response()->json(['status' => 'success']);

        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function rejectNegotiation(Request $request, $number)
    {
        $this->validate($request, [
            'description'   => 'required'
        ]);

        $quotation = Quotation::where('number', $number)->first();
        $quotation->status = 2;
        $quotation->save();

        $quotation->negotiations()->create([
            'user_id'       => Auth::user()->id,
            'number'        => $number,
            'description'   => $request->description
        ]);

        $quotation->activities()->create([
            'channel_id'    => $quotation->channel_id,
            'number'        => $number,
            'description'   => sprintf(Constant::NEGOTIATION_REJECTED,$request->description)
        ]);

        NotificationService::negotiation($number, 'rejected');

        Session::flash('success', 'Negosiasi Berhasil Ditolak');
        return response()->json(['status' => 'success']);
    }

    public function editQuotation($number)
    {
        $data = $this->detailQuote($number);
        if(Auth::user()->getRoleNames()[0] == "Customer") {
            return view('dashboard.transaction.show.quotation', $data);
        } else {
            return view('dashboard.transaction.update.quotation', $data);
        }
    }

    public function reportQuotation($number)
    {
        $data = $this->detailQuote($number);
        return view('dashboard.print.quotation', $data); 
    }
}
