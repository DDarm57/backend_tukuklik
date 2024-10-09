<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function authenticate(Request $request) 
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        $credentials = $request->merge(['is_actived' => 'Y'])->only('email', 'password', 'is_actived');
        $attempt = Auth::attempt($credentials);
        if($attempt) {
            $token = Auth::user()->createToken('auth_token')->plainTextToken;
            return response()->json([
                'success'   => true,
                'data'      => new UserResource(Auth::user()),
                'message'   => 'Login Berhasil',
                'token'     => $token,
                'expired_at'=> Carbon::now()->addMinutes(Helpers::sanctumExpired())
            ]);
        }else {
            return response()->json([
                'success'   => false,
                'data'      => [],
                'message'   => 'Login gagal, email atau password tidak sesuai'
            ], 422);
        }
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'success'   => true,
            'message'   => 'Logout sukses'
        ]);
    }

    public function refresh(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json(['token' => $user->createToken($user->name)->plainTextToken]);
    }
}
