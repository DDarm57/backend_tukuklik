<?php

namespace App\Http\Controllers\Marketing;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\FlashDeal;
use App\Models\FlashDealProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FlashDealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $flashDeal = FlashDeal::withCount('flashDealProducts')->get();
        return view('dashboard.marketing.flash-deals', compact('flashDeal'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = Product::where('status', 1)->get();
        return view('dashboard.marketing.create.flash-deals', compact('product'));
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
            'file'          => 'required|mimes:jpg,jpeg,png',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
            'product.*'     => 'required',
            'discount.*'    => 'required|numeric',
            'discount_type.*'=> 'required'
        ]);

        DB::beginTransaction();

        try {
            $flashDeal = FlashDeal::create([
                'banner'        => $request->file('file')->store('flash-deals', 'public'),
                'start_date'    => $request->start_date,
                'end_date'      => $request->end_date
            ]);
    
            $input = $request->all();
            foreach($input['product'] as $key => $product) {
                FlashDealProduct::create([
                    'flash_deal_id' => $flashDeal->id,
                    'product_id'    => $product,
                    'discount'      => $input['discount'][$key],
                    'discount_type' => $input['discount_type'][$key]
                ]);
            }

            DB::commit();

            return redirect()->route('flash-deals.index')->with('success', 'Flash deal berhasil ditambahkan');

        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
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
        $flashDeal = FlashDeal::where('id', $id)->first();
        $product = Product::where('status', 1)->doesntHave('flashDealProducts')->get();
        return view('dashboard.marketing.edit.flash-deals', compact('flashDeal', 'product'));
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
            'file'          => 'nullable|mimes:jpg,jpeg,png',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
            'product.*'     => 'required',
            'discount.*'    => 'required|numeric',
            'discount_type.*'=> 'required'
        ]);

        DB::beginTransaction();

        try {

            $input = Helpers::requestExcept($request);
            $flashDeal = FlashDeal::where('id', $id);
            $rowFlash = $flashDeal->first();
            $banner = $rowFlash->banner;
            if($request->file('file')) {
                Storage::disk('public')->delete($rowFlash->banner);
                $banner = $request->file('file')->store('flash-deals', 'public');
            }

            $flashDeal = $rowFlash->fill([
                'banner'        => $banner,
                'start_date'    => $input['start_date'],
                'end_date'      => $input['end_date']
            ])->save();
    
            foreach($input['product'] as $key => $product) {
                FlashDealProduct::updateOrCreate([
                    'product_id'    => $product,
                    'flash_deal_id' => $id,
                ],[
                    'flash_deal_id' => $id,
                    'product_id'    => $product,
                    'discount'      => $input['discount'][$key],
                    'discount_type' => $input['discount_type'][$key]
                ]);
            }

            foreach($rowFlash->flashDealProducts as $prod) {
                if(!in_array($prod->product_id, $input['product'])){
                    $rowFlash->flashDealProducts->where('id', $prod->id)->delete();
                }
            }
 
            DB::commit();

            return redirect()->route('flash-deals.index')->with('success', 'Flash deal berhasil Diubah');

        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $flashDeal = FlashDeal::where('id', $id);
        $rows = $flashDeal->first();
        Storage::disk('public')->delete($rows->banner);
        $rows->flashDealProducts()->delete();
        $flashDeal->delete();
        return true;
    }
}
