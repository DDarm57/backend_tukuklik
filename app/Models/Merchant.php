<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Merchant extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function address()
    {
        return $this->hasOne(MerchantAddress::class);
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'user_pic');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
