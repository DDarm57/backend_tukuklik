<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
    public function listAddress()
    {
        $address = UserRepository::customerAddress(Auth::user()->id);
        $collect = Collection::make([]);
        foreach($address->get() as $addr) {
            $addr->full_addr = Helpers::formatAddress($addr);
            $collect->push($addr);
        }
        return response()->json([
            'success'       => true,
            'data'          => $collect
        ]);
    }

    public function addressById($id)
    {
        try {
            $data = UserRepository::customerAddress(Auth::user()->id, null, $id)->firstOrFail();
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
        } catch(Exception $e) {
            return response()->json([
                'success'    => false, 
                'message'   => $e->getMessage()
            ], 400);
        }
    }

    public function deleteAddress($id)
    {
        try {
            CustomerAddresses::where('id', $id)->firstOrFail()->delete();
            return response()->json([
                'success'   => true,
                'message'   => 'Alamat berhasil dihapus'
            ]);
        } catch(Exception $e) {
            return response()->json([
                'success'    => false, 
                'message'   => $e->getMessage()
            ], 400);
        }
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

            return response()->json([
                'success'       => true,
                'data'          => $makeAddress
            ]);

        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'success'    => false, 
                'message'   => $e->getMessage()
            ], 400);
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
            ->firstOrFail()
            ->fill([
                'address_name'              => $request->address_name,
                'full_address'              => $request->address,
                'shipping_province_id'      => $request->province,
                'shipping_city_id'          => $request->city,
                'shipping_district_id'      => $request->district,
                'shipping_subdistrict_id'   => $request->subdistrict,
                'shipping_postcode'         => $request->postcode,
                'user_id'                   => Auth::user()->id
            ])
            ->save();

            DB::commit();

            if($request->from_frontend == true){
                Session::flash('success', 'Alamat berhasil diubah');
            }

            return response()->json([
                'success'    => true,
                'message'   => 'Alamat berhasil diubah'
            ]);

        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'success'    => false, 
                'message'   => $e->getMessage()
            ], 400);
        }
    }
}
