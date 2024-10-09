<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Frontend\ProductsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVisitorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return new ProductsResource($this->visitable);
    }
}
