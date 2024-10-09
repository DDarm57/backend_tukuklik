<?php 

namespace App\Services;

use App\Helpers\Constant;
use App\Helpers\Helpers;
use App\Http\Controllers\API\TokoDaringController;
use App\Models\Invoice;
use App\Models\PaymentHistory;
use App\Repositories\QuotationRepository;
use Carbon\Carbon;
use ErrorException;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class MidtransService {

    protected static $serverKey; 
    protected static $url;
    protected static $arrBank = [];

    public static function __constructStatic()
    {
        self::$serverKey = config('services.midtrans.sandbox.server_key');
        self::$url = config('services.midtrans.sandbox.url');
        self::$arrBank = array(
            'BCA Virtual Account'       => 'bca', 
            'BNI Virtual Account'       => 'bni',
            'BRI Virtual Account'       => 'bri',
        );
    }

    public static function sendPayment($data)
    {
        $client = new Client();

        self::__constructStatic();

        $dataBody = self::formatPayment($data);
        $itemDetails = self::itemDetails($data);
        $customerDetails = self::customerDetails($data);
        $customExpiry = self::customExpiry($data);
        
        $body = array_merge($dataBody, $customExpiry, $itemDetails, $customerDetails);
        
        try {
            $request = $client->request('POST', self::$url, [
                'headers' => [
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Basic '.base64_encode(self::$serverKey)
                ],
                'json' => $body
            ]);
        }catch(\GuzzleHttp\Exception\ClientException $e) {
            throw new ErrorException($e->getResponse()->getBody()->getContents());
        }

        $response = $request->getBody();

        PaymentHistory::create([
            'invoice_number'    => $data['invoice_number'],
            'amount'            => $data['invoice_amount'],
            'evidence'          => 'Midtrans',
            'status'            => 'Pembayaran Dibuat',
            'raw_request'       => json_encode($body),
            'raw_created'       => $response
        ]);

        $decode = json_decode($response);

        $billNumber = self::printBillNumber($data, $decode);

        return $billNumber;
    }

    private static function formatPayment($data)
    {
        if(array_key_exists($data['billing_payment'], self::$arrBank)){
            $dataBody = [
                'payment_type'          => 'bank_transfer',
                'transaction_details'   => [
                    'order_id'      => $data['invoice_number'],
                    'gross_amount'  => $data['invoice_amount']
                ],
                'bank_transfer' => [
                    'bank'  => self::$arrBank[$data['billing_payment']]
                ]
            ];
        }
        else if($data['billing_payment'] == "Mandiri Virtual Account"){
            $dataBody = [
                'payment_type'          => 'echannel',
                'transaction_details'   => [
                    'order_id'      => $data['invoice_number'],
                    'gross_amount'  => $data['invoice_amount']
                ],
                'echannel' => [
                    'bill_info1' => Constant::ECHANNEL_NAME,
                    'bill_info2' => Constant::ECHANNEL_CREATED
                ]
            ];
        }
        else if($data['billing_payment'] == "Permata Virtual Account"){
            $dataBody = [
                'payment_type'          => 'permata',
                'transaction_details'   => [
                    'order_id'      => $data['invoice_number'],
                    'gross_amount'  => $data['invoice_amount']
                ],
            ];
        }
        return $dataBody;
    }

    private static function itemDetails($data)
    {
        $invoiceNumber = $data['invoice_number'];
        $itemDetails = Collection::make([]);
        $invoice = Invoice::where('invoice_number', $data['invoice_number'])->first();
        $product = $invoice->order->quotation->productRequests();

        foreach($product->get() as $key => $prod) {
            $itemDetails->push(
                [
                    'id'        => $prod->productSku->sku,
                    'price'     => $prod->base_price,
                    'quantity'  => $prod->quantity,
                    'name'      => $prod->productSku->product->product_name
                ],
                [
                    'id'        => "PPN-". $key+1,
                    'price'     => $prod->tax_amount,
                    'quantity'  => 1,
                    'name'      => 'PPN'
                ],
                [
                    'id'        => "PPH-". $key+1,
                    'price'     => $prod->income_tax_amount * -1,
                    'quantity'  => 1,
                    'name'      => 'PPH'
                ]
            );

            if($key == 0) {
                $itemDetails->push(
                    [
                        'id'        => "Ongkir-0",
                        'price'     => $invoice->order->shipping_amount,
                        'quantity'  => 1,
                        'name'      => 'Ongkos Kirim'
                    ]
                );
            }
        }


        return [
            "item_details"  => $itemDetails->toArray()
        ];
    }

    private static function customerDetails($data)
    {
        $invoice = Invoice::where('invoice_number', $data['invoice_number'])->first();
        $shippingAddress = QuotationRepository::shippingAddress($invoice->order->channel->rfq_number)->first();
        $billingAddress = QuotationRepository::billingAddress($invoice->order->channel->rfq_number)->first();

        $fullName = explode(' ', $invoice->order->quotation->user_pic);

        $firstName = $fullName[0] ?? '';
        $lastName = $fullName[1] ?? '';

        $customerDetails = [
            "first_name"        => $firstName,
            'last_name'         => $lastName,
            'email'             => $invoice->order->quotation->user->email,
            'phone'             => $invoice->order->quotation->user_phone,
            'billing_address'   => [
                'first_name'    => $firstName,
                'last_name'     => $lastName,
                'email'         => '',
                'phone'         => $billingAddress->billing_phone,
                'address'       => $billingAddress->billing_address,
                'city'          => $billingAddress->city_name,
                'postal_code'   => $billingAddress->billing_postcode,
                'country_code'  => "IDN"
            ],
            'shipping_address'   => [
                'first_name'    => $firstName,
                'last_name'     => $lastName,
                'email'         => '',
                'phone'         => $shippingAddress->shipping_phone,
                'address'       => $shippingAddress->shipping_address,
                'city'          => $shippingAddress->city_name,
                'postal_code'   => $shippingAddress->shipping_postcode,
                'country_code'  => "IDN"
            ],
        ];

        return [
            "customer_details"  => $customerDetails
        ];
    }

    private static function customExpiry($data)
    {
        $invoice = Invoice::where('invoice_number', $data['invoice_number'])->first();
        $invoiceDate = Carbon::parse($invoice->invoice_date);
        $expired = Carbon::parse($invoice->due_date)->diffInDays($invoiceDate);

        return [
            "custom_expiry" => [
                "order_time"        => $invoice->invoice_date. " +0700",
                "expiry_duration"   => $expired,
                "unit"              => "day"
            ]
        ];
    }

    public static function handlePayment($payload)
    {
        self::__constructStatic();

        $signValue = $payload->order_id.$payload->status_code.$payload->gross_amount.self::$serverKey;
        $signatureKey = hash('sha512', $signValue);
        if($payload->signature_key == $signatureKey) {

            $status = $payload->transaction_status;
            $settlementTime = $payload->settlement_time;
            $invoiceNumber = $payload->order_id;

            $invoice = Invoice::where('invoice_number', $invoiceNumber)->firstOrFail();
            $data['billing_payment'] = $invoice->paymentMethod->payment_name;

            if($status == "settlement") {
                $invoice->fill([
                    'paid_date' => $settlementTime,
                    'status'    => Constant::INVOICE_ALREADY_PAID
                ])->save();

                $invoice->histories()->update([
                    'raw_updated'   => json_encode($payload),
                    'status'        => Constant::INVOICE_ALREADY_PAID
                ]);

                $invoice->order->activities()->create([
                    'channel_id'    => $invoice->order->channel_id,
                    'number'        => $invoice->order->order_number,
                    'description'   => $invoice->payment_type == "Cash Before Delivery" ? Constant::INVOICE_CBD_PAID : Constant::INVOICE_TOP_PAID 
                ]);
                
                if($invoice->payment_type == "Cash Before Delivery"){
                    $invoice->order()->update([
                        'order_status'  => 7 //Pesanan Diproses
                    ]);
                }
                $statusLpse = true;
            }
            else if($status == "expire" || $status == "deny"){
                $invoice->fill([
                    'status'    => Constant::INVOICE_EXPIRED,
                ])->save();

                $invoice->histories()->update([
                    'raw_updated'   => json_encode($payload),
                    'status'        => Constant::INVOICE_EXPIRED
                ]);

                $invoice->order->activities()->create([
                    'channel_id'    => $invoice->order->channel_id,
                    'number'        => $invoice->order->order_number,
                    'description'   => Constant::INVOICE_HAS_EXPIRED
                ]);

                if($invoice->payment_type == "Cash Before Delivery"){
                    $invoice->order()->update([
                        'order_status'  => 12 //Kadaluwarsa
                    ]);
                }
                $statusLpse = false;
            }

            if(
                TokoDaringService::isOrderFromLpse($invoice->order->order_number) &&
                $invoice->order->quotation->payment_type == Constant::TOP
            ){
                $reason = Constant::NOTES_TOKODARING_ACCEPTED;
                $formatReporting = TokoDaringService::formatConfirmTransaction($statusLpse, $invoice->order, $reason);
                $tokodaringController = App::make(TokoDaringController::class);
                $tokodaringController->confirmTransaction($formatReporting);
            }

            NotificationService::invoice($invoice, $status);

            return true;

        } else{
            throw new Exception("Signature key tidak valid!");
        }
    }

    private static function printBillNumber($data, $payload)
    {
        if(array_key_exists($data['billing_payment'], self::$arrBank)){
            $billNumber = $payload->va_numbers[0]->va_number;
        }
        else if($data['billing_payment'] == "Mandiri Virtual Account"){
           $billNumber = $payload->bill_key;
        }
        else if($data['billing_payment'] == "Permata Virtual Account"){
            $billNumber = $payload->permata_va_number;
        }

        return $billNumber;
    }

}