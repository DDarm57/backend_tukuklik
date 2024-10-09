<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Repositories\MerchantRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    public function showMerchant($id)
    {
        $merchant = Merchant::query()
                    ->where('id', $id)
                    ->withCount('products')
                    ->firstOrFail();
        
        $merchantRating = MerchantRepository::rating($id);
        $data['countMerchantRating'] = $merchantRating['countMerchantRating'];
        $data['merchantRating'] =  $merchantRating['merchantRating'];
        $data['merchant'] = $merchant;

        $merchantAddress = UserRepository::merchantAddress($id);
        $formatMerchantAddress = Helpers::formatAddress($merchantAddress->first());
        $data['merchantAddress'] = $formatMerchantAddress;

        return view('merchant-detail', $data);
    }
}
