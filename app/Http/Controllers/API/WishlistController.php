<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Resources\WishlistResource;
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
        $query = Wishlist::query()
                ->with(['productSku'])
                ->where('user_id', Auth::user()->id)
                ->latest()
                ->paginate(10);

        return WishlistResource::collection($query);
    }

    public function addWishlist(Request $request)
    {
        $this->validate($request, [
            'product_id'    => 'required',
            'variant.*'       => 'required',
        ]);

        try {
            $variant = $request->input('variant') ?? [];
            $variant = ProductRepository::getProductDetailByVariant($variant, $request->product_id);
            $data = Wishlist::updateOrCreate([
                'user_id'       => Auth::user()->id,
                'product_sku_id'=> $variant->product_sku_id
            ]);
            return response()->json([
                'success'   => true,
                'message'   => 'Produk berhasil ditambah kedalam wishlist',
                'data'      => $data
            ]);
        } catch(Exception $e) {
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage()
            ], 400);
            DB::rollBack();
        }
    }

    public function deleteWishlist($id)
    {
       try {
            Wishlist::where('id', $id)->firstOrFail()->delete();
            return response()->json([
                'success'   => true,
                'message'   => 'Wishlist berhasil dihapus'
            ]);
       } catch(Exception $e) {
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage()
            ], 400);
            DB::rollBack();
        }
    }

    public function addWishlistToCart(Request $request)
    {
        $this->validate($request, [
            'wishlist_id'   => 'required|numeric'
        ]);

        try {
            $id = $request->wishlist_id;
            $wishlist = Wishlist::where('id', $id)->firstOrFail();
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
        
            return response()->json([
                'success'   => true,
                'message'   => 'Produk berhasil ditambahkan kedalam keranjang'
            ]);
        } catch(Exception $e) {
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage()
            ], 400);
            DB::rollBack();
        }
    }
}
