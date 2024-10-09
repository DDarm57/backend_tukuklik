<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockHold extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function productRequest()
    {
        return $this->belongsTo(ProductRequest::class);
    }

    public function productSku()
    {
        return $this->belongsTo(ProductSku::class);
    }
}
