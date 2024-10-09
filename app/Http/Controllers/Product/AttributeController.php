<?php

namespace App\Http\Controllers\Product;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValues;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $query = Attribute::with('attributeValues')->get();
            return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('values', function($query) {
                $val = [];
                foreach($query->attributeValues as $values) {
                    array_push($val, $values->value);
                }
                return implode(', ', $val);
            })->addColumn('status', function($query) {
                return $query->status == 1 ? 'Actived' : 'Deactived';
            })
            ->make();
        }
        return view('dashboard.product.attribute');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.product.create.attribute');
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
            'name'          => 'required',
            'description'   => 'required|max:50',
            'values.*'      => 'required|max:255',
            'status'        => 'required'
        ]);
        DB::beginTransaction();
        try {
            $input = Helpers::requestExcept($request, ['values']);
            $attr = Attribute::create($input);
            foreach($request->values as $values) {
                $attr->attributeValues()->create([
                    'value'         => $values,
                    'attribute_id'  => $attr->id
                ]);
            }
            DB::commit();
            return redirect()->route('attribute.index')->with('success', 'Varian attribute berhasil dibuat');
        } catch(Exception $e){
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
        $attr = Attribute::where('id', $id)->first();
        return view('dashboard.product.edit.attribute', compact('attr'));
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
            'name'          => 'required',
            'description'   => 'required|max:50',
            'values.*'      => 'required|max:255',
            'status'        => 'required'
        ]);
        try {
            DB::beginTransaction();
            $attr = Attribute::where('id', $id);
            $attr->update(Helpers::requestExcept($request,['values','values_id']));
            $arr = [];
            $getData = $attr->first();
            $valueId = $request->input('values_id');
            foreach($request->values as $index => $value) {
                $getData->attributeValues()->updateOrCreate(
                    [
                        'id'    => $valueId[$index]
                    ],
                    [
                        'value' => $value
                    ]
                );
            }
            DB::commit();
            return redirect()->route('attribute.index')->with('success', 'Varian attribute berhasil Diubah');
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
        $attr = Attribute::where('id', $id);
        $attr->first()->attributeValues()->delete();
        $attr->delete();
        return response()->json(['message' => 'Varian berhasil dihapus']);
    }

    public function getAttribute($exceptId = null)
    {
        $attr = Attribute::query()->when($exceptId, function($query) use ($exceptId) {
            $query->where('id', '!=', $exceptId);
        })->get();
        return response()->json($attr);
    }

    public function getValuesByAttribute($id)
    {
        $attr = AttributeValues::with('attribute')->where('attribute_id', $id);
        return response()->json($attr->get());
    }
}
