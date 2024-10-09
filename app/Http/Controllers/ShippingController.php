<?php

namespace App\Http\Controllers;

use App\Exports\ShippingFeeExport;
use App\Helpers\Helpers;
use App\Imports\ShippingFeeImport;
use App\Models\City;
use App\Models\ShippingFee;
use App\Models\ShippingMethod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Excel as CExcel;
use Maatwebsite\Excel\Facades\Excel;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shippingMethod = ShippingMethod::all();
        return view('dashboard.shipping.shipping-method', compact('shippingMethod'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.shipping.create.shipping-method');
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
            'method_name'   => 'required|max:100',
            'cost'          => 'required|numeric',
            'is_active'     => 'required'
        ]);

        $create = ShippingMethod::create(Helpers::requestExcept($request));

        $cities = City::all();

        foreach($cities as $city) {
            ShippingFee::create([
                'shipping_method_id'    => $create->id,
                'city_id'               => $city->city_id
            ]);
        }

        return redirect()->route('shipping-method.index')->with('success', 'Metode Pengiriman Berhasil Ditambahkan');
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
        $shippingMethod = ShippingMethod::where('id', $id)->first();
        return view('dashboard.shipping.edit.shipping-method', compact('shippingMethod'));
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
            'method_name'   => 'required|max:100',
            'cost'          => 'required|numeric',
            'is_active'     => 'required'
        ]);

        ShippingMethod::where('id', $id)->update(Helpers::requestExcept($request));

        return redirect()->route('shipping-method.index')->with('success', 'Metode Pengiriman Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ShippingMethod::where('id', $id)->delete();
        ShippingFee::where('shipping_method_id', $id)->delete();
        return true;
    }

    public function exportShippingList()
    {
        $fileName = now()->format("Y_m_d_his_") . "Tukuklik_Shipping_Fee";
        return Excel::download(new ShippingFeeExport(), $fileName . ".xlsx", CExcel::XLSX);
    }

    public function importShippingList(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:xlsx,xls|max:2048'
        ]);

        try {

            DB::beginTransaction();

            Excel::import(new ShippingFeeImport($request->shipping_method_id), $request->file('file'));

            DB::commit();

            Session::flash('success', 'Import ongkir berhasil dilakukan');
            return response()->json([
                'status'     => "success",
            ]);

        } catch(Exception $e) {

            DB::rollBack();

            return response()->json([
                'status'        => "error",
                'message'       => $e->getMessage()
            ], 400);
        }
    }

    public function getShippingFeeById($id)
    {
        return response()->json([
            'error'     => false,
            'data'      => ShippingFee::query()
                            ->with(['city', 'city.province'])
                            ->where('id', $id)
                            ->first()
        ]);
    }

    public function updateShippingFee(Request $request, $id)
    {
        $this->validate($request, [
            'fee'                   => 'required|numeric',
            'minimum_kg'            => 'required|numeric|gt:0',
            'shipping_estimation'   => 'required'   
        ]);

        try {
            
            DB::beginTransaction();

            ShippingFee::query()->where('id', $id)->update(
                Helpers::requestExcept($request)
            );

            DB::commit();

            Session::flash('success', 'Ongkos kirim berhasil diupdate');
            return response()->json([
                'status'    => 'success'
            ]);

        } catch(Exception $e) {
            return response()->json([
                'status'    => 'error',
                'message'   => $e->getMessage()
            ]);
        }
    }
}
