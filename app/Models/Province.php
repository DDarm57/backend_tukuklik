<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    public function cities()
    {
        return $this->hasMany(City::class, 'prov_id','prov_id');
    }
}
