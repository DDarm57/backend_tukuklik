<?php 

namespace App\Services;

use App\Helpers\Constant;
use App\Helpers\Helpers;
use App\Models\PurchaseOrder;
use App\Models\Quotation;
use Carbon\Carbon;

class PurchaseOrderService {

    public static function createPurchaseOrder($data)
    {
        $quotation = Quotation::where('number', $data['quotationNumber'])->firstOrFail();
        $quotation->status = 5;
        $quotation->save();

        $orderShippedEstimation = 0; //In Days
        foreach($quotation->productRequests()->get() as $prod) {
            $orderShippedEstimation += $prod->processing_estimation;
        }

        $quotation->channel->fill(['po_number' => $data['orderNumber']])->save();

        $purchaseOrder = PurchaseOrder::create([
            'order_number'          => $data['orderNumber'],
            'quotation_number'      => $data['quotationNumber'],
            'order_status'          => 1, // Menunggu Konfirmasi Penjual,
            'subtotal'              => $quotation->subtotal,
            'tax_amount'            => $quotation->tax_amount,
            'income_tax'            => $quotation->income_tax,
            'shipping_amount'       => $quotation->shipping_amount,
            'discount'              => $quotation->discount,
            'grand_total'           => $quotation->grand_total,
            'purchase_date'         => Carbon::now(),
            'purchase_deadline_date'=> Carbon::now()->addDay(Helpers::generalSetting()->expired_po ?? 1),
            'order_shipped_estimation' => Carbon::now()->addDays($orderShippedEstimation ?? 1),
            'user_id'               => $quotation->user_id,
            'merchant_id'           => $quotation->merchant_id,
            'channel_id'            => $quotation->channel_id
        ]);

        $quotation->activities()->create([
            'channel_id'    => $quotation->channel_id,
            'number'        => $quotation->number,
            'description'   => sprintf(Constant::QUOTATION_APPROVED,$data['orderNumber']) 
        ]);

        return $purchaseOrder;
    }

}