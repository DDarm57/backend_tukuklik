<?php 

namespace App\Services;

use App\Helpers\Constant;
use App\Helpers\Helpers;
use App\Models\AddressRequest;
use App\Models\Cart;
use App\Models\CustomerAddresses;
use App\Models\ProductRequest;
use App\Models\Quotation;
use App\Models\QuotationChannel;
use App\Models\ShippingRequest;
use App\Models\TransactionActivity;
use App\Models\TransactionChannel;
use App\Models\User;
use Carbon\Carbon;

class QuotationService {

    public static function saveAddress($data)
    {
        $shippingAddress = $data['shipping_address'];
        $billingAddress = $data['billing_address'];
        $sameAddress = Helpers::formatAddress($shippingAddress) 
                    == Helpers::formatAddress($billingAddress) ? 1 : 0;
        $addressRequest = new AddressRequest;
        $addressRequest->customer_id = $data['user_id'];
        $addressRequest->number = $data['number'];
        $addressRequest->shipping_name = $data['user_pic'];
        $addressRequest->shipping_phone = $data['user_phone'];
        $addressRequest->shipping_address = $shippingAddress->full_address;
        $addressRequest->shipping_province_id = $shippingAddress->shipping_province_id;
        $addressRequest->shipping_city_id = $shippingAddress->shipping_city_id;
        $addressRequest->shipping_district_id = $shippingAddress->shipping_district_id;
        $addressRequest->shipping_subdistrict_id = $shippingAddress->shipping_subdistrict_id;
        $addressRequest->shipping_postcode  = $shippingAddress->shipping_postcode;
        $addressRequest->bill_to_same_address = $sameAddress;
        $addressRequest->billing_name = $data['user_pic'];
        $addressRequest->billing_phone = $data['user_phone'];
        $addressRequest->billing_address = $billingAddress->full_address;
        $addressRequest->billing_province_id = $billingAddress->shipping_province_id;
        $addressRequest->billing_city_id = $billingAddress->shipping_city_id;
        $addressRequest->billing_district_id = $billingAddress->shipping_district_id;
        $addressRequest->billing_subdistrict_id = $billingAddress->shipping_subdistrict_id;
        $addressRequest->billing_postcode  = $billingAddress->shipping_postcode;
        $addressRequest->save();
    }

    public static function saveProductRFQ($data)
    {
        $pph = $data['pph'] ?? [];
        $carts = $data['carts'];
        $discount = $data['discount'] / count($carts) ?? 0;

        foreach($carts as $key => $cart) {
            
            $taxPercentage = ($cart->tax_amount / $cart->subtotal) * 100;
            $incomeTaxPercentage = $pph[$key] / 100;
            $basePrice = $cart->base_price;
            $subTotal = $cart->base_price * $cart->qty;
            if($discount > 0) {
                $subTotal = $subTotal - $discount;
                $basePrice = $subTotal / $cart->qty;
            }
            $taxAmount = $subTotal * $taxPercentage;
            $incomeTaxAmount = $subTotal * $incomeTaxPercentage;

            $productRequest = new ProductRequest;
            $productRequest->product_sku_id = $cart->product_sku_id;
            $productRequest->number = $data['number'];
            $productRequest->quantity = $cart->qty;
            $productRequest->base_price = $basePrice;
            $productRequest->subtotal = $subTotal;
            $productRequest->tax_amount = $taxAmount;
            $productRequest->income_tax_amount = $incomeTaxAmount;
            $productRequest->total_price = $subTotal + $taxAmount - $incomeTaxAmount;
            $productRequest->tax_percentage = $taxPercentage;
            $productRequest->income_tax_percentage = $incomeTaxPercentage;
            $productRequest->stock_type = $cart->productSku->product->stock_type;
            $productRequest->processing_estimation = $cart->productSku->product->processing_estimation;
            $productRequest->save();
        }
    }

    public static function saveRFQ($data)
    {
        $channel = TransactionChannel::create(['rfq_number' => $data['number']]);
                
        $product = ProductRequest::where('number', $data['number'])->get();
        $subTotal = 0;
        $taxAmount = 0;
        $incomeTax = 0;
        foreach($product as $prod) {
            $subTotal += $prod->subtotal;
            $taxAmount += $prod->tax_amount;
            $incomeTax += $prod->income_tax_amount;
        }
        $quotation = new Quotation;
        $quotation->number = $data['number'];
        $quotation->purpose_of = $data['purpose_of'];
        $quotation->subtotal = $subTotal;
        $quotation->tax_amount = $taxAmount;
        $quotation->income_tax = $incomeTax;
        $quotation->discount = $data['discount'];
        $quotation->grand_total = $subTotal + $taxAmount - $incomeTax;
        $quotation->merchant_id = $data['merchant_id'];
        $quotation->user_id = $data['user_id'];
        $quotation->user_pic = $data['user_pic'];
        $quotation->user_phone = $data['user_phone'];
        $quotation->notes_for_merchant = $data['notes_for_merchant'];
        $quotation->payment_type = $data['payment_type'];
        $quotation->termin = $data['termin'];
        $quotation->is_merchant_pkp = $data['is_merchant_pkp'];
        $quotation->date = $data['date'];
        $quotation->deadline_date = $data['deadline_date'];
        $quotation->status = $data['status'];
        $quotation->channel_id = $channel->id;
        $quotation->save();

        $deadline =  Carbon::parse($data['deadline_date'])->translatedFormat('d F Y H:i:s');

        $quotation->activities()->create([
            'channel_id'    => $channel->id,
            'number'        => $data['number'],
            'description'   =>  sprintf(Constant::RFQ_CREATED,'<b>'.$data['number'].'</b>',$deadline),
        ]);

        $user = User::where('id', $data['user_id'])->first();
        if($user->phone_number == ''){
            $user->phone_number = $data['user_phone'];
            $user->save();
        }
        Cart::where('user_id', $data['user_id'])->delete();
    }

    public static function saveQuote($data)
    {
        $shippingAmount = str_replace('.','',$data['shipping_fee']);
        $productRequest = ProductRequest::where('number', $data['rfqNumber'])->get();
        
        $subTotal = 0;
        $taxAmount = 0;
        $incomeTax = 0;
        $deadline =  Carbon::parse($data['deadline_date'])->translatedFormat('d F Y H:i:s');

        $quotation = Quotation::where('number', $data['rfqNumber'])->first();
        $quotation->status = substr($data['rfqNumber'],0,2) == "QN" ? 14 : 4; // Jika submit quote dari negosiasi maka tolak old quote, jika dari rfq buat quotation baru;
        $quotation->save();

        foreach($productRequest as $index => $product) {
            $basePrice = str_replace(',','.',str_replace('.','',$data['base_price'][$index]));
            $newProductReq = $product->replicate();
            $newProductReq->number = $data['quotationNumber'];
            $newProductReq->base_price = $basePrice;
            $newProductReq->subtotal = $basePrice * $product->quantity;
            $newProductReq->tax_amount = $newProductReq->subtotal * ($data['ppn'][$index]/100);
            $newProductReq->income_tax_amount = $newProductReq->subtotal * ($data['pph'][$index]/100);
            $newProductReq->total_price = $newProductReq->subtotal + $newProductReq->tax_amount - $newProductReq->income_tax_amount;
            $newProductReq->tax_percentage = $data['ppn'][$index]/100;
            $newProductReq->income_tax_percentage = $data['pph'][$index]/100;
            $newProductReq->save();

            $subTotal += $newProductReq->subtotal;
            $taxAmount += $newProductReq->tax_amount;
            $incomeTax += $newProductReq->income_tax_amount;

            ProductService::stockHold($newProductReq);

        }

        $newQuote = $quotation->replicate();
        $newQuote->number = $data['quotationNumber'];
        $newQuote->subtotal = $subTotal;
        $newQuote->tax_amount = $taxAmount;
        $newQuote->income_tax = $incomeTax;
        $newQuote->shipping_amount = $shippingAmount;
        $newQuote->grand_total = $subTotal + $taxAmount + $shippingAmount - $incomeTax;
        substr($data['rfqNumber'],0,2) != "QN" ? $newQuote->notes_for_buyer = $data['notes_for_buyer'] : '';
        $newQuote->payment_type = $data['payment_type'];
        $newQuote->termin =  $data['payment_type'] == "Term Of Payment" ? $data['termin'] : 0;
        $newQuote->date = $data['date'];
        $newQuote->deadline_date = $data['deadline_date'];
        $newQuote->status = 2; // menunggu persetujuan pembeli;


        if(substr($data['rfqNumber'],0,2) == "QN"){

            $channel = TransactionChannel::where('quotation_number', $data['rfqNumber'])->first();
            $newChannel = $channel->replicate();
            $newChannel->quotation_number = $data['quotationNumber'];
            $newChannel->save();
            $newQuote->channel_id = $newChannel->id;

            $activities = [
                [
                    'channel_id'    => $channel->id,
                    'number'        => $data['rfqNumber'],
                    'description'   => sprintf(Constant::NEGOTIATION_APPROVED, $data['quotationNumber']),
                    'created_at'    => Carbon::now()
                ],
                [
                    'channel_id'    => $channel->id,
                    'number'        => $data['quotationNumber'],
                    'description'   => sprintf(Constant::QUOTATION_CREATED, $data['quotationNumber'], $deadline),
                    'created_at'    => Carbon::now()
                ]
            ];

            TransactionActivity::insert($activities);

            $showActivities = TransactionActivity::where('channel_id', $quotation->channel_id)->get();
            foreach($showActivities as $act) {
                $newActivity = $act->replicate();
                $newActivity->channel_id = $newChannel->id;
                $newActivity->save();
            }

            $shippingRequest = ShippingRequest::where('number', $data['rfqNumber'])->first();
            $newShip = $shippingRequest->replicate();
            $newShip->number = $data['quotationNumber'];
            $newShip->save();

        }else {

            $addressRequest = AddressRequest::where('number', $data['rfqNumber'])->first();
    
            ShippingRequest::create([
                'number'                =>  $data['quotationNumber'],
                'address_request_id'    =>  $addressRequest->id,
                'shipping_method'       =>  $data['shipping_method'],
                'shipping_fee'          =>  $shippingAmount,
                'date_estimation'       =>  $data['date_estimation']
            ]);

            $channel = TransactionChannel::updateOrCreate(['rfq_number' => $data['rfqNumber'] 
            ],
                ['rfq_number' => $data['rfqNumber'], 'quotation_number' => $data['quotationNumber']]
            );

            $activities = [
                [
                    'channel_id'    => $channel->id,
                    'number'        => $data['rfqNumber'],
                    'description'   => Constant::RFQ_APPROVED,
                    'created_at'    => Carbon::now()
                ],
                [
                    'channel_id'    => $channel->id,
                    'number'        => $data['quotationNumber'],
                    'description'   => sprintf(Constant::QUOTATION_CREATED, $data['quotationNumber'], $deadline),
                    'created_at'    => Carbon::now()
                ]
            ];

            TransactionActivity::insert($activities);

        }

        $newQuote->save();
        
    }
}