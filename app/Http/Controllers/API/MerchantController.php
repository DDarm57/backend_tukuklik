<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\MerchantResource;
use App\Models\Merchant;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    public function showMerchant()
    {
        return MerchantResource::collection(Merchant::all());
    }
    
    public function getMerchantById($id)
    {
        $merchant = Merchant::query()->where('id', $id)->first();
        return new MerchantResource($merchant);
    }
}
