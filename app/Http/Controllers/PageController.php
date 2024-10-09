<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = DB::table('pages')->get();
        return view('dashboard.page', compact('page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.page-create');
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
            'slug'      => ['required', Rule::unique('pages', 'slug')],
            'title'     => 'required|max:255',
            'content'   => 'required',
            'is_actived'=> 'required',
        ]);

        $input = Helpers::requestExcept($request);
        $input['created_at'] = Carbon::now();
        $input['updated_at'] = Carbon::now();
        DB::table('pages')->insert($input);
        return redirect()->route('frontend.index')->with('success', 'Halaman Berhasil Ditambahkan');
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
        $page = DB::table('pages')->where('id', $id)->first();
        return view('dashboard.page-edit', compact('page'));
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
            'slug'      => ['required', Rule::unique('pages', 'slug')->ignore($id) ],
            'title'     => 'required|max:255',
            'content'   => 'required',
            'is_actived'=> 'required',
        ]);

        $input = Helpers::requestExcept($request);
        $input['updated_at'] = Carbon::now();
        DB::table('pages')->where('id', $id)->update($input);
        return redirect()->route('frontend.index')->with('success', 'Halaman Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('pages')->where('id', $id)->delete();
        return response()->json(['status' => 'success']);
    }
}
