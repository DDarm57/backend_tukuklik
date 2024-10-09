<?php

namespace App\Http\Controllers\Product;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Media;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MediaController extends Controller
{
    protected function mediaRules($mediaType)
    {
        switch($mediaType) {
            case 'product_image' : 
                return 'image';
            break;
            case 'product_document' && 'documents' :
                return 'mimes:pdf,csv,xls,xlsx,jpg,jpeg,png';
            break; 
        }
    }

    public function store(Request $request)
    {
        $isMultipleFile = is_array($request->file('file'));

        $this->validate($request, 
            [
                "allowed_mimes" => ["nullable", "array"],
                "max_size" => ["nullable", "integer", "min:0"],
                "media_type"  => ["required", Rule::in(['product_image','product_document','documents'])],
                // "disk" => ['required'],
                "path" => ["required", "string"],
                ($isMultipleFile ? "file.*" : "file") => [
                    "required", $this->mediaRules($request->media_type),"max:2048"
                ]
            ]
        );

        try {
            DB::beginTransaction();

            $disk = "public";
            $path = $request->get("path");

            $files = Helpers::arrStrict($request->file('file'));

            $data = Collection::make([]);

            foreach ($files as $index => $file) {
                $fileName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $encodedName = Carbon::now()->format("Y_m_d_his_") . strtoupper(Str::random());

                if ($extension) {
                    $encodedName .= "." . $extension;
                }
                
                $path = $file->storeAs($path, $encodedName, [
                    "disk" => $disk,
                ]);
                
                $data->push(Media::create([
                    "name" => $fileName,
                    "encoded_name" => $encodedName,
                    "size" => $file->getSize(),
                    "extension" => $extension,
                    "path" => Storage::url($path),
                    "disk" => $disk,
                ]));
            }

            DB::commit();

            if ($data->count() == 1) {
                return response()->json([
                    'data'      => $data->first(),
                    'message'   => 'success'
                ]);
            }
            return response()->json([
                'data' => '',
                'message' => 'failed'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy(Request $request){
        $path = str_replace('/storage','', $request->path);
        $media = Media::where('path', $request->path);
        $id = $media->first()->id;
        $media->delete();
        Storage::disk('public')->delete($path);
        return response()->json(['message' => 'Success', 'id' => $id]);
    }

}
