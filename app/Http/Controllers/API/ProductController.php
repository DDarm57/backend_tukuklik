<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Resources\Frontend\ProductSkuByVarian;
use App\Http\Resources\Frontend\ProductsResource;
use App\Http\Resources\Product\ProductVisitorResource;
use App\Models\AttributeValues;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\Tag;
use App\Repositories\MerchantRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductController extends Controller
{
    public function showProduct($slug)
    {
        try {
            $query = Product::query()->where('slug', $slug)->firstOrFail();
            $product = new ProductsResource($query);
                        
            $data['product'] = $product;
            
            /* Categories */
            $cat = Collection::make([]);
            $product->categories()->get()->map(function($n) use($cat) {
                $cat->push($n->name);
            });
            $data['categories'] = $cat->implode(',');

            /* Tags */
            $tags = Collection::make([]);
            $product->productTags()->get()->map(function($n) use($tags) {
                $tags->push($n->tag->name);
            });
            $data['tags'] = $tags->implode(',');

            /* Variants */
            $attribute = array();
            foreach($product->productSkus()->get() as $skus){
                foreach($skus->productVariants()->get() as $variants){
                    array_push($attribute, [
                        'attribute' => $variants->attribute->name,
                        'value'     => []
                    ]);
                }
            }
            $attribute = array_unique($attribute, SORT_REGULAR);
            $attrValue = AttributeValues::query()->whereHas('variants', function($q) use($product) {
                $q->where('product_id', $product->id);
            });
            foreach($attribute as &$attr) {
                $arrValue = [];
                foreach($attrValue->get() as $value){
                    if($value->attribute->name == $attr['attribute']){
                        array_push($arrValue, [
                            'name'          => $value->value,
                            'is_primary'    => ProductRepository::isPrimary($value->id, $product->id)
                        ]);
                    }
                }
                $attr['value'] = $arrValue;
                foreach($attr['value'] as $val){
                    if($val['is_primary']){
                        $attr['primary'] = $val['name'];
                    }
                }
            }
            
            $data['attribute'] = $attribute;
            // $data['references'] = ProductRepository::productReferences($cat);

            $merchantAddress = UserRepository::merchantAddress($product->merchant->id);
            $formatMerchantAddress = Helpers::formatAddress($merchantAddress->first());
            $data['merchantAddress'] = $formatMerchantAddress;

            $sumRating = $product->reviews()->sum('rating');
            $data['rating'] =   $product->reviews->count() > 0 ?
                                    floor($sumRating) / $product->reviews->count()
                                : 0;    

            $merchantRating = MerchantRepository::rating($product->merchant_id);
            $data['countMerchantRating'] = $merchantRating['countMerchantRating'];
            $data['merchantRating'] =  $merchantRating['merchantRating'];

            $catReviewArr = Collection::make([]);
            for($i = 1; $i<=5; $i++){
                $catReviewArr->push([
                    'star'          => $i,
                    "calculate"     => $product->reviews->count() > 0 
                                        &&  $product->reviews()->whereRaw('FLOOR(rating) = '.$i.' ')->count()  
                                        ? 
                                            $product->reviews->count() 
                                            / 
                                            $product->reviews()->whereRaw('FLOOR(rating) = '.$i.' ')->count() 
                                            * 100 
                                        : 0
                ]);
            }

            $data['categoryReview'] = $catReviewArr;
                        
            return response()->json($data);
        } catch(Exception $e) {
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage()
            ], 400);
            DB::rollBack();
        }
    }
    
    public function getAllProduct(Request $request)
    {
        return  ProductsResource::collection(
                        ProductRepository::productAll($request->all())
                )->additional([
                    'option'    => $this->getProductByCategoryOrTag($request)
                ]);
    }

    private function getProductByCategoryOrTag(Request $request)
    {
        $slug = $request->get('slug') ?? null;
        $type = $request->get('type') ?? null;

        $cat =  Category::query()
                ->withCount('products')
                ->with('subCategories')
                ->limit(3)
                ->when($type, function($q) use($slug, $type) { 
                    if($type == "category") {
                        $q->where('slug', $slug);
                    }
                 });

        $tags =  ProductTag::query()
                 ->with('tag')
                 ->when($type, function($q) use($slug, $type) { 
                    if($type == "category") {
                        $q->whereHas('product', function($qq) use($slug) {
                            $qq->whereHas('categories', function($n) use($slug) {
                                $n->where('slug', $slug);
                            });
                        });
                    }
                 })
                 ->groupBy('tag_id')
                 ->limit(15)
                 ->get();
        
        $product =  Product::query()
                    ->join(
                        'product_skus',
                        'products.id',
                        'product_skus.product_id'
                    )
                    ->where('is_primary','Y')
                    ->when($type, function($q) use($slug, $type) {
                        if($type == "category") {
                            $q->whereHas('categories', function($qq) use($slug) {
                                $qq->where('slug', $slug);
                            });
                        } else {
                            $q->whereHas('productTags', function($qq) use($slug) {
                                $qq->whereHas('tag', function($n) use($slug) {
                                    $n->where('name', $slug);
                                });
                            });
                        }
                    });

        $tag = Tag::where('name', $slug)->first();
        $catName = $cat->get()[0]->name ?? null;
        $name = $type == "Tag" ? "Tag ". $tag->name : ($type == "category" ? "Kategori ". $catName ?? '' : "Seluruh Produk");
        $data['catOrTag'] = $cat->get();
        $data['title'] = $name;
        $data['slug'] = $slug;
        $data['type'] = $type;
        $data['tags'] = $tags;
        $data['minPrice'] = $product->min('selling_price');
        $data['maxPrice'] = $product->max('selling_price');
        return response()->json($data);
    }

    public function getSkuByVariant(Request $request)
    {
        $this->validate($request ,[
            'attribute_value.*'     => 'required',
            'product_id'            => 'required'
        ]);

        $attrValue = $request->input('attribute_value') ?? [];

        $query = ProductRepository::getProductDetailByVariant($attrValue, $request->product_id);
        return (new ProductSkuByVarian($query))->qty($request->qty);
    }

    public function productReferences(Request $request)
    {
        $categories = $request->get('category');
        $data['references'] = ProductRepository::productReferences($categories);
        return response()->json($data);
    }

    public function productLastVisit()
    {
        return ProductVisitorResource::collection(ProductRepository::lastVisit());
    }

}
