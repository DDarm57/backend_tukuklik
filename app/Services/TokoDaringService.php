<?php

namespace App\Services;

use App\Helpers\Helpers;
use App\Repositories\MerchantRepository;
use App\Repositories\QuotationRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use stdClass;

class TokoDaringService {
    
    public static function formatReportingTransaction($order, $lpseAccount)
    {
        $itemProduct = [];
        $courier = [];
        foreach($order->quotation->productRequests()->get() as $packages){
            $shippingMethod = $order->quotation->shippingRequest->shippingMethod->method_name;
            if(!in_array($shippingMethod, $courier)){
                array_push($courier, $shippingMethod);
            }
            $formatProduct = self::formatProducts($packages->productSku, $packages);
            $itemProduct[] = $formatProduct;
        }

        $shippingAddress = QuotationRepository::shippingAddress($order->quotation->number);
        $formatShippingAddress = Helpers::formatAddress($shippingAddress->first());

        $reportData = [
            'order_number'                  => $order->order_number,
            'email'                         => $order->user->email,
            'phone'                         => $order->quotation->user_phone,
            'username'                      => $lpseAccount->username,
            'items'                         => $itemProduct,
            'shipping_address'              => $formatShippingAddress,
            'shipping_amount'               => (int) $order->shipping_amount,
            'shipping_amount_vat'           => 0,
            'shipping_amount_vat_percent'   => 0,
            'shipping_income_tax'           => 0,
            'shipping_income_tax_percent'   => 0,
            'vat_total'                     => (int) $order->tax_amount,
            'income_tax_total'              => (int) $order->income_tax,
            'grand_total'                   => (int) $order->grand_total,
            'payment_method'                => $order->quotation->payment_type,
            'courier'                       => $order->quotation->shippingRequest->shippingMethod->method_name,
            'token'                         => $lpseAccount->token_lpse
        ];
        return $reportData;
    }

    public static function formatConfirmTransaction($status, $order, $desc)
    {
        $tokenOrder = DB::table('purchase_order_tokens')->where('order_number', $order->order_number)->first();
        return [
            'order_number'          => $order->order_number,
            'status'                => $status,
            'keterangan'            => $desc,
            'metode_bayar'          => $order->quotation->payment_type,
            'valuasi'               => (int) $order->grand_total,
            'token'                 => $tokenOrder->token
        ];
    }

    public static function isOrderFromLpse($orderNumber)
    {
        return DB::table('purchase_order_tokens')->where('order_number', $orderNumber)->count() == 1 ? true : false;
    }

    public static function formatProducts($productSku, $productDetails = null)
    {
        $galaryImages = [];
        $categoryProduct = new stdClass;
        $categoryProductv2 = [];
        $tags = [];

        foreach($productSku->product->productPhotos()->get() as $images){
            array_push($galaryImages, url($images->media->path));
        }

        foreach($productSku->product->categories()->get() as $cat) {
            $id = $cat->id;
            $categoryProduct->$id = $cat->name;
            array_push($categoryProductv2, $cat->id);
        }

        foreach($productSku->product->productTags()->get() as $rowTags) {
            array_push($tags, $rowTags->name);
        }

        $merchant = UserRepository::merchantAddress($productSku->product->merchant->id)->first();
        $merchantRating = MerchantRepository::rating($productSku->product->merchant_id);

        $arrProducts = [
            'product_name'          => $productSku->product->product_name,
            'product_price'         => (int) $productSku->selling_price,
            'is_available'          => $productSku->product->status == 1 ? true : false,
            'product_images'        => $productDetails == null ? (object) $galaryImages : $galaryImages,
            'product_thumbnail'     => url($productSku->product->thumbnail_image_source),
            'product_description'   => preg_replace('#<[^>]+>#', ' ', $productSku->product->description),
            'product_category'      => $productDetails == null ? $categoryProductv2 : $categoryProduct,
            'product_weight'        => $productDetails == null ? (float) $productSku->weight / 1000 : (float)$productSku->weight,
            'tags'                  => $productDetails == null ? (object) $tags : $tags,
            'is_PDN'                => true,
            'is_taxable'            => $productSku->product->merchant->is_pkp == "Y" ? true : false,
            'sku'                   => $productSku->sku,
            'merchant_id'           => (string)$productSku->product->merchant->id,
            'merchant_name'         => $productSku->product->merchant->name,
            'merchant_province'     => $merchant->prov_name,
            'merchant_city'         => $merchant->city_name,
            'merchant_npwp'         => $productSku->product->merchant->npwp,
            'merchant_nik'          => $productSku->product->merchant->name,
            'is_pkp'                => $productSku->product->merchant->is_pkp == "Y" ? true : false,
            'merchant_score'        => $merchantRating['merchantRating'],
            'max_merchant_score'    => 5,
            'merchant_type'         => "mikro",
        ];

        if($productDetails != null){
            $arrProducts['kuantitas']       = (int) $productDetails->quantity;
            $arrProducts['total']           = (int) $productDetails->total_price;
            $arrProducts['ppn']             = (int) $productDetails->tax_amount;
            $arrProducts['ppn_persentase']  = $productDetails->tax_percentage * 100;
            $arrProducts['pph']             = (int) $productDetails->income_tax_amount;
            $arrProducts['pph_persentase']  = $productDetails->income_tax_percentage * 100;
        }

        return $arrProducts;
    }
    
    public static function logout($username)
    {
        $cekAccLpse = DB::table('lpse_account')->where('username', $username)->count();
        if($cekAccLpse > 0){
            DB::table('lpse_account')->update([
                'token'         => null,
                'token_lpse'    => null
            ]);
        }
    }
}
