<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ProductRequest extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $guared = [];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'number', 'number');
    }

    public function productSku()
    {
        return $this->belongsTo(ProductSku::class);
    }

    public function stockHold()
    {
        return $this->hasOne(ProductStockHold::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'number', 'order_number');
    }
}
