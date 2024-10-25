<?php

namespace App\Repositories;

use App\Helpers\Helpers;
use App\Models\AttributeValues;
use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\FlashDealProduct;
use App\Models\Product;
use App\Models\ProductRequest;
use App\Models\ProductSku;
use App\Models\ProductStockHold;
use App\Models\ProductVariants;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Shetabit\Visitor\Models\Visit;

class ProductRepository
{

    public static function findAll($filter = [], $descending = false)
    {
        $product = Product::query();
        $status = $filter['status'] ?? null;
        $product = $product
            ->with(['productSkus', 'productTags'])
            ->when($status, function ($query) use ($filter) {
                $query->where('status', $filter['status']);
            })
            ->when($filter['product_type'], function ($query) use ($filter) {
                $query->where('product_type', $filter['product_type']);
            })
            ->when($filter['stock'], function ($query) use ($filter) {
                $query->whereHas('productSkus', function ($q) use ($filter) {
                    $stock = $filter['stock'] == "has_stock" ? '> 0' : ' < 0';
                    $q->havingRaw('SUM(product_stock) ' . $stock . ' ');
                });
            })
            ->when(!Auth::user()->hasAnyRole(['Super Administrator', 'Staff']), function ($query) {
                $query->where('merchant_id', Auth::user()->merchant->id ?? null);
            })
            ->when($descending, function ($query) {
                $query->limit(30)->orderByDesc('created_at');
            });
        if (isset($filter['status'])) {
            $product->where('status', $filter['status']);
        }
        return $product->latest();
    }

    public static function getProductPohotos($productId)
    {
        $getProductPhotos = DB::table('product_photos')->where('product_id', $productId)->get();

        $product_photos = [];
        foreach ($getProductPhotos as $item) {
            $getMedia = DB::table('media')->where('id', $item->media_id)->first();
            $product_photos[] = $getMedia->path;
        }

        return $product_photos;
    }

    public static function getCategoryByLvl($lvl, $parent = null)
    {
        return Category::query()
            ->where('depth_level', $lvl)
            ->when($parent, function ($query) use ($parent) {
                $query->where('parent_id', $parent);
            })
            ->get();
    }

    private static function stockHold($productSkuId = null, $productId = null)
    {
        return ProductStockHold::query()
            ->where('product_sku_id', $productSkuId)
            ->when($productId, function ($q) use ($productId) {
                $q->orWhere('product_id', $productId);
            })
            ->sum('stock');
    }

    public static function getStockBySKU($productSkuId)
    {
        $sku =  ProductSku::where('id', $productSkuId)->first();
        return $sku->product_stock - self::stockHold($productSkuId);
    }

    public static function getStockByProduct($productId)
    {
        $stock = ProductSku::where('product_id', $productId)->sum('product_stock');
        return $stock - self::stockHold(null, $productId);
    }

    public static function findDetail($id) {}


    public static function getCategoryProduct()
    {
        $query = Category::query()
            ->withWhereHas('products', function ($q) {
                $q->where('status', 1);
            })
            ->with('categoryImage')
            ->withCount('products')
            ->orderByDesc('products_count')
            ->where('depth_level', "1")
            ->active()
            ->having('products_count', '>', '0')
            ->limit(3)
            ->get();

        return $query;
    }

    public static function productAll($filter = [])
    {
        return  Product::query()
            ->active()
            ->when($filter['merchant'] ?? [], function ($q) use ($filter) {
                $q->whereIn('merchant_id', $filter['merchant']);
            })
            ->when($filter['min_price'] ?? 0, function ($q) use ($filter) {
                if ($filter['max_price'] > $filter['min_price']) {
                    $q->whereHas('productSkus', function ($qq) use ($filter) {
                        $qq->whereBetween('selling_price', [$filter['min_price'], $filter['max_price']]);
                    });
                }
            })
            ->when($filter['slug'] ?? null, function ($q) use ($filter) {
                if ($filter['type'] == "category") {
                    $q->whereHas('categories', function ($qq) use ($filter) {
                        $qq->where('slug', $filter['slug']);
                    });
                } else if ($filter['type'] == "tag") {
                    $q->whereHas('productTags', function ($qq) use ($filter) {
                        $qq->whereHas('tag', function ($n) use ($filter) {
                            $n->where('name', $filter['slug']);
                        });
                    });
                }
            })
            ->when($filter['stock_type'] ?? [], function ($q) use ($filter) {
                $q->whereIn('stock_type', $filter['stock_type']);
            })
            ->when($filter['tax_status'] ?? [], function ($q) use ($filter) {
                $q->whereHas('merchant', function ($qq) use ($filter) {
                    $qq->whereIn('is_pkp', $filter['tax_status']);
                });
            })
            ->when($filter['search'] ?? null, function ($q) use ($filter) {
                $q->where('product_name', 'LIKE', '%' . $filter['search'] . '%');
            })
            ->withCount('reviews')
            ->get();
    }

    public static function getCategories()
    {
        return Category::query()
            ->active()
            ->withCount('products')
            ->leftJoin(
                'category_images',
                'categories.id',
                'category_images.category_id'
            )
            ->where('depth_level', 1)
            ->selectRaw('1 AS total, name, image')
            ->groupBy('categories.id')
            ->get();
    }

    public static function skuPrimary($productId)
    {
        return ProductSku::query()
            ->where('product_id', $productId)
            ->where('is_primary', 'Y')
            ->first();
    }

    public static function isPrimary($attrId, $productId)
    {
        $query =  AttributeValues::query()
            ->where('id', $attrId)
            ->whereHas('variants', function ($q) use ($productId) {
                $q->wherehas('product_sku', function ($qq) use ($productId) {
                    $qq->where('is_primary', 'Y')->where('product_id', $productId);
                });
            })->count();
        if ($query > 0) {
            return true;
        }
        return false;
    }

    public static function productReferences($categories)
    {
        $references =   Product::query()->whereHas('categories', function ($q) use ($categories) {
            $q->whereIn('categories.id', $categories);
            $q->orWhereIn('categories.name', $categories);
        })
            ->limit(10)
            ->get();

        return $references;
    }

    public static function lastVisit()
    {
        return Visit::query()
            ->whereHasMorph('visitable', [Product::class])
            ->withWhereHas('visitable')
            ->where(function ($q) {
                $q->where('visitor_id', request()->user()->id ?? '');
                $q->orWhere('ip', request()->ip());
            })
            ->groupBy('visitable_id')
            ->latest()
            ->limit(8)
            ->get();
    }

    public static function getProductDetailByVariant($variant = [], $productId)
    {
        if (count($variant) == 0) {
            return ProductSku::query()
                ->where('is_primary', 'Y')
                ->where('product_id', $productId)
                ->join(
                    'products',
                    'product_skus.product_id',
                    'products.id'
                )
                ->selectRaw('product_skus.*,
                        products.*,
                        product_skus.id as product_sku_id
                    ')
                ->first();
        }

        return
            DB::query()
            ->fromSub(
                ProductVariants::query()
                    ->whereHas('attributeValue', function ($qq) use ($variant) {
                        $qq->where(function ($n) use ($variant) {
                            foreach ($variant as $value) {
                                $n->orWhere('value', $value);
                            }
                        });
                    })
                    ->where('product_id', $productId)
                    ->selectRaw('product_sku_id, count(*) AS count')
                    ->groupBy('product_sku_id'),
                'variant'
            )
            ->selectRaw('
                product_skus.*,
                products.*,
                product_skus.id as product_sku_id
            ')
            ->join(
                'product_skus',
                'variant.product_sku_id',
                'product_skus.id'
            )
            ->join(
                'products',
                'product_skus.product_id',
                'products.id'
            )
            ->orderByDesc('variant.count')
            ->first();
    }

    public static function sellingPrice($productSkuId)
    {
        return ProductSku::query()
            ->where('id', $productSkuId)
            ->pluck('selling_price')[0];
    }

    public static function priceAfterDiscount($productId, $productSkuId, $qty = 0)
    {
        $product = Product::where('id', $productId)->first();
        $stockSku = self::getStockBySKU($productSkuId);
        if ($qty == 0 && $stockSku > 0) {
            $qty = $product->minimum_order_qty;
        }
        $sellingPrice = self::sellingPrice($productSkuId);
        foreach ($product->wholeSalers()->get() as $wholeSale) {
            if ($qty >= $wholeSale->min_order_qty && $stockSku > 0) {
                $sellingPrice = $wholeSale->selling_price;
            }
        }
        $discFlash = self::flashDealDiscount($productId, $sellingPrice);
        $flashPrice = $sellingPrice * ($discFlash / 100);

        if ($product->discount_type == "Harga") {
            $discPrice =  $sellingPrice - $product->discount;
        } else {
            $discPrice =  $sellingPrice -
                (
                    $sellingPrice * ($product->discount / 100)
                );
        }

        return $discPrice - $flashPrice;
    }

    public static function productRecomendation($slug = null, $type = null)
    {
        $query = Visit::query()
            ->whereHasMorph('visitable', [Product::class])
            ->withWhereHas('visitable', function ($query) {
                $query->where('status', 1);
            })
            ->groupBy('visitable_id')
            ->orderByRaw('count(distinct ip) DESC')
            ->limit(20)
            ->get();

        return $query;
    }

    public static function countSold($id)
    {
        $query = ProductSku::query()
            ->whereHas('productRequests', function ($q) {
                $q->whereHas('purchaseOrder', function ($qq) {
                    $qq->whereNotIn('order_status', [12, 13, 14]);
                });
            })
            ->where('product_id', $id)
            ->count();

        return $query;
    }

    public static function flashDealDiscount($productId, $sellingPrice)
    {
        $flashDeal = FlashDealProduct::query()
            ->where('product_id', $productId)
            ->whereHas('flashDeal', function ($q) {
                $q->where('start_date', '<=', Carbon::today());
                $q->where('end_date', '>=', Carbon::today());
            })->first();

        $discFlash = 0;

        if ($flashDeal != null) {
            if ($flashDeal->discount_type == "Harga") {
                $discFlash = $flashDeal->discount / $sellingPrice;
            } else {
                $discFlash = $flashDeal->discount;
            }
        }

        return $discFlash;
    }
}
