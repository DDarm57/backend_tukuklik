<?php

namespace App\Models;

use App\Repositories\ProductRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Shetabit\Visitor\Traits\Visitable;

class Product extends Model implements Auditable
{
    use HasFactory, 
        SoftDeletes, 
        \OwenIt\Auditing\Auditable,
        Visitable;

    protected $table = "products";
    protected $guarded = [];
    protected $appends = ['selling_price'];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->created_by = Auth::user()->id ?? null;
            $model->updated_by = Auth::user()->id ?? null;
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::user()->id ?? null;
        });

        static::deleting(function($product) {
            $product->productSkus->each->delete();
            $product->productPhotos->each->delete();
            $product->wholeSalers->each->delete();
            $product->categoryProduct->each->delete();
            $product->productTags->each->delete();
        });
        
    }

    public function unit_type()
    {
        return $this->belongsTo(UnitType::class)->withDefault();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)
                ->withPivot('category_id', 'product_id')
                ->wherePivotNull('deleted_at');
    }

    public function productSkus()
    {
        return $this->hasMany(ProductSku::class);
    }

    public function productPhotos()
    {
        return $this->hasMany(ProductPhotos::class);
    }

    public function merchant(){
        return $this->belongsTo(Merchant::class);
    }

    public function wholeSalers()
    {
        return $this->hasMany(ProductWholesaler::class);
    }

    public function categoryProduct()
    {
        return $this->hasMany(CategoryProduct::class);
    }

    public function productTags()
    {
        return $this->hasMany(ProductTag::class);
    }

    public function document()
    {
        return $this->belongsTo(Media::class, 'pdf');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function flashDealProducts()
    {
        return $this->hasMany(FlashDealProduct::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getSellingPriceAttribute()
    {
        return $this->productSkus
                ->where('is_primary', 'Y')
                ->pluck('selling_price')[0];
    }

    public function getDiscPercentageAttribute()
    {
        $discFlash = ProductRepository::flashDealDiscount($this->id, $this->selling_price);

        if($this->discount_type == "Harga"){
            $disc =  $this->discount / $this->selling_price;
        }
        else {
            $disc =  $this->discount;
        }

        return round($disc + $discFlash, 0);
    }

    public function getDiscAmtAttribute()
    {
        $this->price = $this->selling_price;
        $this->wholeSalers->map(function($n) {
            if($this->minimum_order_qty >= $n->min_order_qty){
                $this->price = $n->selling_price;
            }
        });

        $discFlash = ProductRepository::flashDealDiscount($this->id, $this->selling_price);
        $flashPrice = $this->price * ($discFlash / 100);

        if($this->discount_type == "Harga"){
            $discPrice = $this->price - $this->discount;
        }
        else {
            $discPrice = $this->price - 
            (
                $this->price * ($this->discount / 100)
            );
        }

        return $discPrice - $flashPrice;
    }

    public function getRatingAttribute()
    {
        $rating = $this->reviews()->sum('rating');
        return  $this->reviews->count() > 0 ? 
                    floor($rating) / $this->reviews->count()
                : 0;
    }

    public function scopeActive($q)
    {
        $q->where('status', 1);
    }
    

    public function getCountSoldAttribute()
    {
        return ProductRepository::countSold($this->id);
    }
}
