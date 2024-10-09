<?php 

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Tax;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Support\Facades\Auth;

class CartService {

    public static function addCart($request)
    {
        $qty = $request->qty;
        $variant = $request->input('variant') ?? [];
        $productId = $request->product_id;
        $product = Product::where('id', $productId)->first();
        if($qty == 0 || $qty < $product->minimum_order_qty) {
            throw new Exception('Minimal Quantity Adalah '. $product->minimum_order_qty);
        }
        if($product->max_order_qty > 0 && $qty > $product->max_order_qty) {
            throw new Exception('Max Quantity Adalah '. $product->max_order_qty);
        }

        $variant = ProductRepository::getProductDetailByVariant($variant, $productId);
        $priceAfterDisc = ProductRepository::priceAfterDiscount($productId, $variant->product_sku_id, $qty);
        
        self::confirmStock($variant->product_sku_id, $request->qty);

        $subTotal = $priceAfterDisc * $qty;
        $taxAmount = 0;

        if($product->merchant->is_pkp == "Y"){
            $tax = Tax::where('name', 'LIKE', '%PPN%')->first();
            $percentage = $tax->tax_percentage ?? 0;
            $taxAmount = $subTotal * $percentage;
        }
        
        $create = Cart::updateOrCreate([
            'product_sku_id'    => $variant->product_sku_id,
            'user_id'           => Auth::user()->id
        ], [
            'user_id'           => Auth::user()->id,
            'product_sku_id'    => $variant->product_sku_id,
            'qty'               => $qty,
            'base_price'        => $priceAfterDisc,
            'subtotal'          => $subTotal,
            'tax_amount'        => $taxAmount,
            'total_price'       => $subTotal + $taxAmount
        ]);

        return $create;
    }

    public static function adjustQty($payload, $id)
    {
        $cart = Cart::where('id', $id)->first();
        
        self::confirmStock($cart->productSku->id, $payload->qty);
        
        if($payload->qty < $cart->productSku->product->minimum_order_qty){
            throw new Exception("Minimum Order Adalah ". $cart->productSku->product->minimum_order_qty. " ". $cart->productSku->product->unit_type->name);
        }

        if(
            $payload->qty > $cart->productSku->product->max_order_qty
            &&
            $cart->productSku->product->max_order_qty > 0
        ) {
            throw new Exception("Maximum Order Adalah ". $cart->productSku->product->max_order_qty);
        }

        $taxPercentage = $cart->tax_amount / $cart->subtotal;
        $subTotal = $cart->base_price * $payload->qty;
        $taxAmount = ($cart->base_price * $payload->qty) * $taxPercentage;

        $cart->fill([
            'qty'       => $payload->qty,
            'subtotal'  => $subTotal,
            'tax_amount'=> $taxAmount,
            'total_price'=> $subTotal + $taxAmount
        ])->save();

        return $cart;
    }

    private static function confirmStock($skuId, $qty)
    {
        $stock = ProductRepository::getStockBySKU($skuId);
        if($stock < $qty) {
            throw new Exception("Mohon maaf, qty yang dipilih melebihi stok yang tersedia saat ini");
        }
    }

}