<?php

namespace App\Http\Resources\Homepage;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FlashSaleResource extends JsonResource
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
            'product_id'        => $this->product_id,
            'product_name'      => $this->product_name,
            'thumbnail'         => url($this->thumbnail_image_source),
            'price_before_disc' => $this->selling_price,
            'price_after_disc'  => $this->real_price,
            'rating'            => $this->product->rating,
            'review_count'      => $this->product->reviews->count(),
            'minimum_order_qty' => $this->product->minimum_order_qty,
            'unit'              => $this->product->unit_type->name,
            'flashDeal'         => [
                'id'            => $this->flashDeal->id,
                'start_date'    => $this->flashDeal->start_date,
                'end_date'      => $this->flashDeal->end_date,
                'banner'        => url(Storage::url($this->flashDeal->banner)),
            ],
            'merchant'          => [
                'id'            => $this->product->merchant->id,
                'name'          => $this->product->merchant->name,
                'is_pkp'        => $this->product->merchant->is_pkp,
            ],
            'discount'          => $this->disc,
            'discount_type'     => $this->discount_type,
        ];
    }
}
