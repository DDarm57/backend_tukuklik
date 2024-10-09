<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralSettingController extends Controller
{
    public function showHomepageSEO()
    {
        return view('dashboard.settings.homepage-seo');
    }

    public function showCompanyInformation()
    {
        return view('dashboard.settings.company-information');
    }

    public function showGeneralSetting()
    {
        return view('dashboard.settings.general-setting');
    }

    public function save(Request $request)
    {
        $this->validate($request, [
            'meta_title'        => 'nullable|max:255',
            'meta_keywords'     => 'nullable|max:255',
            'meta_description'  => 'nullable|max:255',
            'logo'              => 'nullable|max:2048|mimes:jpg,jpeg,png',
            'favicon'           => 'nullable|max:2048|mimes:ico,jpg,jpeg,png,gif',
            'facebook_url'      => 'nullable|url',
            'instagram_url'     => 'nullable|url',
            'twitter_url'       => 'nullable|url',
            'expired_rfq'       => 'nullable|numeric|gt:0',
            'expired_quotation' => 'nullable|numeric|gt:0',
            'expired_po'        => 'nullable|numeric|gt:0'
        ]);

        $input = Helpers::requestExcept($request);
        
        if ($request->file('logo')) {
            $input['logo'] = $request->file('logo')->store('settings', 'public');
        }

        if ($request->file('favicon')) {
            $input['favicon'] = $request->file('favicon')->store('settings','public');
        }

        $save = DB::table('general_settings')->update($input);
        return redirect()->back()->with('success', 'Pengaturan Berhasil Disimpan');
    }
}
