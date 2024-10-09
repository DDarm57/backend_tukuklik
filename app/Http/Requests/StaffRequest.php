<?php

namespace App\Http\Requests;

use App\Services\RolePermissionService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules =  [
            'name'              => 'required|min:3|max:255',
            'email'             => 'required|unique:users,email|email',
            'phone_number'      => 'required|numeric|max_digits:20',
            'date_of_birth'     => 'required|date',
            'organization_id'   => 'required',
            'role'              => 'required',
            'is_actived'        => 'required',
            'password'          => 'required|confirmed|min:6',
            'photo'             => 'required|image|max:2048',
        ];
        if($this->request->get('_method') == "PUT"){
            $rules['password'] = 'nullable';
            if($this->request->get('password') != ''){
                $rules['password'] = "min:5|confirmed";
            }
            $rules['email'] = ['required','email',Rule::unique('users')->ignore($this->route('staff'))];
            $rules['photo'] = 'nullable|image|max:2048';
        }
        return $rules;
    }
}
