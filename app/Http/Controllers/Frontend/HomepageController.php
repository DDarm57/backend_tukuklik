<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\ProductsResource;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Merchant;
use App\Models\Product;
use App\Models\Tag;
use App\Repositories\MarketingRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function homePage(Request $request)
    {
        $data['banner'] = self::banners();
        $data['flashDeal'] = self::flashDeals();
        $data['productByCategory'] = self::productsByCategory();
        $data['categories'] = ProductRepository::getCategories();
        $data['recommendation'] = ProductRepository::productRecomendation();

        if($request->ajax()){
            return ProductsResource::collection(ProductRepository::productAll());
        }

        return view('index', $data);
    }

    private static function banners()
    {
        $banner = MarketingRepository::banners();
        return $banner;
    }

    private static function flashDeals()
    {
        $flashDeals = MarketingRepository::flashDeals();
        return $flashDeals;
    }

    private static function productsByCategory()
    {
        $categoryProduct = ProductRepository::getCategoryProduct();
        return $categoryProduct;
    }

    public function advancedSearch(Request $request)
    {
        $option = $request->get('option');
        $keyword = $request->get('keyword');

        if($option == "all" || $option == "byProduct") 
        {
            $data['product'] =  ProductsResource::collection(
                                    Product::query()
                                    ->active()
                                    ->where('product_name', 'LIKE', '%'.$keyword.'%')
                                    ->limit(4)
                                    ->latest()
                                    ->get()
                                );
        }

        if($option == "all" || $option == "byCategory") 
        {
            $data['category'] = Category::query()
                                ->withCount('products')
                                ->where('name', 'LIKE', '%'.$keyword.'%')
                                ->limit(4)
                                ->latest()
                                ->get();
        }

        if($option == "all" || $option == "byTag") 
        {
            $data['tag'] = Tag::query()
                                ->where('name', 'LIKE', '%'.$keyword.'%')
                                ->limit(4)
                                ->latest()
                                ->get();
        }

        if($option == "all" || $option == "byMerchant") 
        {
            $data['merchant'] = Merchant::query()
                                ->where('name', 'LIKE', '%'.$keyword.'%')
                                ->limit(4)
                                ->latest()
                                ->get();
        }

        return response()->json($data);
        
    }
}
