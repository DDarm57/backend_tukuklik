<?php 

namespace App\Services;

use App\Helpers\Constant;
use App\Helpers\Helpers;
use App\Notifications\Merchant\Invoice as MerchantInvoice;
use App\Models\DeliveryOrder;
use App\Models\PurchaseOrder;
use App\Models\Quotation;
use App\Notifications\Customer\DeliveryOrder as CustomerDeliveryOrder;
use App\Notifications\Customer\Invoice as CustomerInvoice;
use App\Notifications\Customer\Negotiation as CustomerNegotiation;
use App\Notifications\Customer\PurchaseOrder as CustomerPurchaseOrder;
use App\Notifications\Customer\Quotation as CustomerQuotation;
use App\Notifications\Merchant\DeliveryOrder as MerchantDeliveryOrder;
use App\Notifications\Merchant\Negotiation;
use App\Notifications\Merchant\PurchaseOrder as MerchantPurchaseOrder;
use App\Notifications\Merchant\Quotation as MerchantQuotation;
use App\Repositories\QuotationRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class NotificationService {

    public static function coba()
    {
        echo 'cb';
    }
    
    public static function quotation($number, $type, $additional=[])
    {
        $quotation = Quotation::query()->where('number', $number)->first();
        $data['url'] = url('dashboard/request-for-quotation/'.$quotation->number.'');
        $deadline =  Carbon::parse($quotation->deadline_date)->translatedFormat('d F Y H:i:s');
        switch($type) {
            case 'created' : 
                $data['message'] = sprintf(Constant::RFQ_CREATED, $quotation->number, $deadline);
                $data['subject'] = "Permintaan Telah Dibuat";
            break;
            case 'rejected' : 
                $data['message'] = sprintf(Constant::RFQ_REJECTED, $additional['rejected_reason']);
                $data['subject'] = "Permintaan Ditolak";
            break;
            case 'reminder' :
                $data['subject'] = "Reminder Permintaan ". $number;
                $data['message'] = "Reminder Permintaan Yang Belum Dikonfirmasi Dengan Nomor ". $number;
            break;
            case 'expired' :
                $data['subject'] = "Permintaan ".$number." Telah Kadaluwarsa";
                $data['message'] = "Permintaan ".$number." Telah Kadaluwarsa Karena Belum Dikonfirmasi Sampai ".Carbon::parse($quotation->deadline_date)->translatedFormat('d F Y H:i:s')."";
            break;
        }

        $data['deadline']   = $deadline;
        $data['messageTitle'] = "Informasi Permintaan";
        $data['optionalMessage'] = "Pengajuan Permintaan";

        if(substr($quotation->number,0,2) == "QN"){
            switch($type) {
                case 'created' : 
                    $data['message'] = sprintf(Constant::QUOTATION_CREATED, $quotation->number, $deadline);
                    $data['subject'] = "Penawaran Telah Dibuat";
                break;
                case 'approved' : 
                    $data['message'] = sprintf(Constant::QUOTATION_APPROVED, $additional['po_number']);
                    $data['subject'] = "Penawaran Diterima, Pesanan Telah Dibuat";
                break;
                case 'rejected' : 
                    $data['message'] = sprintf(Constant::QUOTATION_REJECTED, $additional['rejected_reason']);
                    $data['subject'] = "Penawaran Ditolak";
                break;
                case 'reminder' :
                    $data['subject'] = "Reminder Penawaran ". $number;
                    $data['message'] = "Reminder Penawaran Yang Belum Dikonfirmasi Dengan Nomor ". $number;
                break;
                case 'expired' :
                    $data['subject'] = "Penawaran ".$number." Telah Kadaluwarsa";
                    $data['message'] = "Penawaran ".$number." Telah Kadaluwarsa Karena Belum Dikonfirmasi Sampai ".Carbon::parse($quotation->deadline_date)->translatedFormat('d F Y H:i:s')."";
                break;
            }
            $data['url'] = url('dashboard/quotation/'.$quotation->number.'');
            $data['messageTitle'] = "Informasi Penawaran";
            $data['optionalMessage'] = "Penawaran";
        }

        Notification::send(Helpers::notifForAllStaffOrMerchant($quotation->merchant->pic->id), new MerchantQuotation($quotation, $data));
        Notification::send($quotation->user, new CustomerQuotation($quotation, $data));
    }
    
    public static function purchaseOrder($number, $type, $additional=[])
    {
        $order = PurchaseOrder::query()->where('order_number', $number)->first();
        $data['url'] = url('dashboard/purchase-order/'.$order->order_number.'');
        switch($type) {
            case 'approved' : 
                $data['message'] = $additional['confirmed'];
                $data['subject'] = "Pesanan Telah Dikonfirmasi Penjual";
            break;
            case 'rejected' : 
                $data['message'] = $additional['rejected'];
                $data['subject'] = "Pesanan Ditolak Penjual";
            break;
            case 'reminder' :
                $data['message'] = "Reminder Pesanan Yang Belum Dikonfirmasi Dengan Nomor ". $number;
                $data['subject'] = "Reminder Pesanan ". $number;
            break;
            case 'expired' :
                $data['message'] = "Pesanan Telah Kadaluwarsa Dengan Nomor". $number;
                $data['subject'] = "Pesanan Kadaluwarsa ". $number;
            break;
        }

        $data['messageTitle'] = "Informasi Pesanan";
        Notification::send(Helpers::notifForAllStaffOrMerchant($order->quotation->merchant->pic->id), new MerchantPurchaseOrder($order, $data));
        Notification::send($order->quotation->user, new CustomerPurchaseOrder($order, $data));

    }

    public static function negotiation($number, $type, $additional=[])
    {
        $quotation = Quotation::query()->where('number', $number)->first();
        $data['url'] = url('dashboard/quotation/'.$quotation->number.'');

        if(count($additional) > 0) {
            $userType = Auth::user()->id == $quotation->user_id ? "Pembeli" : "Penjual";
            $optional = "Dari ". $additional['user']->name. " (". $userType. ") : ". $additional['description']; 
        }

        switch($type) {
            case 'created' : 
                $data['message'] = "Berikut Kami Sampaikan Negosiasi Yang Diajukan Dari Pembeli";
                $data['subject'] = "Negosiasi Penawaran ".$number." Telah Diajukan Kepada Penjual";
            break;
            case 'updated' : 
                $data['message'] = "Pesan Negosiasi Dengan Nomor Penawaran ". $quotation->number. " ". $optional;
                $data['subject'] = "Pesan Negosiasi Penawaran ". $number;
            break;
            case 'approved' : 
                $data['message'] = "Negosiasi Telah Disetujui Penjual, Penawaran Baru Telah Dibuat";
                $data['subject'] = "Negosiasi Penawaran ".$number." Telah Disetujui Penjual";
            break;
            case 'rejected' : 
                $data['message'] = "Negosiasi Ditolak Penjual, Silahkan Konfirmasi Penawaran";
                $data['subject'] = "Negosiasi Penawaran ".$number." Ditolak Oleh Penjual";
            break;
        }

        $data['messageTitle'] = "Informasi Negosiasi";
        Notification::send(Helpers::notifForAllStaffOrMerchant($quotation->merchant->pic->id), new Negotiation($quotation, $data));
        Notification::send($quotation->user, new CustomerNegotiation($quotation, $data));
    }

    public static function invoice($invoice, $type)
    {
        $data['url'] = url('dashboard/invoice/'.$invoice->invoice_number.'');
        $data['attachment'] = '';
        switch($type) {
            case 'create_invoice' : 
                $data['message'] = 'Tagihan Telah Dibuat Dengan Informasi Sebagai Berikut';
                $data['subject'] = "Tagihan Pesanan ".$invoice->order_number." Telah Dibuat";
                $sendBoth = false;
            break;
            case 'create_payment' : 
                $data['message'] = 'Informasi Pembayaran Telah Dibuat Dengan Informasi Sebagai Berikut';
                $data['subject'] = "Informasi Pembayaran No. Invoice ".$invoice->invoice_number." Telah Dibuat";
                $sendBoth = false;
            break;
            case 'uploaded' : 
                $data['message'] = "Pembayaran Telah Dikonfirmasi Manual Oleh Pembeli";
                $data['subject'] = "Pembayaran Telah Diupload, Menunggu Konfirmasi Penjual";
                $data['attachment'] = Storage::url($invoice->histories->evidence);
                $sendBoth = true;
            break;
            case 'confirmed' : 
                $data['message'] = "Pembayaran Telah Terkonfirmasi Dengan Informasi Sebagai Berikut";
                $data['subject'] = "Pembayaran Telah Terkonfirmasi";
                $sendBoth = true;
            break;
            case 'reminder' :
                $data['message'] = "Berikut Kami Informasikan Tagihan Yang Belum Dibayar";
                $data['subject'] = "Tagihan Belum Dibayar";
                $sendBoth = false;
            break;
            case 'due_date' :
                $data['message'] = "Tagihan Dengan Nomor ". $invoice->invoice_number. " Telah Jatuh Tempo";
                $data['subject'] = "Tagihan Jatuh Tempo ". $invoice->invoice_number;
                $sendBoth = false;
            break;
            case 'settlement' :
                $data['message'] = "Tagihan Dengan Nomor ". $invoice->invoice_number. " Sudah Dibayarkan Oleh Pembeli";
                $data['subject'] = "Tagihan ". $invoice->invoice_number. " Telah Dibayar";
                $sendBoth = true;
            break;
            case 'expire' || 'deny'  :
                $data['message'] = "Tagihan Dengan Nomor ". $invoice->invoice_number. " Telah Kadaluwarsa Karena Belum Dibayar Sampai Tenggat Waktu Jatuh Tempo";
                $data['subject'] = "Tagihan ". $invoice->invoice_number. " Telah Kadaluwarsa";
                $sendBoth = true;
            break;
        }

        $data['messageTitle'] = "Informasi Tagihan";
        $data['invoiceDate'] = Carbon::parse($invoice->invoice_date)->translatedFormat('d F Y H:i:s');
        $data['dueDate'] = Carbon::parse($invoice->due_date)->translatedFormat('d F Y H:i:s');
        $data['paidDate'] = Carbon::parse($invoice->paid_date)->translatedFormat('d F Y H:i:s');

        if($sendBoth){
            Notification::send(Helpers::notifForAllStaffOrMerchant($invoice->order->quotation->merchant->pic->id), new MerchantInvoice($invoice, $data));
        }
        Notification::send($invoice->order->quotation->user, new CustomerInvoice($invoice, $data));
    }

    public static function deliveryOrder($doNumber, $type, $additional=[])
    {
        $deliveryOrder = DeliveryOrder::query()->where('do_number', $doNumber)->first();
        $data['url'] = url('dashboard/shipping-order/'.$doNumber.'');
        switch($type) {
            case 'created' : 
                $data['message'] = 'Pengiriman Telah Diproses Penjual, Berikut Informasinya';
                $data['subject'] = "Pesanan Telah Dikirim Penjual";
            break;
            case 'approved' : 
                $data['message'] = 'Pengiriman Telah Dikonfirmasi Oleh Pembeli, Pesanan Selesai.';
                $data['subject'] = "Pesanan Telah Diterima Oleh Pembeli";
            break;
        }

        $merchant = $deliveryOrder->purchaseOrder->quotation->merchant;

        $merchantAddress = UserRepository::merchantAddress($merchant->id);
        $shippingAddress = QuotationRepository::shippingAddress($deliveryOrder->purchaseOrder->channel->rfq_number);

        $data['messageTitle'] = "Informasi Pengiriman";
        $data['date_estimation'] = Carbon::parse($deliveryOrder->date_estimation)->translatedFormat('d F Y');
        $data['delivery_date'] = Carbon::parse($deliveryOrder->delivery_date)->translatedFormat('d F Y');
        $data['delivered_date'] = Carbon::parse($deliveryOrder->delivered_date)->translatedFormat('d F Y');
        $data['merchantAddress'] = Helpers::formatAddress($merchantAddress->first());
        $data['shippingAddress'] = Helpers::formatAddress($shippingAddress->first());

        Notification::send($deliveryOrder->purchaseOrder->quotation->user, new CustomerDeliveryOrder($deliveryOrder, $data));
        Notification::send(Helpers::notifForAllStaffOrMerchant($deliveryOrder->purchaseOrder->quotation->merchant->pic->id), new MerchantDeliveryOrder($deliveryOrder, $data));
    }   
}
