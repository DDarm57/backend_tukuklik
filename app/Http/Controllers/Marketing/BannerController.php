<?php

namespace App\Http\Controllers\Marketing;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $banner = Banner::all();
        return view('dashboard.marketing.banner', compact('banner'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.marketing.create.banner');
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
            'name'          => 'required|max:50',
            'file'          => 'required|mimes:jpg,png,jpeg|max:2048',
            'status'        => 'required',
            'position'      => 'required|numeric',
            'is_newtab'     => 'required'
        ]);

        $input = Helpers::requestExcept($request, ['file']);
        $path = $request->file('file')->store('banners','public');
        $input['slider_image'] = $path;
        Banner::create($input);
        return redirect()->route('banner.index')->with('success', 'Banner berhasil ditambahkan');
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
        $banner = Banner::where('id', $id)->first();
        return view('dashboard.marketing.edit.banner', compact('banner'));
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
            'name'          => 'required|max:50',
            'file'          => 'nullable|mimes:jpg,png,jpeg|max:2048',
            'status'        => 'required',
            'position'      => 'required|numeric',
            'is_newtab'     => 'required'
        ]);

        $input = Helpers::requestExcept($request, ['file']);
        $banner = Banner::where('id', $id);
        if($request->file('file')){
            Storage::disk('public')->delete($banner->first()->slider_image);
            $path = $request->file('file')->store('banners','public');
            $input['slider_image'] = $path;
        }
        $banner->update($input);
        return redirect()->route('banner.index')->with('success', 'Banner berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $banner = Banner::where('id', $id);
        Storage::disk('public')->delete($banner->first()->slider_image);
        $banner->delete();
        return true;
    }
}
