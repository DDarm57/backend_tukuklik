<?php 

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductReview;

class MerchantRepository {

    public static function rating($merchantId)
    {
        $merchantRating =   ProductReview::query()->whereHas('product', function($q) use($merchantId){
                                $q->where('merchant_id', $merchantId);
                            });
        $sumMerchantRating = $merchantRating->sum('rating');
        $data['countMerchantRating'] = $merchantRating->count();
        $data['merchantRating'] =   $merchantRating->count() > 0 ?
                                        floor($sumMerchantRating) / $merchantRating->count()
                                    : 0;
        return $data;
    }

}