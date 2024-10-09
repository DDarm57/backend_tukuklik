<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffRequest;
use App\Models\Organization;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if($request->ajax()){
            $user = UserRepository::staffOnly();
            return DataTables::of($user)
            ->addIndexColumn()
            ->make(true);
        }
        return view('dashboard.user.staff');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organization = Organization::all();
        $role = Role::where('name', '!=' , 'Customer')->get();
        return view('dashboard.user.create.staff', compact('organization', 'role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StaffRequest $request)
    {
        UserService::created($request);
        return redirect(url('dashboard/staff'))->with('success', 'Staff berhasil ditambahkan');
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
        $staff = UserRepository::staffOnly($id);
        $organization = Organization::all();
        $role = Role::all();
        return view('dashboard.user.edit.staff', compact('staff', 'organization', 'role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StaffRequest $request, $id)
    {
        UserService::updated($request, $id);
        return redirect(url('dashboard/staff'))->with('success', 'Staff berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        UserService::deleted($id);
        return response()->json(['message' => 'Staff has been deleted']);
    }
}
