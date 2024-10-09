<?php

namespace App\Http\Resources\Frontend;

use App\Helpers\Helpers;
use App\Repositories\ProductRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    protected $showPaginate = false;

    public function showPaginate($param)
    {
        $this->showPaginate = $param;
        return $this;
    }

    public function toArray($request)
    {
        $skuPrimary = ProductRepository::skuPrimary($this->id);
        $product_photos = ProductRepository::getProductPohotos($this->id);

        return [
            'id'                => $this->id,
            'product_name'      => $this->product_name,
            'price_after_disc'  => number_format(ProductRepository::priceAfterDiscount($this->id, $skuPrimary->id, 1), 0, '.', '.'),
            'price_before_disc' => number_format($this->selling_price, 0, '.', '.'),
            'description'      => $this->description,
            'disc_percentage'   => $this->disc_percentage,
            'thumbnail'         => Helpers::image($this->thumbnail_image_source),
            'discount'          => $this->discount,
            'minimum_order_qty' => $this->minimum_order_qty,
            'unit'              => $this->unit_type->name,
            'discount_type'     => $this->discount_type,
            'tax'               => $this->tax,
            'tax_type'          => $this->tax_type,
            'slug'              => $this->slug,
            'product_photos'    => $product_photos,
            'sku_primary'       => [
                'id'            => $skuPrimary->id,
                'sku'           => $skuPrimary->sku,
                'product_stock' => $skuPrimary->product_stock,
                'track_sku'     => $skuPrimary->track_sku,
                'weight'        => $skuPrimary->weight,
                'length'        => $skuPrimary->length,
                'breadth'       => $skuPrimary->breadth,
                'height'        => $skuPrimary->height,
                'is_primary'    => $skuPrimary->is_primary,
                'stock'         => ProductRepository::getStockBySKU($skuPrimary->id)
            ],
            'merchant'          => [
                'id'            => $this->merchant->id,
                'name'          => $this->merchant->name,
                'phone'         => $this->merchant->phone,
                'photo'         => $this->merchant->photo != null ? url(Storage::url($this->merchant->photo)) : null,
                'is_pkp'        => $this->merchant->is_pkp,
                'status'        => $this->merchant->status == 1 ? "Actived" : "Deactived"
            ],
            'category'          => $this->categories,
            'stock_type'        => $this->stock_type,
            'processing_est'    => $this->processing_estimation,
            'reviews_count'     => $this->reviews_count,
            'rating'            => $this->rating,
            'created_by'        => $this->createdBy->name,
            'updated_by'        => $this->updatedBy->name,
            'created_at'        => $this->created_at,
            'count_sold'        => ProductRepository::countSold($this->id), //to be refactor #1
            'meta'              => $this->showPaginate == true ? $this->meta : null
        ];
    }
}
