<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request) 
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $credentials = $request->merge(['is_actived' => 'Y'])->only('email', 'password', 'is_actived');
        $attempt = Auth::attempt($credentials);
        if($attempt) {
            $request->session()->regenerate();
            if(Auth::user()->getRoleNames()[0] == Helpers::roleForCustomer()){
                return redirect(url(''));
            }   
            return redirect(url('dashboard'));
        }else {
            return redirect()
            ->back()
            ->withInput()
            ->withErrors(['email' => 'These credentials do not match our records']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
