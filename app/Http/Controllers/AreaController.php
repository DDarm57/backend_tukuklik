<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\Subdistrict;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function province()
    {
        return response()->json([
            'status'    => 'success', 
            'data'      => Province::all()
        ]);
    }

    public function getCitiesByProvince($provinceId)
    {
        return response()->json([
            'status'    => 'success',
            'data'      => City::where('prov_id', $provinceId)->get(),
        ]);
    }

    public function getDistrictsByCity($cityId)
    {
        return response()->json([
            'status'    => 'success',
            'data'      => District::where('city_id', $cityId)->get()
        ]);
    }

    public function getSubdistrictsByDistrict($districtId)
    {
        return response()->json([
            'status'    => 'success',
            'data'      => Subdistrict::where('dis_id', $districtId)->get()
        ]);
    }
}
