<?php 

namespace App\Repositories;

use App\Helpers\Helpers;
use App\Models\Cart;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CartRepository {
    
    public static function showCart()
    {
        $cart = Cart::where('user_id', Auth::user()->id);

        $categories = Collection::make([]);

        $cart->get()->map(function($n) use($categories) {
            $n->productSku->product->categories->map(function($q) use($categories) {
                $q->depth_level == 1 ? $categories->push($q->id) : '';
            });
        });
        
        $data['carts'] = $cart->get();
        $data['productReferences'] = ProductRepository::productReferences($categories);
        $data['newProductVisitors'] = ProductRepository::lastVisit();
        $data['subTotal'] = $cart->sum('subtotal');
        $data['tax'] = $cart->sum('tax_amount');
        $data['incomeTax'] = 0;
        $data['shippingFeeEstimation'] = Helpers::getShippingFeeEstimation();
        $data['total'] = $cart->sum('total_price');
        return $data;
    }

}