<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddressRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'number', 'number');
    }
}
