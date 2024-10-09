<?php

namespace App\Http\Controllers\Product;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Attribute;
use App\Models\AttributeValues;
use App\Models\Category;
use App\Models\Media;
use App\Models\Merchant;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductVariants;
use App\Models\Tag;
use App\Models\Tax;
use App\Models\UnitType;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use App\Traits\GenerateSlug;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{

    use GenerateSlug;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $product = ProductRepository::findAll($request->input('filter'))->get();
            return DataTables::of($product)
            ->addIndexColumn()
            ->addColumn('stock', function($product) {
                $stock = 0;
                foreach($product->productSkus as $sku) {
                    $stock += $sku->product_stock;
                }
                return $stock; 
            })
            ->addColumn('product_type', function($product) {
                return $product->product_type == 1 ? 'Product' : 'Varian';
            })
            ->addColumn('status', function($product) {
                return $product->status ? 'Actived' : 'Deactived';  
            })
            ->addColumn('thumbnail', function($product) {
                return Helpers::image($product->thumbnail_image_source);
            })
            ->make(true);
        }
        return view('dashboard.product.product');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $catLvlOne = ProductRepository::getCategoryByLvl(1);
        $merchant = Merchant::all();
        $attribute = Attribute::all();
        $unit = UnitType::all();
        return view('dashboard.product.create.product', compact('catLvlOne','merchant', 'attribute', 'unit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = Helpers::requestExcept($request);
            $data['slug'] = $this->productSlug($data['product_name']);
            $data['status'] = $request->status ?? 0;

            $categories = $request->input('categories') ?? [];
            $tags = $request->input('tags') ?? [];
            $data['wholesale_price'] = $request->input('wholesale_price') ?? [];
            $medias = $request->input('media') ?? [];

            $product = ProductService::product($request, $data);
            ProductService::category($categories, $product);
            ProductService::tags($tags, $product);
            ProductService::skuVarian($data, $product);
            ProductService::wholesale($data, $product);
            ProductService::media($medias, $product);

            DB::commit();

            Session::flash('success', 'Produk berhasil dibuat');
            return response()->json([
                'status'    => 'success', 
                'data'      => $product->first(),
                'message'   => 'Produk berhasil dibuat'
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if($request->ajax()){
            return response()->json([
                'status'    => 'success',
                'data'      => Product::where('id', $id)->first()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['merchant'] = Merchant::all();
        $data['attribute'] = Attribute::all();
        $data['unit'] = UnitType::all();
        $product = Product::where('id', $id)->first();
        $data['product'] = $product;
        $tags = Collection::make([]);
        $categories = Collection::make([]);
        foreach($product->productTags as $tag) {
            $tags->push($tag->tag->name);
        }
        foreach($product->categoryProduct as $cat) {
            $categories->push($cat->category_id);
        }

        $selectVarian = ProductVariants::query()
                        ->where('product_id', $id)
                        ->join('attribute_values AS a','product_variants.attribute_value_id','=', 'a.id')
                        ->join('attributes AS b', 'a.attribute_id','=', 'b.id')
                        ->join('attribute_values AS c','product_variants.attribute_id','=','c.attribute_id')
                        ->select('b.name', DB::raw("GROUP_CONCAT(DISTINCT a.value, '') AS existing"), DB::raw("GROUP_CONCAT(DISTINCT c.value ORDER BY c.id ASC, '') AS available"))
                        ->groupBy('product_variants.attribute_id');

        $tableVarian = ProductSku::query()
                        ->join('product_variants AS z','z.product_sku_id','=','product_skus.id')
                        ->where('z.product_id', $id)
                        ->join('attribute_values AS a','a.id','=','z.attribute_value_id')
                        ->groupBy('z.product_sku_id')
                        ->select('sku','selling_price','product_stock','track_sku', DB::raw("GROUP_CONCAT(DISTINCT a.value ORDER BY a.id ASC, '') as variant"));
        
        $headVarian = ProductVariants::query()
                        ->join('attributes as b','product_variants.attribute_id','=','b.id')
                        ->where('product_id',$id)
                        ->groupBy('b.id')
                        ->select('b.name');
        
        $data['selectVariants'] = $selectVarian->get()->toArray();
        $data['tableVarian'] = $tableVarian->get()->toArray();
        $data['headVarian'] = $headVarian->get()->toArray();
        $data['catLvlOne'] = ProductRepository::getCategoryByLvl(1);
        $data['catLvlTwo'] = ProductRepository::getCategoryByLvl(2, $categories[0] ?? null);
        $data['catLvlThree'] = ProductRepository::getCategoryByLvl(3, $categories[1] ?? null);
        $tags = $tags->implode(',');
        $data['tags'] = $tags;
        $data['categories'] = $categories;
        $data['viewMode'] = request()->get('viewMode');
        return view('dashboard.product.edit.product', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
         DB::beginTransaction();
        try {
            $data = Helpers::requestExcept($request);
            $data['slug'] = $this->productSlug($data['product_name']);
            $data['status'] = $request->status ?? 0;

            $categories = $request->input('categories') ?? [];
            $tags = $request->input('tags') ?? [];
            $data['wholesale_price'] = $request->input('wholesale_price') ?? [];
            $medias = $request->input('media') ?? [];
            
            $product = ProductService::product($request, $data, $id);

            // $product->categoryProduct()->delete();
            // $product->productTags()->delete();
            // $product->productSkus()->delete();
            // $product->productSkus()->productVariants()->delete();
            // $product->wholeSalers()->delete();
            // $product->productPhotos()->delete();
            
            ProductService::category($categories, $product);
            ProductService::tags($tags, $product);
            ProductService::skuVarian($data, $product);
            ProductService::wholesale($data, $product);
            ProductService::media($medias, $product);

            DB::commit();

            Session::flash('success', 'Produk berhasil Diubah');

            return response()->json([
                'status'    => 'success', 
                'data'      => $product->first(),
                'message'   => 'Produk berhasil Diubah'
            ]);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Product::where('id', $id)->first()->delete();
            DB::commit();
            return response()->json(['message' => 'Produk berhasil dihapus']);
        } catch(Exception $e) {
            throw $e;
        }
    }
}
