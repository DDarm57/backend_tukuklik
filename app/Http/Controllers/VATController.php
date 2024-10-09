<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Tax;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class VATController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $query = Tax::all();
            return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('percentage', function($query) {
                        return $query->tax_percentage . " %";
                    })
                    ->make(true);
        }
        return view('dashboard.settings.vat');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'name'              => 'required',
            'tax_percentage'    => 'numeric|required',
            'is_active'         => 'required'
        ]);

        Tax::create(Helpers::requestExcept($request));
        return redirect()->back()->with('success', 'Pajak Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if($request->ajax()) {
            return response()->json(Tax::where('id', $id)->first());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            'name'              => 'required',
            'tax_percentage'    => 'numeric|required',
            'is_active'         => 'required'
        ]);

        Tax::where('id', $id)->update(Helpers::requestExcept($request));
        return redirect()->back()->with('success', 'Pajak Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tax = Tax::where('id' ,$id)->firstOrFail();
        $tax->delete();

        return response()->json(['status' => 'success']);
    }
}
