<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\Constant;
use App\Helpers\Helpers;
use App\Http\Controllers\API\TokoDaringController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Product\MediaController;
use App\Models\DocumentRequest;
use App\Models\Media;
use App\Models\PurchaseOrder;
use App\Models\Status;
use App\Repositories\OrderRepository;
use App\Repositories\QuotationRepository;
use App\Repositories\UserRepository;
use App\Services\InvoiceService;
use App\Services\NotificationService;
use App\Services\ProductService;
use App\Services\PurchaseOrderService;
use App\Services\TokoDaringService;
use App\Traits\GenerateNumber;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class PurchaseOrderController extends Controller
{
    use GenerateNumber;

    public function index(Request $request)
    {
        if($request->ajax()){

            $filter = $request->input('filter');

            $query = OrderRepository::findAll($filter);

            return DataTables::of($query)
                            ->addIndexColumn()
                            ->addColumn('grand_total', function($query) {
                                return number_format($query->grand_total,0,'.','.');
                            })
                            ->make(true);
        }

        $data['status'] = Status::all();
        return view('dashboard.transaction.purchase-order', $data);
    }

    public function createPurchaseOrder(Request $request)
    {
        
        DB::beginTransaction();

        try { 
            $data['quotationNumber'] = $request->quotation_number;
            $data['orderNumber'] = $this->generatePurchaseOrderNumber();
            $order = PurchaseOrderService::createPurchaseOrder($data);
            NotificationService::quotation($data['quotationNumber'], 
                'approved', 
                ['po_number' => $data['orderNumber']]
            );

            /* Send Purchase Order to Tokodaring */
            if(Auth::user()->username_lpse != null){
                $formatReporting = TokoDaringService::formatReportingTransaction($order, auth()->user()->lpse);
                $tokodaringController = App::make(TokoDaringController::class);
                $tokodaringController->reportTransaction($formatReporting);
            }

            DB::commit();
            Session::flash('success', 'Pesanan Berhasil Dibuat');
            return response()->json(['status' => 'success']);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    public function confirmPurchaseOrder(Request $request)
    {

        DB::beginTransaction();

        try {
            
            $orderNumber = $request->order_number;
            $order = PurchaseOrder::where('order_number', $orderNumber)->first();
            if($order->quotation->payment_type == "Cash Before Delivery" 
            && $order->quotation->termin == 0)
            {
                InvoiceService::createInvoice($order, $this->generateInvoiceNumber());   
                $order->order_status = 15; // Menunggu Pembayaran
                $optDesc = Constant::PURCHASE_ORDER_CBD;

            }else {
                $order->order_status = 7; // Pesanan Diproses
                $optDesc = sprintf(Constant::PURCHASE_ORDER_TOP, Carbon::parse($order->order_shipped_estimation)->translatedFormat('d F Y H:i:s'));
            }

            $order->activities()->create([
                'channel_id'    => $order->channel_id,
                'number'        => $order->order_number,
                'description'   => sprintf(Constant::PURCHASE_ORDER_CONFIRMED, $optDesc)
            ]);

            $order->save();
            
            NotificationService::purchaseOrder($orderNumber, 'approved', [
                'confirmed'   => sprintf(Constant::PURCHASE_ORDER_CONFIRMED, $optDesc)
            ]);

            Session::flash('success',  'Pesanan Berhasil Dikonfirmasi, Segera Proses Pengiriman Dihalaman Pesanan');
            DB::commit();
            return response()->json(['status' => 'success']);

        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function rejectPurchaseOrder(Request $request)
    {
        DB::beginTransaction();

        try {
            
            $orderNumber = $request->order_number;
            $order = PurchaseOrder::where('order_number', $orderNumber)->first();
            $order->order_status = 14; // Ditolak Penjual
            $order->save();

            $order->activities()->create([
                'channel_id'    => $order->channel_id,
                'number'        => $order->order_number,
                'description'   => sprintf(Constant::PURCHASE_ORDER_REJECTED,$request->rejected_reason)
            ]);

            ProductService::removeStockHold($order->quotation);

            NotificationService::purchaseOrder($orderNumber, 'rejected', [
                'rejected' => sprintf(Constant::PURCHASE_ORDER_REJECTED,$request->rejected_reason)
            ]);

            if(TokoDaringService::isOrderFromLpse($orderNumber)){
                $reason = sprintf(Constant::PURCHASE_ORDER_REJECTED,$request->rejected_reason);
                $formatReporting = TokoDaringService::formatConfirmTransaction(false, $order, $reason);
                $tokodaringController = App::make(TokoDaringController::class);
                $tokodaringController->confirmTransaction($formatReporting);
            }

            Session::flash('success',  'Pesanan Berhasil Ditolak');
            DB::commit();
            return response()->json(['status' => 'success']);

        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deletePurchaseOrder(Request $request)
    {
        DB::beginTransaction();

        try {
            
            $orderNumber = $request->order_number;
            $order = PurchaseOrder::where('order_number', $orderNumber)->first();
            $order->delete();

            Session::flash('success',  'Pesanan Berhasil Dihapus');
            DB::commit();
            return response()->json(['status' => 'success']);

        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function detailPurchaseOrder($orderNumber)
    {
        $order = PurchaseOrder::query()->where('order_number', $orderNumber)->firstOrFail();
        $merchant = $order->quotation->merchant;
        $merchantAddress = UserRepository::merchantAddress($merchant->id);
        
        $activities =   $order->activities()->orderByDesc('id')->get();

        $shippingAddress = QuotationRepository::shippingAddress($order->channel->rfq_number);
        $billingAddress = QuotationRepository::billingAddress($order->channel->rfq_number);

        $data['produk'] = $order->quotation->productRequests()->get();  
        $data['user']  = $order->quotation->user;
        $data['date']  = Carbon::parse($order->purchase_date)->translatedFormat('d F Y H:i:s');
        $data['merchant'] = $merchant;
        $data['shippingAddress'] = Helpers::formatAddress($shippingAddress->first());
        $data['billingAddress'] = Helpers::formatAddress($billingAddress->first());
        $data['merchantAddress'] = Helpers::formatAddress($merchantAddress->first());
        $data['order'] = $order;
        $data['paymentType'] = $order->quotation->payment_type;
        $data['choosePaymentType'] = Helpers::paymentType();
        $data['grandTotal'] = $order->quotation->grand_total;
        $data['activities'] = $activities;
        $data['dateEstimation'] = Carbon::now()->addDays($order->quotation->shippingRequest->date_estimation)->format('Y-m-d');
        $data['orderShippedEstimation'] = Carbon::parse($order->order_shipped_estimation)->translatedFormat('d F Y H:i:s');
        $data['allowEdited'] = $this->allowEdited($order->order_status);
        return $data;
    }

    public function showPurchaseOrder($orderNumber)
    {
        $data = $this->detailPurchaseOrder($orderNumber);
        return view('dashboard.transaction.show.purchase-order', $data);
    }

    private function allowEdited($status)
    {
        if(Auth::user()->hasRole('Customer') == true){
            return $status == 9 ? true : false;
        }else {
            return $status == 1 ? true : false;
        }
    }

    public function uploadDocument(Request $request)
    {
        try {

            DB::beginTransaction();

            $media = App::make(MediaController::class);
            $newRequest = new Request();
            $newRequest->merge([
                'path'      => $request->path,
                'media_type'=> $request->media_type
            ]);
            $newRequest->files->set('file', $request->file('file'));
            $storeDoc = $media->store($newRequest);
            $resp = $storeDoc->getData();
            DocumentRequest::create([
                'number'    => $request->number,
                'media_id'  => $resp->data->id,
                'user_id'   => Auth::id(),
            ]);

            DB::commit();

            return $resp;
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
            DB::rollBack();
        }
    }

    public function deleteDocument(Request $request, $id)
    {
        try {

            DB::beginTransaction();
            DocumentRequest::where('media_id', $id)->delete();
            DB::commit();

            return response()->json([
                'message'   => 'Dokumen berhasil dihapus',
                'success'   => true
            ]);

        } catch(Exception $e) {
            throw new Exception($e->getMessage());
            DB::rollBack();
        }
    }

    public function reportPurchaseOrder($orderNumber)
    {
        $data = $this->detailPurchaseOrder($orderNumber);
        return view('dashboard.print.purchase-order', $data);
    }
}
