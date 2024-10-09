<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionChannel extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_number', 'number');
    }

    public function rfq()
    {
        return $this->belongsTo(Quotation::class, 'rfq_number', 'number');
    }
}
