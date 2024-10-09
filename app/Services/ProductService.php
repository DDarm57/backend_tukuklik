<?php 

namespace App\Services;

use App\Http\Controllers\Product\MediaController;
use App\Models\AttributeValues;
use App\Models\Media;
use App\Models\Merchant;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductStockHold;
use App\Models\Tag;
use App\Models\Tax;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ProductService {
    
    public static function product($request, $data, $id = 0)
    {
        $product = new Product();
        if($id > 0) {
            $product = $product->where('id', $id)->first();
        }
        if($data['max_order_qty'] != null && $data['max_order_qty'] < 1){
            $data['max_order_qty'] = null;
        }
        $merchant = Merchant::where("id", $request->merchant)->first();
        if($merchant->is_pkp == 'Y') {
            $tax = Tax::where('name', 'LIKE', '%PPN%')->first();
        } 
        $data['tax_type'] = $tax->name ?? '';
        $data['tax'] =  $tax->percentage ?? 0; 
        $data['product_type'] = $data['product_type'] == 'product' ? 1 : 2;
        $data['unit_type_id'] = $data['unit_type'];
        $data['merchant_id']  = $data['merchant'];
        $data['status']  = $data['status'] ?? 0;
        $data['discount'] = $data['discount'] ?? 0;
        $media = $request->input('media')[0] ?? '';
        $thumbnail = Media::where('id', $media)->first();
        $data['thumbnail_image_source'] = $thumbnail->path ?? '';
        $data['pdf'] = $product->pdf;
        if($request->file('pdf')) {
            $media = App::make(MediaController::class);
            $newRequest = new Request();
            $newRequest->merge([
                'path'      => 'product_document',
                'media_type'=> 'product_document'
            ]);
            $newRequest->files->set('file', $request->file('pdf'));
            $storeDoc = $media->store($newRequest);
            $data['pdf'] = json_decode($storeDoc->getData()->data->id);
        }
        $product->fill([
            'product_name'              => $data['product_name'],
            'product_type'              => $data['product_type'],
            'unit_type_id'              => $data['unit_type_id'],
            'description'               => $data['description'],
            'thumbnail_image_source'    => $data['thumbnail_image_source'],
            'discount'                  => $data['discount'],
            'tax_type'                  => $data['tax_type'],
            'tax'                       => $data['tax'],
            'pdf'                       => $data['pdf'],
            'video_link'                => $data['video_link'],
            'minimum_order_qty'         => $data['minimum_order_qty'],
            'max_order_qty'             => $data['max_order_qty'],
            'status'                    => $data['status'],
            'slug'                      => $data['slug'],
            'discount_type'             => $data['discount_type'],
            'merchant_id'               => $data['merchant_id'],
            'stock_type'                => $data['stock_type'],
            'processing_estimation'     => $data['processing_estimation']
        ])->save();
        return $product;
    }

    public static function category($categories, $product) 
    {
        $newCat = [];

        $cat =  $product->categoryProduct()
                ->where('product_id', $product->id)
                ->whereNotIn('category_id', $categories)
                ->count();
                
        if($cat > 0) {
            $product->categoryProduct()->delete();
        }
        
        foreach($categories as $cat) {
            if($cat != null) {
                array_push($newCat, [
                    'category_id'   => $cat,
                    'product_id'    => $product->id,
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ]);
            }
        }   
        
        $product->categoryProduct()->insert($newCat);
    }

    public static function tags($tags, $product)
    {
        $tags = json_decode($tags);
        $updatingTag = [];
        if(count($tags) > 0) {
            foreach($tags as $tag) {

                $tag = Tag::where('name', $tag->value)->updateOrCreate([
                    'name'  => strtolower($tag->value)
                ]);

                array_push($updatingTag, $tag->id);
                
                $product->productTags()->updateOrCreate([
                    'tag_id'        => $tag->id,
                    'product_id'    => $product->id
                ]);
            }

            $product->productTags()
            ->whereNotIn('tag_id', $updatingTag)
            ->where('product_id', $product->id)
            ->delete();

        }
    }

    public static function skuVarian($data, $product)
    {
        $varian = $data['sku_varian'] ?? [];
            $dimension = [
                'weight'        => $data['weight'] ?? 0,
                'length'        => $data['length'] ?? 0,
                'breadth'       => $data['breadth'] ?? 0,
                'height'        => $data['height'] ?? 0
            ];
            if(count($varian) > 0) {
                $oldTrackSkus = $data['old_track_sku'] ?? [];
                $updateTrackSkus = [];
                foreach($varian as $key => $skuvarian) {
                    $skuModel = new ProductSku();
                    $attributes = str_contains($data['varian_attribute'][$key],',');
                    $oldTrackSku = $data['old_track_sku'][$key] ?? null;
                    $newTrackSku = $skuvarian.'-'.str_replace(',','-', $data['varian_attribute'][$key]);
                    array_push($updateTrackSkus, $oldTrackSku);
                    $skuModel = $product->productSkus()->updateOrCreate([
                            'track_sku' => $oldTrackSku
                        ], [
                        'product_id'    => $product->id,
                        'sku'           => $skuvarian,
                        'selling_price' => str_replace('.','',$data['harga_varian'][$key]),
                        'track_sku'     => $newTrackSku,
                        'status'        => $data['status'],
                        'product_stock' => $data['stok_varian'][$key],
                        'is_primary'    => $key == 0 ? "Y" : "T",
                        ...$dimension
                    ]);
                    if($attributes) {
                        $explodeAttr = explode(',', $data['varian_attribute'][$key]);
                        foreach($explodeAttr as $attr) {
                            $getAttr = AttributeValues::where('value', $attr)->first();
                            // $skuModel->productVariants()
                            // ->where(function($query) use($getAttr) {
                            //     $query
                            //     ->where('attribute_id', '!=', $getAttr->attribute_id)
                            //     ->orWhere('attribute_value_id', '!=', $getAttr->id);
                            // })
                            // ->where('product_id', $product->id)
                            // ->where('product_sku_id', $skuModel->id)
                            // ->delete();
                            $skuModel->productVariants()->updateOrCreate([
                                'product_id'        => $product->id,
                                'product_sku_id'    => $skuModel->id,
                                'attribute_id'      => $getAttr->attribute_id,
                                'attribute_value_id'=> $getAttr->id
                            ]);
                        }
                    } else {
                        $getAttr = AttributeValues::where('value', $data['varian_attribute'][$key])->first();
                        $skuModel->productVariants()
                        ->where(function($query) use($getAttr) {
                            $query
                            ->where('attribute_id', '!=', $getAttr->attribute_id)
                            ->orWhere('attribute_value_id', '!=', $getAttr->id);
                        })
                        ->where('product_id', $product->id)
                        ->where('product_sku_id', $skuModel->id)
                        ->delete();
                        $skuModel->productVariants()->updateOrCreate([
                            'product_id'            => $product->id,
                            'product_sku_id'        => $skuModel->id,
                            'attribute_id'          => $getAttr->attribute_id,
                            'attribute_value_id'    => $getAttr->id
                        ]);
                    }
                }
                $diffSkus =  array_diff($oldTrackSkus, $updateTrackSkus);
                foreach($diffSkus as $diff) {
                    $delSku = $product->productSkus()->where('track_sku', $diff);
                    $delSku->first()->productVariants()->delete();
                    $delSku->delete();
                }
            } else {
                $product->productSkus()->updateOrCreate([
                    'product_id'    => $product->id
                ], ['product_id'    => $product->id,
                    'sku'           => $data['product_sku'],
                    'selling_price' => str_replace('.','',$data['selling_price']),
                    'status'        => $data['status'],
                    'product_stock' => $data['product_stock'],
                    'is_primary'    => 'Y',
                    ...$dimension
                ]);
            }
    }

    public static function wholesale($data, $product)
    {
        $product->wholeSalers()->where('product_id', $product->id)->delete(); //for temporary alias capee pen istirahat

        $hasWholesale = $data['has_wholesale'] ?? '';
        if(count($data['wholesale_price']) > 0 
            && $data['min_wholesale_qty'][0] > 0
            && $hasWholesale != '') 
        {
            foreach($data['wholesale_price'] as $key => $value){

                 $product->wholeSalers()->updateOrCreate([
                    'product_id'        => $product->id,
                    'min_order_qty'     => $data['min_wholesale_qty'][$key],
                    'selling_price'     => str_replace('.', '', $data['wholesale_price'][$key])
                ]);

            }
        }
    }

    public static function media($medias, $product) 
    {
        if(count($medias)) {
            foreach($medias as $key => $media){
                $path = Media::where('id', $media)->first();

                $product->productPhotos()->updateOrCreate([
                    'product_id'    => $product->id,
                    'media_id'      => $path->id
                ]);
            }
        }
    }

    public static function stockHold($product)
    {

        $stockHold = new ProductStockHold;

        $stockHold->whereHas('productRequest', function($q) {
            $q->whereHas('quotation', function($query) {
                $query->whereIn('status', [13,14]);
            });
        })->delete();

        $stockHold->create([
            'product_request_id'    => $product->id,
            'product_sku_id'        => $product->product_sku_id,
            'stock'                 => $product->quantity,
        ]);

        return true;
    }

    public static function releaseStock($quote) 
    {
        foreach($quote->productRequests()->get() as $prodReq)
        {
            $prodReq->productSku->fill([
                'product_stock' => $prodReq->productSku->product_stock - $prodReq->quantity
            ])->save();
        }
    }

    public static function removeStockHold($quote)
    {
        foreach($quote->productRequests()->get() as $prodReq)
        {
            $prodReq->stockHold->delete();
        }
    }

}