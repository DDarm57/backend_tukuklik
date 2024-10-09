<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GeneralSettingController extends Controller
{
    public function showGeneralSetting()
    {
        $data = DB::table('general_settings')->first();

        $data->logo = url(Storage::url($data->logo));
        $data->favicon = url(Storage::url($data->favicon));

        return response()->json([
            'success'   => true,
            'data'      => $data
        ]);
    }
}
