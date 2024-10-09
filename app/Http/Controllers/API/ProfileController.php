<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function myProfile(Request $request)
    {
        return new UserResource($request->user());
    }

    public function notifications()
    {
        return response()->json(auth()->user()->notifications()->paginate(5));
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'name'              => 'required|min:3|max:255',
            'email'             => ['required','email',Rule::unique('users')->ignore(auth()->user()->id)],
            'phone_number'      => 'required|numeric|max_digits:20',
            'date_of_birth'     => 'required|date',
            'old_password'      => 'required_with:password',
            'password'          => 'nullable|confirmed',
        ]);

        $oldPassword = $request->old_password;
        $check = Hash::check($oldPassword, auth()->user()->password);
        if(!$check && $oldPassword != null) {
            return response()->json([
                'success'   => false,
                'message'   => 'Password lama yang diinput tidak valid'
            ], 400);
        }

        UserService::updated($request, Auth::user()->id);
        return response()->json([
            'success'   => true,
            'message'   => 'Profil berhasil diubah'
        ]);
    }
}
