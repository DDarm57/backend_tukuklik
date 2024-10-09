<?php 

namespace App\Repositories;

use App\Models\Banner;
use App\Models\FlashDealProduct;
use Carbon\Carbon;

class MarketingRepository {

    public static function banners()
    {
        return Banner::where('status', 1)->get();
    }

    public static function flashDeals()
    {
        $flashSale = FlashDealProduct::whereHas('flashDeal', function($q) {
                        $q->where('start_date', '<=', Carbon::today()->format('Y-m-d'));
                        $q->where('end_date', '>=', Carbon::today()->format('Y-m-d'));
                    })
                    ->with(['product', 'flashDeal'])
                    ->join(
                        'products',
                        'flash_deal_products.product_id',
                        'products.id'
                    )
                    ->join(
                        'product_skus',
                        'products.id',
                        'product_skus.product_id'
                    )
                    ->join(
                        'merchants',
                        'products.merchant_id',
                        'merchants.id'
                    )
                    ->selectRaw('
                        CASE WHEN flash_deal_products.discount_type = "Harga"
                        THEN
                            (flash_deal_products.discount / product_skus.selling_price) * 100
                        ELSE 
                           flash_deal_products.discount
                        END 
                        AS disc,

                        CASE WHEN flash_deal_products.discount_type  = "Harga"
                        THEN
                            product_skus.selling_price - flash_deal_products.discount
                        ELSE 
                            product_skus.selling_price - 
                            (
                                product_skus.selling_price * (flash_deal_products.discount / 100)
                            )
                        END 
                        AS real_price,
                        
                        product_skus.*,
                        products.*,
                        flash_deal_products.*
                    ')
                    ->where('product_skus.is_primary', 'Y')
                    ->get();
        
        return $flashSale;      
    }
    
}