<?php

namespace App\Http\Controllers\Product;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\UnitType;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UnitTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $unit = UnitType::all();
            return DataTables::of($unit)
            ->addIndexColumn()
            ->addColumn('status', function($unit) {
                return $unit->status == 1 ? 'Actived' : 'Deactived';
            })
            ->make(true);
        }
        return view('dashboard.product.unit');
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
            'name'  => 'required|max:100',
            'status'=> 'required'
        ]);
        $input = Helpers::requestExcept($request);
        UnitType::create($input);
        return redirect()->route('unit.index')->with('success', 'Unit berhasil ditambahkan');
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
            return response()->json(UnitType::where('id', $id)->first());
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
            'name'  => 'required|max:100',
            'status'=> 'required'
        ]);
        $input = Helpers::requestExcept($request);
        UnitType::where('id', $id)->update($input);
        return redirect()->route('unit.index')->with('success', 'Unit berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        UnitType::where('id', $id)->delete();
        return response()->json(['message' => 'Unit berhasil dihapus']);
    }
}
