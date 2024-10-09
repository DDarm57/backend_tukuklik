<?php

namespace App\Http\Controllers\API\Transaction;

use App\Helpers\Constant;
use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Resources\Transaction\RFQResource;
use App\Models\Cart;
use App\Models\CustomerAddresses;
use App\Models\Quotation;
use App\Models\ShippingMethod;
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
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class QuotationController extends Controller
{
    use GenerateNumber;

    public function rfqIndex(Request $request)
    {
        try {
            $userId = null;
            if(Auth::user()->hasRole('Customer')) {
                $userId = Auth::user()->id;
            }
            $filter = $request->input('filter');
            $filter['type'] = 'RFQ';
            $rfqQuery = QuotationRepository::quotation($filter, $userId);
            return response()->json([
                'success'   => true,
                'data'      => $rfqQuery,
            ]);
        } catch(Exception $e) {
            return response()->json([
                'success'    => false, 
                'message'   => $e->getMessage()
            ], 400);
        }
    }

    public function quotationIndex(Request $request)
    {
        $userId = null;
        if(Auth::user()->hasRole('Customer')) {
            $userId = Auth::user()->id;
        }
        $filter = $request->input('filter');
        $filter['type'] = 'QN';
        $qnQuery = QuotationRepository::quotation($filter, $userId);
        return response()->json([
            'success'   => true,
            'data'      => $qnQuery,
        ]);
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
            ],
            "pph.*"             => 'required|numeric',
            "ppn.*"             => 'required|numeric'
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
            if($carts->count() == 0){
                throw new Exception("RFQ Gagal Disubmit, Keranjang Belanja Kosong");
            }
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
            return response()->json([
                'success'    => true, 
                'message'   => 'Permintaan Penawaran Berhasil Diajukan!'
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'success'    => false, 
                'message'   => $e->getMessage()
            ], 400);
        }
    }

    public function deleteRFQ($number)
    {
        $quote = Quotation::where('number', $number)->firstOrFail();
        $channel = TransactionChannel::where('rfq_number', $quote->first()->number)->first();
        DB::beginTransaction();
        try {
            if($channel->quotation_number == "") {
                $quote->delete();
                DB::commit();
                return response()->json([
                    'success'   => true,
                    'message'   => 'RFQ berhasil dihapus'
                ]);
            }   
            return response()->json([
                'success'   => false,
                'message'   => 'RFQ gagal dihapus, quote telah terbentuk'
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'success'    => false, 
                'message'   => $e->getMessage()
            ], 400);
        }
    }

    public function detailQuote($number)
    {
        try {
            $quotation = Quotation::query()->where('number', $number);
            $quote = $quotation->firstOrFail();
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
            $data['date']  = Carbon::parse($quote->date)->translatedFormat('d F Y');
            $data['merchant'] = $merchant;
            $data['shippingAddress'] = Helpers::formatAddress($shippingAddress->first());
            $data['billingAddress'] = Helpers::formatAddress($billingAddress->first());
            $data['merchantAddress'] =Helpers::formatAddress($merchantAddress->first());
            $data['quote'] = $quote;
            $data['paymentType'] = $quote->payment_type;
            $data['choosePaymentType'] = Helpers::paymentType();
            $data['shippingMethod'] = ShippingMethod::all();
            $data['grandTotal'] = $quote->grand_total;
            $data['activities'] = $activities;
            $data['allowEdited'] = $this->allowEdited($quote->status, $quote->number);
            return response()->json($data);
        } catch(Exception $e) {
            return response()->json([
                'success'    => false, 
                'message'   => $e->getMessage()
            ], 400);
        }
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

    public function rejectRFQ(Request $request, $number)
    {
       
        $this->validate($request, [
            'rejected_reason' => 'required'
        ]);

        try {
    
            $quotation = Quotation::where('number', $number)->firstOrFail();
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
    
            return response()->json([
                'success'   => true,
                'message'   => 'Permintaan berhasil ditolak'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success'    => false, 
                'message'   => $e->getMessage()
            ], 400);
        }
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
            
            return response()->json([
                'success'       => true, 
                'message'       => 'Penawaran berhasil dibuat'
            ]);

        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'success'    => false, 
                'message'   => $e->getMessage()
            ], 400);
        }
    }

    public function rejectQuotation(Request $request, $number)
    {
        $this->validate($request, [
            'rejected_reason' => 'required'
        ]);

        try {
            $quotation = Quotation::where('number', $number)->firstOrFail();
            $quotation->status = 13;
            $quotation->save();
    
            $channel = TransactionChannel::where('quotation_number', $number)->first();
    
            ProductService::removeStockHold($quotation);
    
            $quotation->activities()->create([
                'channel_id'    => $channel->id,
                'number'        => $number,
                'description'   => sprintf(Constant::QUOTATION_REJECTED, $request->rejected_reason)
            ]);
    
            return response()->json([
                'success'   => true,
                'message'   => 'Penawaran Berhasil Ditolak'
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'success'    => false, 
                'message'   => $e->getMessage()
            ], 400);
        }
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
            return response()->json([
                'success'   => true,
                'message'   => 'Pesan Negosiasi Berhasil Dikirim'
            ]);

        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'success'    => false, 
                'message'   => $e->getMessage()
            ], 400);
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
            return response()->json([
                'success'   => true,
                'message'   => 'Negosiasi Berhasil Diajukan Ke Penjual'
            ]);

        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'success'    => false, 
                'message'   => $e->getMessage()
            ], 400);
        }
    }

    public function rejectNegotiation(Request $request, $number)
    {
        $this->validate($request, [
            'description'   => 'required'
        ]);

        try {

            DB::beginTransaction();

            $quotation = Quotation::where('number', $number)->firstOrFail();
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
            
            DB::commit();

            return response()->json([
                'success'   => true,
                'message'   => 'Negosiasi Berhasil Ditolak'
            ]);   

        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'success'    => false, 
                'message'   => $e->getMessage()
            ], 400);
        }
    }
}
