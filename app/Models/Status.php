<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = "status";

    public function requestForQuotation()
    {
        return $this->belongsToMany(Quotation::class,'rfq_number','rfq_number');
    }
}
