<?php 

namespace App\Services;

use App\Helpers\Constant;
use App\Models\Invoice;
use App\Models\TransactionActivity;
use App\Traits\GenerateNumber;
use Carbon\Carbon;

class InvoiceService {

    public static function createInvoice($order, $invoiceNumber)
    {
        $invoice = new Invoice;

        $payload['invoice_number']  = $invoiceNumber;
        $payload['order_number']    = $order->order_number;
        $payload['invoice_amount']  = $order->grand_total;
        $payload['payment_type']    = $order->quotation->payment_type;
        $payload['invoice_date']    = Carbon::now();
        $payload['due_date']        = Carbon::now()->addDays($order->quotation->termin > 0 ? $order->quotation->termin : 1);

        $invoice->fill($payload)->save();

        TransactionActivity::create([
            'channel_id'    => $invoice->order->channel_id,
            'number'        => $payload['invoice_number'],
            'description'   => sprintf(Constant::INVOICE_CREATED, $payload['invoice_date'], $payload['due_date'])
        ]);

        NotificationService::invoice($invoice, 'create_invoice');
    }

}