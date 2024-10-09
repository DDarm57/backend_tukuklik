<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\ProductsResource;
use App\Http\Resources\Homepage\CategoryResource;
use App\Http\Resources\Homepage\FlashSaleResource;
use App\Http\Resources\Homepage\ProductPerCategory;
use App\Repositories\MarketingRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomepageController extends Controller
{
    public function banner()
    {
        $banner = MarketingRepository::banners();
        $banner->map(function($n) {
            $n->slider_image = url(Storage::url($n->slider_image));
            $n->status = $n->status == "1" ? "Active" : "Deactive";
            $n->is_newtab = $n->is_newtab == "1" ? "Y" : "T";
        });
        return response()->json([
            'status'    => 'success',
            'data'      => $banner
        ]);
    }

    public function flashSaleProduct()
    {
        $flashDeal = MarketingRepository::flashDeals();
        return response()->json([
            'status'    => 'success',
            'data'      => FlashSaleResource::collection($flashDeal)
        ]);
    }

    public function categories()
    {
        return response()->json([
            'status'    => 'success',
            'data'      => CategoryResource::collection(ProductRepository::getCategories())
        ]);
    }

    public function productPerCategory()
    {
        return response()->json([
            'status'    => 'success',
            'data'      => ProductPerCategory::collection(ProductRepository::getCategoryProduct())
        ]);
    }

    public function productAll()
    {
        return ProductsResource::collection(ProductRepository::productAll());
    }
}
