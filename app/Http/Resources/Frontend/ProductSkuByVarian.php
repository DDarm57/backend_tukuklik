<?php

namespace App\Http\Resources\Frontend;

use App\Models\ProductWholesaler;
use App\Repositories\ProductRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductSkuByVarian extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    protected $qty;

    public function qty($qty) 
    {
        $this->qty = $qty;
        return $this;
    }

    public function toArray($request)
    {
        return [
            'product_name'  => $this->product_name,
            'slug'          => $this->slug,
            'stock_type'    => $this->stock_type,
            'weight'        => $this->weight,
            'length'        => $this->length,
            'breadh'        => $this->breadth,
            'height'        => $this->height,
            'minimum_order_qty' => $this->minimum_order_qty,
            'sku'           => [
                'track_sku'     => $this->track_sku,
                'sku'           => $this->sku,
                'discount'      => $this->discount,
                'discount_type' => $this->discount_type,
                'stock'         => ProductRepository::getStockBySKU($this->product_sku_id),
                'selling_price' => $this->selling_price,
                'price_after_disc' => ProductRepository::priceAfterDiscount($this->id, $this->product_sku_id, $this->qty)
            ],
            'whole_sale' => ProductWholesaler::where('product_id', $this->id)->get()
        ];
    }
}
