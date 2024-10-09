<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $organization = Organization::with('parent')->orderByDesc('id')->get();
            return DataTables::of($organization)
            ->addIndexColumn()
            ->make(true);
        }
        return view('dashboard.user.organization');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.user.create.organization');
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
            'org_name'          => 'required|min:5|max:50|string',
            'org_type'          => 'required',
            'parent_org_id'     => 'nullable'   
        ]);
        $check = Organization::where('org_name', $request->name)->count();
        if($check > 0){
            return redirect()->back()->withInput()->withErrors(['org_name' => 'The organization name already in our records']);
        }
        Organization::create($request->except('_token'));
        return redirect(url('dashboard/organization'))->with('success', 'Organization has been created');
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
        $org = Organization::find($id);
        return view('dashboard.user.edit.organization', compact('org'));
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
            'org_name'          => 'required|min:5|max:50|string',
            'org_type'          => 'required',
            'parent_org_id'     => 'nullable'   
        ]);
        $check = Organization::where('org_name', $request->name)->where('id', '!=', $id)->count();
        if($check > 0){
            return redirect()->back()->withInput()->withErrors(['org_name' => 'The organization name already in our records']);
        }
        Organization::where('id', $id)->update($request->except('_token','_method'));
        return redirect(url('dashboard/organization'))->with('success', 'Organization has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            Organization::where('id', $id)->delete();
            DB::commit();
            return response()->json(['message' => 'Organization has been deleted']);
        } catch(Exception $e) {
            DB::rollBack();
            throw($e->getMessage());
        }
    }

    public function getOrgByType($level) 
    {
        $org = Organization::where('org_type', '!=', $level)->get();
        return OrganizationResource::collection($org);
    }
}
