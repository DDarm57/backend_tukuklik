<?php

namespace App\Http\Controllers\Marketing;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Quotation;
use Carbon\Carbon;
use ErrorException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupon = Coupon::all();
        return view('dashboard.marketing.coupon', compact('coupon'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.marketing.create.coupon');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'             => 'required|max:100',
            'coupon_code'       => 'required|max:50',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after:start_date',
            'discount'          => 'required|numeric',
            'discount_type'     => 'required',
            'minimum_shopping'  => 'required|numeric',
            'maximum_discount'  => 'required|numeric',
        ]);

        $input = Helpers::requestExcept($request);
        Coupon::create($input);
        return redirect()->route('coupon.index')->with('success', 'Kupon diskon berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $coupon = Coupon::where('id', $id)->first();
        return view('dashboard.marketing.edit.coupon', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title'             => 'required|max:100',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date',
            'discount'          => 'required|numeric',
            'discount_type'     => 'required',
            'minimum_shopping'  => 'required|numeric',
            'maximum_discount'  => 'required|numeric',
        ]);

        $input = Helpers::requestExcept($request);
        Coupon::where('id', $id)->update($input);
        return redirect()->route('coupon.index')->with('success', 'Kupon diskon berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Coupon::where('id', $id)->delete();
        return true;
    }

    public function applyCoupon(Request $request)
    {
        try  {
            DB::beginTransaction();

            $carts = Cart::where('user_id', Auth::user()->id);
            $coupon = Coupon::where('coupon_code', $request->coupon_code)->first();
            $couponUses = DB::table('coupon_uses')
                            ->where('coupon_code', $request->coupon_code)
                            ->where('user_id', $request->user_id);
            $subTotal = $carts->sum('subtotal');

            if(
                $coupon->minimum_shopping > 0 && 
                $subTotal < $coupon->minimum_shopping
            )
            {
                throw new ErrorException('Gagal Klaim Diskon, Minimal Belanja Rp. '. number_format($coupon->minimum_shopping,0,'.','.'), 402);
            }

            if(
                $coupon->is_multiple_buy == false 
                && 
                $couponUses->count() > 0
            ) {
                throw new ErrorException('Gagal Klaim Diskon, Anda Sudah Pernah Klaim Kupon Ini', 402);
            }

            $discountAmount =   $coupon->discount_type == "Persentase" 
                                ?
                                $subTotal * ($coupon->discount/100) 
                                :
                                $coupon->discount;

            if($discountAmount > $coupon->maximum_discount) {
                $discountAmount = $coupon->maximum_discount;
            } 

            DB::table('coupon_uses')->insert([
                'user_id'           => Auth::user()->id,
                'coupon_code'       => $request->coupon_code,
                'rfq_number'        => $request->rfq_number,
                'discount_amount'   => $discountAmount
            ]);

            DB::commit();

            return $discountAmount;

        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
