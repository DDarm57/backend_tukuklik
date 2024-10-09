<?php

namespace App\Http\Controllers\Transaction;

use App\Helpers\Constant;
use App\Helpers\Helpers;
use App\Http\Controllers\API\TokoDaringController;
use App\Http\Controllers\Controller;
use App\Models\DeliveryOrder;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Repositories\QuotationRepository;
use App\Repositories\UserRepository;
use App\Services\InvoiceService;
use App\Services\NotificationService;
use App\Services\ProductService;
use App\Services\TokoDaringService;
use App\Traits\GenerateNumber;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class DeliveryOrderController extends Controller
{
    use GenerateNumber;

    public function index(Request $request)
    {
        if($request->ajax()) {
            $query = DeliveryOrder::with([
                'deliveryStatus', 'purchaseOrder', 'shippingRequest', 'shippingRequest.shippingMethod'
            ])->get();
            return DataTables::of($query)->addIndexColumn()->make(true);
        }
        return view('dashboard.shipping.delivery-order');
    }

    public function createDeliveryOrder(Request $request)
    {
        $this->validate($request, [
            'delivery_number'               => 'required',
            'delivery_date'                 => 'required|date',
            'delivery_estimation_date'      => 'required|date',
            'order_number'                  => 'required'
        ]);

        DB::beginTransaction();

        try {
            $doNumber = $this->generateDONumber(); 
            $order = PurchaseOrder::where('order_number', $request->order_number)->firstOrFail();
    
            $order->deliveryOrder()->create([
                'do_number'                 => $doNumber,
                'order_number'              => $order->order_number,
                'delivery_number'           => $request->delivery_number,
                'delivery_recipient_name'   => $order->quotation->shippingRequest->addressRequest->shipping_name,
                'delivery_date'             => $request->delivery_date,
                'date_estimation'           => $request->delivery_estimation_date,
                'delivered_date'            => null,
                'shipping_request_id'       => $order->quotation->shippingRequest->id,
                'status'                    => 8 // sedang dikirim
            ]);

            $order->activities()->create([
                'channel_id'    => $order->channel_id,
                'number'        => $order->order_number,
                'description'   =>  sprintf(Constant::DELIVERY_ORDER_CREATED,$request->delivery_number)
            ]);

            $order->order_status = 8;
            $order->save();

            if($order->quotation->payment_type == "Term Of Payment"){
                InvoiceService::createInvoice($order, $this->generateInvoiceNumber());
            }

            NotificationService::deliveryOrder($doNumber, 'created', [
                'created'   => sprintf(Constant::DELIVERY_ORDER_CREATED,$request->delivery_number)
            ]);

            DB::commit();
            Session::flash('success', 'Pengiriman Berhasil Diproses');
            return response()->json(['status' => 'success']);

        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function confirmDeliveryOrder(Request $request)
    {

        $this->validate($request, [
            'delivery_recipient_name'   => 'required|max:255',
            'delivered_date'            => 'required|date'
        ]);

        try {

            DB::beginTransaction();

            $doNumber = $request->do_number;
            $deliveryOrder = DeliveryOrder::where('do_number', $doNumber)->firstOrFail();
            $deliveryOrder->status = 9; // terkirim
            $deliveryOrder->delivery_recipient_name = $request->delivery_recipient_name;
            $deliveryOrder->delivered_date = $request->delivered_date;
            $deliveryOrder->save();

            $purchaseOrder = $deliveryOrder->purchaseOrder()->first();
    
            $purchaseOrder->fill([
                'order_status'    => 10 // selesai
            ])->save();
    
            $deliveredDate = Carbon::parse($request->delivered_date)->translatedFormat('d F Y');
            $purchaseOrder->activities()->create([
                'channel_id'    => $purchaseOrder->channel_id,
                'number'        => $deliveryOrder->order_number,
                'description'   => sprintf(Constant::DELIVERY_ORDER_CONFIRMED,$request->delivery_recipient_name, $deliveredDate),
            ]);

            ProductService::releaseStock($purchaseOrder->quotation);

            NotificationService::deliveryOrder($doNumber, 'approved');

            if(
                TokoDaringService::isOrderFromLpse($purchaseOrder->order_number) && 
                $purchaseOrder->quotation->payment_type == Constant::CBD
            ){
                $reason = Constant::NOTES_TOKODARING_ACCEPTED;
                $formatReporting = TokoDaringService::formatConfirmTransaction(true, $purchaseOrder, $reason);
                $tokodaringController = App::make(TokoDaringController::class);
                $tokodaringController->confirmTransaction($formatReporting);
            }

            DB::commit();
            Session::flash('success', 'Pengiriman Berhasil Dikonfirmasi');
            return response()->json(['status' => 'success']);

        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function detailDeliveryOrder($number)
    {
        $deliveryOrder = DeliveryOrder::where('do_number', $number)->firstOrFail();
        $merchant = $deliveryOrder->purchaseOrder->quotation->merchant;
        $merchantAddress = UserRepository::merchantAddress($merchant->id);

        $shippingAddress = QuotationRepository::shippingAddress($deliveryOrder->purchaseOrder->channel->rfq_number);
        $billingAddress = QuotationRepository::billingAddress($deliveryOrder->purchaseOrder->channel->rfq_number);

        $data['deliveryOrder'] = $deliveryOrder;
        $data['merchant'] = $merchant;
        $data['produk'] = $deliveryOrder->purchaseOrder->quotation->productRequests()->get();  
        $data['user']  = $deliveryOrder->purchaseOrder->quotation->user;
        $data['date']  = Carbon::parse($deliveryOrder->delivery_date)->translatedFormat('d F Y');
        $data['deliveredDate']  = Carbon::parse($deliveryOrder->delivered_date)->translatedFormat('d F Y');
        $data['shippingAddress'] = Helpers::formatAddress($shippingAddress->first());
        $data['merchantAddress'] = Helpers::formatAddress($merchantAddress->first());
        return $data;
    }

    public function showDeliveryOrder($number)
    {
        $data = $this->detailDeliveryOrder($number);
        return view('dashboard.shipping.show.delivery-order', $data);
    }

    public function reportDeliveryOrder($number)
    {
        $data = $this->detailDeliveryOrder($number);
        return view('dashboard.print.delivery-order', $data);
    }

    public function reportBAST($number)
    {
        $data = $this->detailDeliveryOrder($number);
        return view('dashboard.print.bast', $data);
    }
}
