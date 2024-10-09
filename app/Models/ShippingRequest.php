<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ShippingRequest extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    // public function requestForQuotation()
    // {
    //     return $this->belongsTo(Quotation::class, 'rfq_number', 'rfq_number');
    // }

    // public function quotation()
    // {
    //     return $this->belongsTo(Quotation::class, 'quotation_number', 'quotation_number');
    // }

    public function addressRequest()
    {
        return $this->belongsTo(AddressRequest::class);
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method');
    }

}
