<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function index()
    {
        return view('auth.forgot-password');
    }

    public function resetPasswordLink(Request $request)
    {
        $this->validate($request, [
            'email'     =>  [
                                'required', 'email',
                                Rule::exists('users', 'email')
                            ]
        ]);
        try {
            $user = UserRepository::findByEmail($request->email)->first();
            $token = Str::random(100);

            $data = [
                'user_id'       => $user->id,
                'token'         => $token,
                'valid_until'   => Carbon::now()->addDay(),
                'is_reset'      => 'T'
            ];
            DB::table('forgot_passwords')->insert($data);

            $data['user'] = $user;
            Mail::to($user->email)->queue(new ForgotPasswordMail($data));

            DB::commit();
            Session::flash('success', 'Link Verifikasi Password Sudah Kami Kirim Ke Email Anda');
            return redirect()->back();
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function showResetPassword($link)
    {
        $forgot = DB::table('forgot_passwords')
        ->join('users', 'users.id', '=', 'forgot_passwords.user_id')
        ->where('is_reset', 'T')
        ->where('token', $link)
        ->whereRaw("valid_until >= DATE_FORMAT(now(),'%Y-%m-%d %H:%i:%s') ");
        $isReset = $forgot->count() > 0 ? 'T' : 'Y';
        $user = $forgot->first();
        return view('auth.reset-password', compact('isReset', 'user'));
    }

    public function resetPassword(Request $request, $userId)
    {
        $this->validate($request, [
            'password'  => 'required|confirmed|min:6'
        ]);
        $arr['password'] = Hash::make($request->password);
        UserRepository::update($userId, $arr);
        return redirect(url('login'))->with('success', 'Reset Password Berhasil, Silahkan Coba Login Kembali');
    }
}
