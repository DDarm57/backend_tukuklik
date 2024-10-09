<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MerchantRequest extends FormRequest
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
        return [
            'name'      => 'required',
            'user_pic'  => 'required',
            'phone'     => 'required|numeric',
            'npwp'      => 'required',
            'is_pkp'    => 'required',
            'status'    => 'required',
            'province'  => 'required',
            'city'      => 'required',
            'district'  => 'required',
            'subdistrict'=> 'required',
            'address'   => 'required|max:255',
            'postcode'  => 'required|numeric|max_digits:7',
            'address_name' => 'required|max:100'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'status' => 'success'
        ], 422));
    }
}
