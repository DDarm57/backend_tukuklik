<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Quotation extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::deleting(function($quote) {
            $quote->addressRequest->delete();
            // $quote->productRequests->each->delete();
            // $quote->shippingRequest->each->delete();
            // $quote->documentRequest->each->delete();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function quoteStatus()
    {
        return $this->belongsTo(Status::class, 'status','id');
    }

    public function addressRequest()
    {
        return $this->hasOne(AddressRequest::class, 'number', 'number');
    }

    public function productRequests()
    {
        return $this->hasMany(ProductRequest::class, 'number', 'number');
    }

    public function shippingRequest()
    {
        return $this->hasOne(ShippingRequest::class, 'number', 'number');
    }

    public function documentRequests()
    {
        return $this->hasMany(DocumentRequest::class, 'number', 'number');   
    }

    public function activities()
    {
        return $this->hasMany(TransactionActivity::class, 'channel_id', 'channel_id');
    }

    public function channel()
    {
        return $this->belongsTo(TransactionChannel::class,'channel_id');
    }

    public function negotiations()
    {
        return $this->hasMany(QuotationNegotiation::class, 'number', 'number');
    }
    
}
