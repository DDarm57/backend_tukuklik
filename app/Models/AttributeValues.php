<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeValues extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['value','attribute_id'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariants::class, 'attribute_value_id');
    }
}
