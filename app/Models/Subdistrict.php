<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
{
    use HasFactory;

    public function district()
    {
        return $this->belongsTo(District::class, 'dis_id', 'dis_id');
    }

}
