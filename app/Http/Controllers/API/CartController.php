<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\ProductsResource;
use App\Http\Resources\Product\ProductVisitorResource;
use App\Models\Cart;
use App\Repositories\CartRepository;
use App\Services\CartService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function showCart()
    {
        $data = CartRepository::showCart();
        $data['newProductVisitors'] = ProductVisitorResource::collection($data['newProductVisitors']);
        return response()->json([
            'status'    => 'success',
            'data'      => $data
        ]);
    }

    public function deleteCart($id)
    {
        try {

            DB::beginTransaction();
            Cart::where('id', $id)->firstOrFail()->delete();
            DB::commit();

            return response()->json([
                'success'       => true,
                'message'       => 'Keranjang berhasil dihapus'
            ]);

        } catch(Exception $e) {
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage()
            ], 400);
            DB::rollBack();
        }
    }

    public function adjustQty(Request $request, $id)
    {
        $this->validate($request, [
            'qty'       => 'required|numeric|gt:0'
        ]);

        try {
            DB::beginTransaction();
            $cart = CartService::adjustQty($request, $id);
            DB::commit();

            return response()->json([
                'status'    => 'success',
                'data'      => $cart,
                'subtotal'  => auth()->user()->cart()->sum('subtotal'),
                'tax_amount'=> auth()->user()->cart()->sum('tax_amount'),
                'grand_total'=> auth()->user()->cart()->sum('total_price')
            ]);

        } catch(Exception $e) {
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage()
            ], 400);
            DB::rollBack();
        }
    }

    public function addCart(Request $request)
    {
        $this->validate($request, [
            'product_id'    => 'required',
            'variant.*'     => 'required',
            'qty'           => 'required'
        ]);

        try {
            DB::beginTransaction();
            $cart = CartService::addCart($request);
            DB::commit();
            return response()->json([
                'success'   => true,
                'data'      => $cart
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
