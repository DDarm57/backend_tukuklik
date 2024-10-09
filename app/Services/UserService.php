<?php 

namespace App\Services;

use App\Helpers\Helpers;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService {

    public static function deleted($id)
    {
        try {
            DB::beginTransaction();
            User::where('id', $id)->first()->delete();
            DB::commit();
            return true;
        } catch(Exception $e){
            DB::rollBack();
            throw($e->getMessage());
        }
    }

    public static function created($payload)
    {
        DB::beginTransaction();
        try {
            $input = Helpers::requestExcept($payload);
            $path = $payload->file('photo')->store('avatars', 'public');
            $input['password'] = Hash::make($payload->password);
            $input['photo'] = $path;
            $user = User::create($input);
            $user->assignRole($payload->role ?? Helpers::roleForCustomer());
            DB::commit();
        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public static function updated($payload, $id)
    {
        DB::beginTransaction();
        try {
            $input = Helpers::requestExcept($payload, ['old_password']);
            $input['password'] = Hash::make($payload->password);
            if($payload->password == '') {
                unset($input['password']);
            }
            $user = User::where('id', $id);
            if($payload->file('photo')){
                $user->first()->photo ? Storage::disk('public')->delete($user->first()->photo) : null;
                $path = $payload->file('photo')->store('avatars', 'public');
                $input['photo'] = $path;
            }
            $user->update($input);
            $user->first()->syncRoles($payload->role ?? $user->first()->getRoleNames()[0] ?? Helpers::roleForCustomer());
            DB::commit();
        } catch(Exception $e){
            DB::rollBack();
            throw $e;
        }
    }
    
}