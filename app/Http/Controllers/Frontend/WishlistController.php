<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Tax;
use App\Models\Wishlist;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class WishlistController extends Controller
{
    public function index()
    {
        $data['wishlist'] = Wishlist::query()
                            ->where('user_id', Auth::user()->id)
                            ->latest()
                            ->paginate(10);

        return view('wishlist', $data);
    }

    public function addWishlist(Request $request)
    {
        $this->validate($request, [
            'product_id'    => 'required',
            'variant.*'       => 'required',
        ]);

        $variant = $request->input('variant') ?? [];
        $variant = ProductRepository::getProductDetailByVariant($variant, $request->product_id);
        Wishlist::create([
            'user_id'       => Auth::user()->id,
            'product_sku_id'=> $variant->product_sku_id
        ]);
        Session::flash('success', 'Produk berhasil dimasukan kedalam wishlist');
        return response()->json(['status' => 'success']);
    }

    public function deleteWishlist($id)
    {
        Wishlist::where('id', $id)->delete();
        Session::flash('success', 'Produk wishlist berhasil dihapus');
        return response()->json(['status' => 'success']);
    }

    public function addWishlistToCart(Request $request)
    {
        $id = $request->wishlist_id;
        $wishlist = Wishlist::where('id', $id)->first();
        if($wishlist->productSku->stock_status == "Out Of Stock"){
            throw new Exception("Gagal, stok tidak tersedia");
        }
        $stockSku = ProductRepository::getStockBySKU($wishlist->product_sku_id);
        if($stockSku >= $wishlist->productSku->product->minimum_order_qty) {
            $qty = $wishlist->productSku->product->minimum_order_qty;
        }else {
            $qty = 1;
        }
        $priceAfterDisc =   ProductRepository::priceAfterDiscount(
                                $wishlist->productSku->product->id, 
                                $wishlist->product_sku_id,
                                $qty
                            );

        $subTotal = $priceAfterDisc * $qty;
        $taxAmount = 0;

        if($wishlist->productSku->product->merchant->is_pkp == "Y"){
            $tax = Tax::where('name', 'LIKE', '%PPN%')->first();
            $percentage = $tax->tax_percentage ?? 0;
            $taxAmount = $subTotal * $percentage;
        }

        Cart::updateOrCreate([
            'product_sku_id'    => $wishlist->product_sku_id,
            'user_id'           => Auth::user()->id
        ], [
            'user_id'           => Auth::user()->id,
            'product_sku_id'    => $wishlist->product_sku_id,
            'qty'               => $qty,
            'base_price'        => $priceAfterDisc,
            'subtotal'          => $subTotal,
            'tax_amount'        => $taxAmount,
            'total_price'       => $subTotal + $taxAmount
        ]);
        
        $wishlist->delete();
    
        Session::flash('success', 'Produk berhasil ditambahkan kedalam keranjang');
        return response()->json(['status' => 'success']);
    }
}
