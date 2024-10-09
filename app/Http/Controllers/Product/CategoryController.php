<?php

namespace App\Http\Controllers\Product;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryImage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $category = Category::with(['parentCategory'])->get();
            return DataTables::of($category)
            ->addIndexColumn()
            ->addColumn('parent', function($category) {
                return $category->parentCategory->name ?? '';
            })
            ->addColumn('status', function($category) {
                return $category->status == 1 ? 'Actived' : 'Deactived';
            })
            ->make(true);
        }
        return view('dashboard.product.category');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('dashboard.product.create.category', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'name'      => 'required|min:5|max:100',
                'slug'      => ['required', Rule::unique(Category::class,'slug')],
                'status'    => 'required',
                'parent_id' => $request->isSubcategory ? 'required' : 'nullable',
                'image'     => $request->isSubcategory ? 'nullable|image|max:2048' : 'required|image|max:2048',
                'status'    => 'required'
            ]);
            $level = 1;
            if($request->parent_id != ""){
                $query = Category::where('id', $request->parent_id)->first();
                $level = $query->depth_level + 1;
            }
            $request->merge(['depth_level' => $level]);
            $input = Helpers::requestExcept($request, ['image', 'isSubcategory']);
            $input['parent_id'] = $request->parent_id ? $request->parent_id : 0;
            $category = Category::create($input);
            if($request->file('image')){
                $path = $request->file('image')->store('categories', 'public');
                CategoryImage::create([
                    'category_id'   => $category->id,
                    'image'         => $path
                ]);
            }
            DB::commit();
            return redirect(url('dashboard/category'))->with('success', 'Category berhasil dibuat');
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::with('parentCategory')->where('id', $id)->first();
        $allCategories = Category::where('id', '<>', $id)->get();
        return view('dashboard.product.edit.category', compact('category', 'allCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'name'      => 'required|min:5|max:100',
                'slug'      => ['required', Rule::unique(Category::class,'slug')->ignore($id)],
                'status'    => 'required',
                'parent_id' => $request->isSubcategory ? 'required' : 'nullable',
                'image'     => 'nullable|image|max:2048',
                'status'    => 'required'
            ]);
            $level = 1;
            if($request->parent_id != ""){
                $query = Category::where('id', $request->parent_id)->first();
                $level = $query->depth_level + 1;
            }
            $request->merge(['depth_level' => $level]);
            $input = Helpers::requestExcept($request, ['image']);
            $input['parent_id'] = $request->parent_id ? $request->parent_id : 0;
            $category = Category::where('id', $id)->first();
            $category->update($input);
            if($request->file('image')){
                Storage::disk('public')->exists($category->categoryImage->image ?? 'x')
                    ?
                Storage::disk('public')->delete($category->categoryImage->image)
                    :
                null;
                $path = $request->file('image')->store('categories', 'public');
                $category->categoryImage->updateOrCreate(
                    ['category_id' => $id],
                    [
                        'category_id'   => $id,
                        'image'         => $path
                    ]
                );
            }
            DB::commit();
            return redirect(url('dashboard/category'))->with('success', 'Category berhasil Diubah');
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
            $category = Category::where('id', $id);
            $rows = $category->first();
            if($rows->categoryImage->image != null) {
                Storage::disk('public')->delete($rows->categoryImage->image);
                $rows->categoryImage->delete();
            }
            $category->delete();
            DB::commit();
            return response()->json(['message' => 'Kategori berhasil dihapus']);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getChildCategory($id)
    {
        $category = Category::where('id', $id)->with('subCategories')->first();
        return response()->json($category->subCategories);
    }
}
