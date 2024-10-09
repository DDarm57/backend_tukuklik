<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Http\Requests\MerchantRequest;
use App\Models\City;
use App\Models\District;
use App\Models\Merchant;
use App\Models\MerchantAddress;
use App\Models\Product;
use App\Models\Province;
use App\Models\Subdistrict;
use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $merchant = Merchant::all();
        return view('dashboard.merchant.merchant', compact('merchant'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $province = Province::all();
        $user = User::role('Seller')->doesntHave('merchant')->get();
        return view('dashboard.merchant.merchant-create', compact('province', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MerchantRequest $request)
    {        
        $input = Helpers::requestExcept($request);

        $input['photo'] = null;
        
        if($request->file('photo')){
            $path = $request->file('photo')->store('merchants', 'public');
            $input['photo'] = $path;
        }

        DB::beginTransaction();
        try {
            $merchant = Merchant::create([
                'name'      => $input['name'],
                'user_pic'  => $input['user_pic'],
                'phone'     => $input['phone'],
                'npwp'      => $input['npwp'],
                'is_pkp'    => $input['is_pkp'],
                'photo'     => $input['photo'],
                'status'    => $input['status']
            ]);
    
            MerchantAddress::create([
                'address_name'  => $input['address_name'],
                'full_address'  => $input['address'],
                'province_id'   => $input['province'],
                'city_id'       => $input['city'],
                'district_id'   => $input['district'],
                'subdistrict_id'=> $input['subdistrict'],
                'postcode'      => $input['postcode'],
                'is_default'    => 1,
                'merchant_id'   => $merchant->id
            ]);
    
            DB::commit();
            Session::flash('success', 'Penjual berhasil ditambahkan');
            return response()->json(['status' => 'success']);

        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
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
        $merchant = Merchant::where('id', $id)->first();
        $province = Province::all();
        $city = City::where('prov_id', $merchant->address->province_id)->get();
        $district = District::where('city_id', $merchant->address->city_id)->get();
        $subdistrict = Subdistrict::where('dis_id', $merchant->address->district_id)->get();
        $user = User::role('Seller')->get();
        return view('dashboard.merchant.merchant-edit', compact('merchant', 'province', 'city', 'district', 'subdistrict','user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MerchantRequest $request, $id)
    {

        DB::beginTransaction();
        try {
            $input = Helpers::requestExcept($request);

            $input['photo'] = null;
            $merchant = Merchant::where('id', $id);
            
            if($request->file('photo')){
                $merchant->first()->photo ? Storage::disk('public')->delete($merchant->first()->photo) : null;
                $path = $request->file('photo')->store('merchants', 'public');
                $input['photo'] = $path;
            }

            Merchant::where('id', $id)->update([
                'name'      => $input['name'],
                'user_pic'  => $input['user_pic'],
                'phone'     => $input['phone'],
                'npwp'      => $input['npwp'],
                'is_pkp'    => $input['is_pkp'],
                'photo'     => $input['photo'],
                'status'    => $input['status']
            ]);

            MerchantAddress::where('merchant_id', $id)->update([
                'address_name'  => $input['address_name'],
                'full_address'  => $input['address'],
                'province_id'   => $input['province'],
                'city_id'       => $input['city'],
                'district_id'   => $input['district'],
                'subdistrict_id'=> $input['subdistrict'],
                'postcode'      => $input['postcode'],
                'is_default'    => 1,
            ]);

            DB::commit();
            Session::flash('success', 'Penjual berhasil diubah');
            return response()->json(['status' => 'success']);

        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::where('merchant_id', $id);
        if($product->count() > 0){
            return response()->json([
                'status'    => 'error',
                'message'   => 'Penjual tidak bisa dihapus karena sudah mempunyai produk'
            ], 400);
        }

        MerchantAddress::where('merchant_id', $id)->delete();
        Merchant::where('id', $id)->delete();
        return response()->json(['status' => 'success']);
    }
}
