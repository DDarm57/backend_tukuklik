<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\Constant;
use App\Http\Controllers\Controller;
use App\Notifications\UserRegistration;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        
        $this->validate($request, [
            'name'      => 'required|min:3|max:50',
            'email'     => 'required|unique:users,email|email',
            'password'  => 'required|confirmed|min:6'
        ]);

        try {
            DB::beginTransaction();
            $data = $request->merge(['is_actived' => 'T', 'organization' => null]);
            $arr = $data->all();
            $arr['password'] = Hash::make($request->password);
            $create = UserRepository::create($arr);
            $token = Str::random(100);
            $data = [
                'user_id'       => $create->id,
                'token'         => $token,
                'message'       => sprintf(Constant::NEW_CUSTOMER, $request->name),
                'url'           => url('dashboard/customer'),
                'user'          => $create,
                'valid_until'   => Carbon::now()->addDay()
            ];

            UserRepository::createVerify($data);
            Notification::send(UserRepository::staffOnly(), new UserRegistration($data));
            DB::commit();

            return response()->json([
                'success'   => true,
                'message'   => 'Registrasi Berhasil, Segera Cek Inbox Email Anda Untuk Melakukan Verifikasi'
            ]);

        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
