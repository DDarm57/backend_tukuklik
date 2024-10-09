<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariants extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function product_sku()
    {
        return $this->belongsTo(ProductSku::class);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function attributeValue()
    {
        return $this->belongsTo(AttributeValues::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
