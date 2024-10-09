<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public function districts()
    {
        return $this->hasMany(District::class, 'city_id','city_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'prov_id', 'prov_id');
    }

}
