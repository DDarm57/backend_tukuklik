<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryImage extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'category_id',
        'image'
    ];

    use HasFactory;
}
