<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Tax;
use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;
use App\Services\CartService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Shetabit\Visitor\Models\Visit;

class CartController extends Controller
{
    public function showCart()
    {
        $data = CartRepository::showCart();
        return view('cart', $data);
    }

    public function delete($id)
    {
        try {

            DB::beginTransaction();
            Cart::where('id', $id)->firstOrFail()->delete();
            DB::commit();
            Session::flash('success', 'Keranjang berhasil dihapus');

            return response()->json([
                'status'    => 'success'
            ]);

        } catch(Exception $e) {
            throw $e;
            DB::rollBack();
        }
    }

    public function adjustQty(Request $request, $id)
    {
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
            throw $e;
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
            CartService::addCart($request);
            DB::commit();
            Session::flash('success', 'Produk berhasil ditambahkan kedalam keranjang');
            return response()->json(['status' => 'success']);

        } catch(Exception $e) {
            throw $e;
            DB::rollBack();
        }
    }
}
