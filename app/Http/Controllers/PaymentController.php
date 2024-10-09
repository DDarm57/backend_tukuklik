<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paymentMethod = PaymentMethod::all();
        return view('dashboard.payment.payment-method', compact('paymentMethod'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.payment.create.payment-method');
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
            'payment_type'  =>  'required',
            'payment_name'  =>  'required',
            'branch_name'   =>  'required_if:payment_type,==,Bank Transfer',
            'account_number'=>  'required_if:payment_type,==,Bank Transfer',
            'account_holder'=>  'required_if:payment_type,==,Bank Transfer',
            'payment_service'=> 'required',
            'logo'          =>  'nullable|mimes:jpg,jpeg,png'
        ]);

        $input = Helpers::requestExcept($request);

        if($request->file('logo')){
            $input['logo'] = $request->file('logo')->store('logo-payment', 'public');
        }

        PaymentMethod::create($input);

        return redirect()->route('payment-method.index')->with('success', 'Metode Pembayaran Berhasil Ditambahkan');
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
        $paymentMethod = PaymentMethod::where('id', $id)->first();
        return view('dashboard.payment.edit.payment-method', compact('paymentMethod'));
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
            'payment_type'  =>  'required',
            'payment_name'  =>  'required',
            'branch_name'   =>  'required_if:payment_type,==,Bank Transfer',
            'account_number'=>  'required_if:payment_type,==,Bank Transfer',
            'account_holder'=>  'required_if:payment_type,==,Bank Transfer',
            'payment_service'=> 'required',
            'logo'          =>  'nullable|mimes:jpg,jpeg,png'
        ]);

        $input = Helpers::requestExcept($request);

        $paymentMethod = PaymentMethod::where('id', $id);
        if($request->file('logo')){
            if(Storage::disk('public')->exists($paymentMethod->first()->logo ?? 'x')){
                Storage::disk('public')->delete($paymentMethod->first()->logo);
            }
            $input['logo'] = $request->file('logo')->store('logo-payment', 'public');
        }

        PaymentMethod::where('id', $id)->update($input);

        return redirect()->route('payment-method.index')->with('success', 'Metode Pembayaran Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PaymentMethod::where('id', $id)->delete();
        return true;
    }
}
