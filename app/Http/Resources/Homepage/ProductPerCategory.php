<?php

namespace App\Http\Resources\Homepage;

use App\Http\Resources\Frontend\ProductsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPerCategory extends JsonResource
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
            'id'    => $this->id,
            'name'  => $this->name,
            'slug'  => $this->slug,
            'status'=> $this->status == "1" ? "Actived" : "Deactived",
            'level' => $this->depth_level,
            'product_total' => $this->products_count,
            'products'      => ProductsResource::collection($this->products->take(20))
        ];
    }
}
