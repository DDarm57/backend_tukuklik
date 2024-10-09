<?php 

namespace App\Repositories;

use App\Models\CustomerAddresses;
use App\Models\MerchantAddress;
use App\Models\User;
use App\Models\UserVerify;

class UserRepository {

    public static function findById($id)
    {
        return User::find($id);
    }

    public static function findAll()
    {
        return User::all();
    }

    public static function findByEmail($email)
    {
        return User::where('email', $email);
    }

    public static function create($data)
    {
        $user = new User;
        $user->email = $data['email'];
        $user->name = $data['name'];
        $user->password = $data['password'];
        $user->organization_id = $data['organization'];
        $user->is_actived = $data['is_actived'];
        $user->save();
        return $user;
    }

    public static function createVerify($data)
    {
        $verify = new UserVerify;
        $verify->user_id = $data['user_id'];
        $verify->token = $data['token'];
        $verify->valid_until = $data['valid_until'];
        $verify->is_used = "T";
        $verify->save();
    }

    public static function verify($token)
    {
        $verify = UserVerify::where('token', $token)
                ->where('is_used', 'T')
                ->whereRaw("valid_until >= DATE_FORMAT(now(),'%Y-%m-%d %H:%i:%s') ");
        if($verify->count() > 0){
            return [
                'status'    => true,
                'user_id'   => $verify->first()->user_id
            ];
        }
        return [
            'status'    => false,
            'user_id'   => null
        ];
    }

    public static function update($id, $data)
    {
        User::where('id', $id)->update($data);
    }

    public static function destroy($id)
    {
        User::where('id', $id)->delete();
    }
    
    public static function staffOnly($id = NULL)
    {
        $query = User::with('roles')
        ->with('organization')
        ->whereHas('roles', function($q) {
            $q->whereNotIn('name', ['Customer']);
        });
        if($id != null) {
            $query = $query->where('id', $id);
            return $query->firstOrFail();
        }
        return $query->get();
    }
    
    public static function customerOnly($id = NULL)
    {
        $query = User::with('roles')
        ->with('organization')
        ->whereHas('roles', function($q) {
            $q->where('name', 'Customer');
        });
        if($id != null) {
            $query = $query->where('id', $id);
            return $query->firstOrFail();
        }
        return $query->get();
    }

    public static function customerAddress($customerId, $isDefault = null, $addressId = null)
    {
        $query = CustomerAddresses::where('user_id', $customerId)
                ->leftJoin('provinces','customer_addresses.shipping_province_id', '=', 'provinces.prov_id')
                ->leftJoin('cities', 'customer_addresses.shipping_city_id','=', 'cities.city_id')
                ->leftJoin('districts', 'customer_addresses.shipping_district_id','=','districts.dis_id')
                ->leftJoin('subdistricts', 'customer_addresses.shipping_subdistrict_id','=','subdistricts.subdis_id')
                ->when($addressId, function($query) use($addressId) {
                    $query->where('customer_addresses.id', $addressId);
                })
                ->when($isDefault, function($query) use($isDefault) {
                    $query->where('customer_addresses.is_default', $isDefault);
                });
        return $query;
    }

    public static function merchantAddress($merchantId) 
    {
        $query = MerchantAddress::where('merchant_id', $merchantId)
                ->leftJoin('provinces','merchant_addresses.province_id', '=', 'provinces.prov_id')
                ->leftJoin('cities', 'merchant_addresses.city_id','=', 'cities.city_id')
                ->leftJoin('districts', 'merchant_addresses.district_id','=','districts.dis_id')
                ->leftJoin('subdistricts', 'merchant_addresses.subdistrict_id','=','subdistricts.subdis_id');
        return $query;
    }

}