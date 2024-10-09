<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\Quotation;
use App\Models\Wishlist;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return view('dashboard.profile', compact('user'));
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
        //
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
            'name'              => 'required|min:3|max:255',
            'email'             => ['required','email',Rule::unique('users')->ignore($id)],
            'phone_number'      => 'required|numeric|max_digits:20',
            'date_of_birth'     => 'required|date',
            'old_password'      => 'required_with:password',
            'password'          => 'nullable|confirmed',
        ]);

        $oldPassword = $request->old_password;
        $check = Hash::check($oldPassword, auth()->user()->password);
        if(!$check && $oldPassword != null) {
            return redirect()->back()->withErrors(['old_password' => 'Password lama yang diinput tidak valid']);
        }

        UserService::updated($request, Auth::user()->id);
        return redirect()->back()->with('success', 'Profil berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function showMyProfile()
    {
        $data['notification'] = Auth::user()->notifications()->paginate(5)->appends(request()->query());
        $rfq = Quotation::query()
                ->where('number','LIKE', '%RFQ%')
                ->where('user_id', Auth::user()->id);
        $trans = $rfq;
        
        switch(request()->get('type')) {
            case 'rfq': 
                $trans = $rfq;
            break;
            case 'quote' :
                $trans = Quotation::query()
                        ->where('number','LIKE', '%QN%')
                        ->where('user_id', Auth::user()->id);
            break;
            case 'order' :
                $trans = PurchaseOrder::query()
                         ->where('user_id', Auth::user()->id);
            break;
            case 'invoice' :
                $trans = Invoice::query()
                         ->whereHas('order', function($q) {
                            $q->where('user_id', Auth::user()->id);
                         });
            break;
        }

        $data['transaction'] = $trans;
        $data['countRfq'] = auth()->user()
                            ->quotation()
                            ->where('number', 'LIKE', '%RFQ%')
                            ->count();

        $data['countQuote'] = auth()->user()
                            ->quotation()
                            ->where('number', 'LIKE', '%QN%')
                            ->count();

        $data['countOrder'] = auth()->user()
                              ->order()
                              ->count();
        
        $data['countInvoice'] = Invoice::query()
                                ->whereHas('order', function($q) {
                                    $q->where('user_id', auth()->user()->id);
                                })
                                ->count();
        $data['wishlist'] = Wishlist::query()
                            ->where('user_id', Auth::user()->id)
                            ->latest()
                            ->paginate(10);

        return view('profile', $data);
    }
}
