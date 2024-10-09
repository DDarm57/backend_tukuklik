<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
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
        $method = $this->getMethod();
        return [
            'product_name' =>'required|max:255',
            'product_type' => 'required',
            'minimum_order_qty' => 'required',
            'tags' => 'required',
            'unit_type' => 'required',
            'description'   => 'required',
            'max_order_qty' => 'nullable|numeric|gt:minimum_order_qty',
            'merchant'   => 'required',
            'weight' => 'nullable|numeric',
            'length' => 'nullable|numeric',
            'breadth' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'product_stock' => 'required|numeric',
            'selling_price' => 'required',
            'categories.0' => 'required',
            'sku_varian.*' => ['required_if:product_type,==,varian',
                Rule::unique('product_skus', 'sku')->where(function($q) use($method) {
                    $query = $q->whereIn('sku', $this->sku_varian);
                    $qWhere = $method == "PUT" ? $query = $query->where('product_id','!=', $this->route('product')) : '';
                    return $qWhere;
                })
            ],
            'product_sku' => ['required_if:product_type,==,product',
                Rule::unique('product_skus', 'sku')->where(function($q) use($method) {
                    $query = $q->where('sku', $this->product_sku);
                    $qWhere = $method == "PUT" ? $query = $query->where('sku','!=', $this->product_sku) : '';
                    return $qWhere;
                })
            ],
            'product_stock' => ['required_if:product_type,==,product'],
            'discount' => ['nullable', 'required_with:discount_type','numeric'],
            'selling_price' => ['required_if:product_type,==,product','required_with:has_wholesale'],
            // 'status'    => ['required'],
            'video_link' => ['nullable', 'url'],
            'stock_type' => ['required'],
            'processing_estimation' => ['required', 'gt:0']   
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
