<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Http\Requests\CustomerRequest;
use App\Models\City;
use App\Models\CustomerAddresses;
use App\Models\District;
use App\Models\Province;
use App\Models\Subdistrict;
use App\Models\User;
use App\Repositories\QuotationRepository;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $user = UserRepository::customerOnly();
            return DataTables::of($user)
            ->addIndexColumn()
            ->make(true);
        }
        return view('dashboard.user.customer');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.user.create.customer');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        UserService::created($request);
        return redirect(url('dashboard/customer'))->with('success', 'Customer berhasil ditambahkan');
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
        $customer = UserRepository::customerOnly($id);
        return view('dashboard.user.edit.customer', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, $id)
    {
        UserService::updated($request, $id);
        return redirect(url('dashboard/customer'))->with('success', 'Customer berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        UserService::deleted($id);
        return response()->json(['message' => 'Customer has been deleted']);
    }

    public function listAddress()
    {
        $address = UserRepository::customerAddress(Auth::user()->id);
        $collect = Collection::make([]);
        foreach($address->get() as $addr) {
            $addr->full_addr = Helpers::formatAddress($addr);
            $collect->push($addr);
        }
        return response()->json([
            'status'    => 'success',
            'data'      => $collect
        ]);
    }

    public function addressById($id)
    {
        $data = UserRepository::customerAddress(Auth::user()->id, null, $id)->first();
        return response()->json([
            'status'    => 'success',
            'data'      => $data,
            'additional' => [
                'province'      => Province::query()->get(),
                'city'          => City::where('prov_id', $data->shipping_province_id)->get(),
                'district'      => District::where('city_id', $data->shipping_city_id)->get(),
                'subdistrict'   => Subdistrict::where('dis_id', $data->shipping_district_id)->get()
            ]
        ]);
    }

    public function deleteAddress($id)
    {
        CustomerAddresses::where('id', $id)->delete();
        Session::flash('success', 'Alamat berhasil dihapus');
        return response()->json(['status' => 'success']);
    }

    public function saveAddress(Request $request)
    {
        $this->validate($request, [
            'province'  => 'required',
            'city'      => 'required',
            'district'  => 'required',
            'subdistrict' => 'required',
            'address_name'   => 'required|max:255',
            'postcode'  => 'required|numeric|max_digits:7',
            'address'   => 'required|max:255'
        ]);

        DB::beginTransaction();
        try { 
            $checkAddress = CustomerAddresses::where('user_id', Auth::user()->id);
            $isDefault = $checkAddress->count() == 0 ? 1 : 0;

            $makeAddress = CustomerAddresses::create([
                'address_name'              => $request->address_name,
                'full_address'              => $request->address,
                'shipping_province_id'      => $request->province,
                'shipping_city_id'          => $request->city,
                'shipping_district_id'      => $request->district,
                'shipping_subdistrict_id'   => $request->subdistrict,
                'shipping_postcode'         => $request->postcode,
                'is_default'                => $isDefault,
                'user_id'                   => Auth::user()->id
            ]);

            DB::commit();

            $customerAddress = UserRepository::customerAddress(Auth::user()->id, null, $makeAddress->id);
            $formatAddress = Helpers::formatAddress($customerAddress->first());
            $makeAddress->full_address = $formatAddress;

            if($request->from_frontend == true){
                Session::flash('success', 'Alamat berhasil ditambahkan');
            }

            return response()->json([
                'status'    => 'success',
                'data'      => $makeAddress
            ]);

        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateAddress(Request $request, $id)
    {
        $this->validate($request, [
            'province'  => 'required',
            'city'      => 'required',
            'district'  => 'required',
            'subdistrict' => 'required',
            'address_name'   => 'required|max:255',
            'postcode'  => 'required|numeric|max_digits:7',
            'address'   => 'required|max:255'
        ]);

        DB::beginTransaction();
        try { 

            CustomerAddresses::where('id', $id)
            ->update([
                'address_name'              => $request->address_name,
                'full_address'              => $request->address,
                'shipping_province_id'      => $request->province,
                'shipping_city_id'          => $request->city,
                'shipping_district_id'      => $request->district,
                'shipping_subdistrict_id'   => $request->subdistrict,
                'shipping_postcode'         => $request->postcode,
                'user_id'                   => Auth::user()->id
            ]);

            DB::commit();

            if($request->from_frontend == true){
                Session::flash('success', 'Alamat berhasil diubah');
            }

            return response()->json([
                'status'    => 'success',
            ]);

        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
