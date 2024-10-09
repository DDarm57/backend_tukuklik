<?php

namespace App\Http\Resources\Homepage;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CategoryResource extends JsonResource
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
            'level' => $this->depth_level,
            'image' => url(Storage::url($this->image)),
            'status'=> $this->status == "1" ? "Actived" : "Deactived",
            'product_total' => $this->products_count     
        ];
    }
}
