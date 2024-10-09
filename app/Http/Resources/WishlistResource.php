<?php

namespace App\Http\Resources;

use App\Http\Resources\Frontend\ProductsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WishlistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'product_sku_id'=> $this->product_sku_id,
            'product'       => new ProductsResource($this->productSku->product),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at
        ];
    }
}
