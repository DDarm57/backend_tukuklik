<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ForgotPasswordController extends Controller
{
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

            return response()->json([
                'success'   => true,
                'message'   => 'Link Verifikasi Password Sudah Kami Kirim Ke Email Anda',
            ]);

        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'password'  => 'required|confirmed|min:6',
            'token'      => 'required'
        ]);

        $token = $request->token;

        $forgot = DB::table('forgot_passwords')
        ->join('users', 'users.id', '=', 'forgot_passwords.user_id')
        ->where('is_reset', 'T')
        ->where('token', $token)
        ->whereRaw("valid_until >= DATE_FORMAT(now(),'%Y-%m-%d %H:%i:%s') ");
        $isReset = $forgot->count() > 0 ? 'T' : 'Y';

        if($isReset == "Y"){
            return response()->json([
                'success'   => false,
                'message'   => 'Mohon maaf, token tidak valid atau sudah kadaluwarsa.'
            ]);
        }

        $this->validate($request, [
            'password'  => 'required|confirmed|min:6'
        ]);
        $arr['password'] = Hash::make($request->password);
        UserRepository::update(auth()->user()->id, $arr);
        return response()->json([
            'success'   => true,
            'message'   => 'Reset Password Berhasil, Silahkan Coba Login Kembali'
        ]);
    }
}
