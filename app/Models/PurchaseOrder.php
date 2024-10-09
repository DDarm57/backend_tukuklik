<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_number', 'number');
    }

    public function channel()
    {
        return $this->belongsTo(TransactionChannel::class, 'channel_id');
    }

    public function activities()
    {
        return $this->hasMany(TransactionActivity::class, 'channel_id', 'channel_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'order_status');
    }

    public function deliveryOrder()
    {
        return $this->hasOne(DeliveryOrder::class, 'order_number', 'order_number');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class,' order_number','order_number');
    }

    public function documents()
    {
        return $this->hasMany(DocumentRequest::class, 'number', 'order_number');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
