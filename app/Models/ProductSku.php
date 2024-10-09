<?php

namespace App\Models;

use App\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class ProductSku extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        static::deleting(function($skus) {
            $skus->productVariants->each->delete();
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariants::class);
    }

    public function getVarianAttribute()
    {
        $arr = Collection::make([]);
        foreach($this->productVariants()->get() as $variant) {
            $arr->push($variant->attributeValue->value);
        }
        return $arr->implode(', ');
    }

    public function getStockStatusAttribute()
    {
        $stock = ProductRepository::getStockBySKU($this->id);
        if($stock > 0) {
            return "In Stock";
        }
        return "Out Of Stock";
    }

    public function productRequests()
    {
        return $this->hasMany(ProductRequest::class);
    }
}
