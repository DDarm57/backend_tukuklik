<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Category extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'depth_level',
        'status'
    ];

    public function parentCategory(){
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    public function categoryImage(){
        return $this->hasOne(CategoryImage::class, 'category_id', 'id');
    }

    public function products(){
        return $this->belongsToMany(Product::class)->withPivot('category_id', 'product_id');
    }

    public function subCategories(){
        return $this->hasMany(Category::class,'parent_id','id')->with('subCategories');
    }

    public function productPhotos() 
    {
        return $this->hasMany(ProductPhotos::class);
    }

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }
}
