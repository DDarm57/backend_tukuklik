<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\Constant;
use App\Helpers\Helpers;
use App\Http\Controllers\API\TokoDaringController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Payment\MidtransController;
use App\Models\Invoice;
use App\Models\PaymentMethod;
use App\Repositories\QuotationRepository;
use App\Services\NotificationService;
use App\Services\TokoDaringService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){

            $query = Invoice::query()
                    ->with(['order', 'order.quotation'])
                    ->when(Auth::user()->hasRole('Customer'), function($q) {
                        $q->whereHas('order', function($qq) {
                            $qq->where('user_id', Auth::user()->id);
                        });
                    })
                    ->latest()
                    ->get();

            return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('invoice_amount', function($query) {
                        return "Rp. ". number_format($query->invoice_amount,2,',','.');
                    })
                    ->make(true);
        }

        return view('dashboard.invoice');
    }

    private function detailInvoice($invoiceNumber)
    {
        $invoice = Invoice::query()->where('invoice_number', $invoiceNumber)->firstOrFail();
        $billingAddress = QuotationRepository::billingAddress($invoice->order->channel->rfq_number);

        $data['invoice']    = $invoice;
        $data['produk']     = $invoice->order->quotation->productRequests()->get();
        $data['billingAddress'] = Helpers::formatAddress($billingAddress->first());
        $data['date']   = Carbon::parse($invoice->invoice_date)->translatedFormat('d F Y H:i:s');
        $data['paidDate']   = $invoice->paid_date != null ? Carbon::parse($invoice->paid_date)->translatedFormat('d F Y H:i:s') : '-';
        $data['dueDate'] = Carbon::parse($invoice->due_date)->translatedFormat('d F Y H:i:s');
        $data['paymentMethod'] = PaymentMethod::where('is_active', 1)->get();
        return $data;
    }

    public function showInvoice($invoiceNumber)
    {
        $data = $this->detailInvoice($invoiceNumber);
        return view('dashboard.invoice-show', $data);
    }

    public function createPayment(Request $request)
    {
        $this->validate($request, [
            'invoice_number'    => 'required',
            'payment'           => 'required'
        ]);

        try { 

            DB::beginTransaction();

            $invoice = Invoice::where('invoice_number', $request->invoice_number)->first();

            $method = PaymentMethod::where('id', $request->payment)->firstOrFail();
            
            $invoice->payment_method_id = $method->id;

            if($method->payment_service == "Manual") {
                $invoice->billing_number = $method->account_number;
            }
            elseif($method->payment_service == "Midtrans") {
                $midtrans = App::make(MidtransController::class);
                $billNumber = $midtrans->sendPayment($invoice);
                $invoice->billing_number = $billNumber;
            }

            $invoice->save();

            NotificationService::invoice($invoice, 'create_payment');

            DB::commit();
            Session::flash('success', 'Pembayaran Berhasil Dibuat, Silahkan Lakukan Pembayaran');
            return response()->json(['status' => 'success']);

        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function uploadPayment(Request $request)
    {
        $this->validate($request, [
            'invoice_amount'    => 'required',
            'file'              => 'required|mimes:jpg,jpeg,png',
            'paid_date'         => 'required|date'
        ]);

        try {

            DB::beginTransaction();

            $invoice = Invoice::where('invoice_number', $request->invoice_number)->firstOrFail();
            $invoice->histories()->create([
                'invoice_number'    => $request->invoice_number,
                'amount'            => str_replace('.', '', $request->invoice_amount),
                'evidence'          => $request->file('file')->store('payment', 'public'),
                'paid_date'         => $request->paid_date
            ]);
            $invoice->status = Constant::INVOICE_WAITING_SELLER_CONFIRMED;
            $invoice->paid_date = $request->paid_date;
            $invoice->save();

            NotificationService::invoice($invoice, 'uploaded');

            DB::commit();

            Session::flash('success', 'Bukti Transfer Berhasil Diupload');
            return response()->json(['status' => 'success']);

        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    public function confirmPayment(Request $request)
    {
        $this->validate($request, [
            'invoice_number'    => 'required'
        ]);

        try {

            DB::beginTransaction();

            $invoice = Invoice::where('invoice_number', $request->invoice_number)->firstOrFail();
            $invoice->status = "Sudah Dibayar";
            $invoice->save();

            if($invoice->order->quotation->payment_type == "Term Of Payment"){
                $invoice->order->activities()->create([
                    'channel_id'    => $invoice->order->channel_id,
                    'number'        => $invoice->order_number,
                    'description'   => Constant::INVOICE_TOP_PAID
                ]);
            } else {
                $invoice->order()->first()->fill([
                    'order_status'  => 7
                ])->save();

                $invoice->order->activities()->create([
                    'channel_id'    => $invoice->order->channel_id,
                    'number'        => $invoice->order_number,
                    'description'   => Constant::INVOICE_CBD_PAID
                ]);
            }

            if(TokoDaringService::isOrderFromLpse($invoice->order->order_number)){
                $reason = Constant::NOTES_TOKODARING_ACCEPTED;
                $formatReporting = TokoDaringService::formatConfirmTransaction($invoice->order->order_number, $invoice->order, $reason);
                $tokodaringController = App::make(TokoDaringController::class);
                $tokodaringController->reportTransaction($formatReporting);
            }

            NotificationService::invoice($invoice, 'confirmed');
    
            Session::flash('success', 'Pembayaran Berhasil Dikonfirmasi');
            DB::commit();
            return response()->json(['status' => 'success']);   

        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function reportInvoice($invoiceNumber)
    {
        $data = $this->detailInvoice($invoiceNumber);
        return view('dashboard.print.invoice', $data);
    }

}
