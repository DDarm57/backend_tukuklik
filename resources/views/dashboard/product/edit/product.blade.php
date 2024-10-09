@extends('layouts.dashboard.app', ['title' => 'Ubah Produk'])

@push('styles')
<link href="https://unpkg.com/@yaireo/tagify@4.17.7/dist/tagify.css" rel="stylesheet" type="text/css" />
@endpush

@section('content')
    <div class="pagetitle">
        <h1>{{ $viewMode == "true" ? "Lihat" : "Ubah" }} Produk</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Produk</li>
                <li class="breadcrumb-item"><a href="{{ url('dashboard/Product') }}">List Produk</a></li>
                <li class="breadcrumb-item active">{{ $viewMode == "true" ? "Lihat" : "Ubah" }}</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form id="my-form">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Informasi Produk</h5>
                                    <div class="row">
                                        <div class="form-group col-lg-6">
                                            <label for="inputText" class="col-form-label">Nama Produk</label>
                                            <input type="text" value="{{ $product->product_name }}" name="product_name" class="form-control" placeholder="Nama Produk...">
                                            <span id="message_product_name" class="invalid-feedback" style="display:none">
                                            </span>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="inputText" class="col-form-label">SKU</label>
                                            <input type="text" value="{{ $product->product_type == "1" ? $product->productSkus[0]->sku : '' }}" name="product_sku" class="form-control" placeholder="SKU Number..." {{ $product->product_type == "2" ? 'readonly' : '' }}>
                                            <span id="message_product_sku" class="invalid-feedback" style="display:none">
                                            </span>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="inputText" class="col-form-label">Minimum Order Qty</label>
                                            <input type="text" value="{{ $product->minimum_order_qty }}" name="minimum_order_qty" class="form-control" placeholder="Minimal Order...">          
                                            <span id="message_minimum_order_qty" class="invalid-feedback" style="display:none">
                                            </span>                              
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="inputText" class="col-form-label">Maximum Order Qty</label>
                                            <input type="text" value="{{ $product->max_order_qty }}" name="max_order_qty" class="form-control" placeholder="Maksimal Order...">
                                            <span id="message_max_order_qty" class="invalid-feedback" style="display:none">
                                            </span>
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label for="inputText" class="col-form-label">Deskripsi Produk</label>
                                            <textarea id="description" class="form-control w-100" name="description" rows="100" cols="50">{{ $product->description }}</textarea>
                                            <span id="message_description" class="invalid-feedback" style="display:none">
                                            </span>
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label for="inputText" class="col-form-label">Pilih Merchant / Seller</label>
                                            <select class="selects form-control" name="merchant">
                                                <option></option>
                                                @foreach($merchant as $m)
                                                    <option value="{{ $m->id }}" {{ $product->merchant_id == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                                                @endforeach
                                            </select>
                                            <span id="message_merchant" class="invalid-feedback" style="display:none">
                                            </span>
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label for="inputText" class="col-form-label">Unit Produk</label>
                                            <select class="selects form-control" name="unit_type">
                                                <option></option>
                                                @foreach($unit as $u)
                                                    <option value="{{ $u->id }}" {{ $product->unit_type_id == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                                @endforeach
                                            </select>
                                            <span id="message_unit_type" class="invalid-feedback" style="display:none">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Attributes</h5>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label for="inputText" class="col-form-label">Kategori</label>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <select class="selects form-control cat" id="catFirst" name="categories[]">
                                                        <option></option>
                                                        @foreach($catLvlOne as $cat)
                                                            <option value="{{ $cat->id }}" {{ $categories[0] == $cat->id ? 'selected' : '' }} >{{ $cat->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span id="message_categories.0" class="invalid-feedback" style="display:none">
                                                    </span>
                                                </div>
                                                <div class="col-lg-4">
                                                    <select class="selects form-control cat" name="categories[]" id="catSecond">
                                                        <option></option>
                                                        @foreach($catLvlTwo as $cat)
                                                            <option value="{{ $cat->id }}" {{ ($categories[1] ?? null) == $cat->id ? 'selected' : '' }} >{{ $cat->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-4">
                                                    <select class="selects form-control cat" name="categories[]" id="catThird">
                                                        <option></option>
                                                        @foreach($catLvlThree as $cat)
                                                            <option value="{{ $cat->id }}" {{ $categories[2] ?? null == $cat->id ? 'selected' : '' }} >{{ $cat->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label for="inputText" class="col-form-label">Tags (Comma Separated)</label>
                                            <input value="{{ $tags }}" type="text" name="tags" class="form-control" placeholder="Tag Product..." data-role="tagsinput">
                                            <span id="message_tags" class="invalid-feedback" style="display:none">
                                            </span>
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label for="inputText" class="col-form-label">Varian</label><br>
                                            <select class="selects form-control" id="tipeVarian" name="varian[]">
                                                <option></option>
                                                @foreach($attribute as $attr)
                                                    <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div id="selectVarian" style="{{ count($selectVariants) == 0 ? 'display:none' : '' }}" class="col-lg-12">
                                        @foreach($selectVariants as $key => $variant)
                                            <div class="row rowVarian{{ $key }}">
                                                <div class="form-group col-lg-4">
                                                    <label for="inputText" class="col-form-label">Tipe</label><br>
                                                    <input type="text" class="form-control" value="{{ $variant['name'] }}" readonly>
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <label for="inputText" class="col-form-label">Attribute</label><br>
                                                    <select multiple class="selects form-control" id="attributeVarian{{ $key }}" name="attribute[]">
                                                        @foreach(explode(',', $variant['available']) as $v)
                                                            <option {{ in_array($v, explode(',', $variant['existing'])) ? 'selected' : '' }}>{{ $v }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-2" style="margin-top:35px">
                                                    <a class="btn btn-outline-danger" onclick="deleteVarian({{ $key }})"><i class="bi bi-patch-minus"></i></a>
                                                </div>
                                            </div>
                                        @endforeach
                                        </div>
                                        <div id="tableVarian" style="{{ $product->product_type == "1" ? 'display:none' : '' }}" class="form-group col-lg-12">
                                            <table class="table table-bordered w-100">
                                                <thead>
                                                    @foreach($headVarian as $key => $thVarian) 
                                                        <th id="{{ $key }}">{{ $thVarian['name'] }}</th>
                                                    @endforeach
                                                    <th>SKU</th>
                                                    {{-- <th>Gambar</th> --}}
                                                    <th>Stok</th>
                                                    <th>Harga</th>
                                                </thead>
                                                <tbody>
                                                    @foreach($tableVarian as $tVarian)
                                                        <tr>
                                                            @php 
                                                                $explode = explode(',', $tVarian['variant']);
                                                            @endphp
                                                            @if(count($explode) > 0)
                                                                @foreach($explode as $expAttr)
                                                                <td>{{ $expAttr }}</td>
                                                                @endforeach
                                                            @else 
                                                                <td>{{ $tVarian['varian'] }}</td>
                                                            @endif
                                                            <td><input name="sku_varian[]" class="form-control" value="{{ $tVarian['sku'] }}"></td>
                                                            <td><input name="stok_varian[]" class="form-control" value="{{ $tVarian['product_stock'] }}"></td>
                                                            <td><input id="rupiah" name="harga_varian[]" class="form-control" value="{{ number_format($tVarian['selling_price'],0,'.','.') }}"></td>
                                                            <td style='display:none'>
                                                                <input name='varian_attribute[]' value='{{ $tVarian['variant'] }}' type='hidden'>
                                                                <input name='old_track_sku[]' value='{{ $tVarian['track_sku'] }}' type='hidden'>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @foreach($tableVarian as $tVarian)
                                                 <input id="hSku" name='old_track_sku[]' value='{{ $tVarian['track_sku'] }}' type='hidden'>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Inventaris</h5>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <label for="inputText" class="col-form-label">Dimensi Produk</label>
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <label for="inputText" class="col-form-label">Berat</label>
                                                    <div class="input-group mb-3"> 
                                                        <input value="{{ $product->productSkus[0]->weight }}" type="text" name="weight" placeholder="Berat..." class="form-control"> 
                                                        <span class="input-group-text" id="basic-addon2">gm</span>
                                                        <span id="message_weight" class="invalid-feedback" style="display:none">
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="inputText" class="col-form-label">Panjang</label>
                                                    <div class="input-group mb-3"> 
                                                        <input value="{{ $product->productSkus[0]->length }}" type="text" name="length" placeholder="Panjang..." class="form-control"> 
                                                        <span class="input-group-text" id="basic-addon2">cm</span>
                                                        <span id="message_length" class="invalid-feedback" style="display:none">
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="inputText" class="col-form-label">Lebar</label>
                                                    <div class="input-group mb-3"> 
                                                        <input value="{{ $product->productSkus[0]->breadth }}" type="text" name="breadth" placeholder="Lebar..." class="form-control"> 
                                                        <span class="input-group-text" id="basic-addon2">cm</span>
                                                        <span id="message_breadth" class="invalid-feedback" style="display:none">
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="inputText" class="col-form-label">Tinggi</label>
                                                    <div class="input-group mb-3"> 
                                                        <input value="{{ $product->productSkus[0]->height }}" type="text" name="height" placeholder="Tinggi..." class="form-control"> 
                                                        <span class="input-group-text" id="basic-addon2">cm</span>
                                                        <span id="message_height" class="invalid-feedback" style="display:none">
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Harga & Stok</h5>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <div class="row">
                                                <div class="form-group col-lg-12">
                                                    <label for="inputText" class="col-form-label">Stok</label>
                                                    <input value="{{ $product->product_type == 1 ? $product->productSkus[0]->product_stock : '' }}" type="text" name="product_stock" class="form-control" placeholder="Stok..." {{ $product->product_type == "2" ? 'readonly' : '' }}>
                                                    <span id="message_product_stock" class="invalid-feedback" style="display:none">
                                                    </span>
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <label for="inputText" class="col-form-label">Diskon</label>
                                                    <input value="{{ $product->discount }}" type="text" name="discount" class="form-control" placeholder="Diskon...">
                                                    <span id="message_discount" class="invalid-feedback" style="display:none">
                                                    </span>
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <label for="inputText" class="col-form-label">Tipe Diskon</label>
                                                    <select class="selects form-control" name="discount_type">
                                                        <option></option>
                                                        <option {{ $product->discount_type == 'Harga' ? 'selected' : '' }}>Harga</option>
                                                        <option {{ $product->discount_type == 'Persentase' ? 'selected' : '' }}>Persentase</option>
                                                    </select>
                                                    <span id="message_discount_type" class="invalid-feedback" style="display:none">
                                                    </span>
                                                </div>
                                                <div class="form-group col-lg-12">
                                                    <label for="inputText" class="col-form-label">Harga</label>
                                                    <div class="input-group mb-3"> 
                                                        <span class="input-group-text" id="basic-addon2">Rp</span>
                                                        <input value="{{ $product->product_type == 1 || $product->wholeSalers->count() > 0  ? number_format($product->productSkus[0]->selling_price,0,'.','.') : 0 }}"  type="text" name="selling_price" id="rupiah" placeholder="Harga..." class="form-control" {{ $product->product_type == "2" && $product->wholeSalers->count() == 0 ? 'readonly' : '' }}>
                                                        <span id="message_selling_price" class="invalid-feedback" style="display:none">
                                                        </span>
                                                    </div>
                                                    <div class="form-check"> 
                                                        <input class="form-check-input" type="checkbox" id="gridCheck1" name="has_wholesale" {{ $product->wholeSalers->count() > 0 ? 'checked' : '' }}> 
                                                        <label class="form-check-label" for="gridCheck1">Produk Ini Memiliki Harga Grosir</label>
                                                    </div>
                                                </div>
                                                <div id="buttonWholesale" class="col-lg-12" style="{{ $product->wholeSalers->count() == 0 ? 'display:none' : '' }}">
                                                    <a class="btn btn-outline-danger" id="addWholesale">Tambah</a>
                                                </div>
                                                <div class="col-lg-12" id="wholesale" style="{{ $product->wholeSalers->count() == 0 ? 'display:none' : '' }}">
                                                    <div class="row">
                                                        <div class="col-lg-12" id="infoVarianWholesale" style="display:none">
                                                            <div class="alert alert-info mt-2">
                                                                Semua harga variant otomatis mengikuti harga utama jika produk memiliki harga grosir.
                                                            </div>
                                                        </div>
                                                        @if($product->wholeSalers->count() == 0)
                                                        <div class="form-group col-lg-4">
                                                            <label for="inputText" class="col-form-label">Jumlah Min.</label>
                                                            <div class="input-group mb-3"> 
                                                                <span class="input-group-text" id="basic-addon2">Pcs</span>
                                                                <input type="text" id="rupiah" name="min_wholesale_qty[]" value="0" class="form-control"> 
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-6">
                                                            <label for="inputText" class="col-form-label">Harga</label>
                                                            <div class="input-group mb-3"> 
                                                                <span class="input-group-text" id="basic-addon2">Rp</span>
                                                                <input type="text" id="rupiah" name="wholesale_price[]" value="0" class="form-control"> 
                                                            </div>
                                                        </div>
                                                        @else 
                                                            @foreach($product->wholeSalers as $wholeSale)
                                                            <div class="form-group col-lg-4">
                                                                <label for="inputText" class="col-form-label">Jumlah Min.</label>
                                                                <div class="input-group mb-3"> 
                                                                    <span class="input-group-text" id="basic-addon2">Pcs</span>
                                                                    <input type="text" id="rupiah" name="min_wholesale_qty[]" value="{{ $wholeSale->min_order_qty }}" class="form-control"> 
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-lg-6">
                                                                <label for="inputText" class="col-form-label">Harga</label>
                                                                <div class="input-group mb-3"> 
                                                                    <span class="input-group-text" id="basic-addon2">Rp</span>
                                                                    <input type="text" id="rupiah" value="{{ $wholeSale->selling_price }}" name="wholesale_price[]" value="0" class="form-control"> 
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Proses Pengerjaan</h5>
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <div class="row">
                                                <div class="form-group col-lg-6">
                                                    <label for="inputText" class="col-form-label">Tipe</label>
                                                    <select class="selects form-control" name="stock_type">
                                                        <option></option>
                                                        <option {{ $product->stock_type == "Ready Stock" ? 'selected' : '' }}>Ready Stock</option>
                                                        <option {{ $product->stock_type == "Pre Order" ? 'selected' : '' }}>Pre Order</option>
                                                    </select>
                                                    <span id="message_stock_type" class="invalid-feedback" style="display:none">
                                                    </span>
                                                </div>
                                                <div class="form-group col-lg-6">
                                                    <label for="inputText" class="col-form-label">Estimasi Pengerjaan</label>
                                                    <div class="input-group mb-3"> 
                                                        <input type="number" name="processing_estimation" value="{{ $product->processing_estimation }}" class="form-control">
                                                        <span class="input-group-text" id="basic-addon2">Hari</span>
                                                        <span id="message_processing_estimation" class="invalid-feedback" style="display:none">
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Media</h5>
                                    <span id="file-upload-form" action="{{ url('') }}">
	                                    <input id="file-upload" type="file" />
                                        <label for="file-upload" id="file-drag">
                                            Select a file to upload
                                            <br />OR
                                            <br />Drag a file into this box
                                        </label>	
                                        <div class="progress" style="display:none">
                                            <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                            <div class="info"></div>
                                        </div>
                                        <div class="img-thumbs {{ $product->productPhotos[0] ?? 'img-thumbs-hidden' }}" id="img-preview">
                                            @foreach($product->productPhotos as $photo)
                                                <div class="wrapper-thumb">
                                                    <img src="{{ $photo->media->path }}" class="img-preview-thumb">
                                                    <a class="remove-btn">x</a>
                                                    <input type='hidden' name='media[]' value='{{ $photo->media->id }}'>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div id="message"></div>
                                        <div id="outputFile"></div>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Dokumen Pendukung</h5>
                                    <div class="form-group col-lg-12">
                                        <label for="inputText" class="col-form-label">Spesifikasi Dokumen (PDF)</label>
                                        <input type="file" name="pdf" class="form-control">
                                        @if($product->pdf != '')
                                            <a href="{{ $product->document->path }}" target="_blank"><i class="bi bi-file-earmark-text"></i> {{ $product->document->name }}</a>
                                        @endif
                                        <span id="message_pdf" class="invalid-feedback" style="display:none">
                                        </span>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label for="inputText" class="col-form-label">Video Produk (Youtube)</label>
                                        <input value="{{ $product->video_link }}" type="text" name="video_link" class="form-control" placeholder="Link Video...">
                                        <span id="message_video_link" class="invalid-feedback" style="display:none">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Status</h5>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input form-control" type="checkbox" name="status" id="flexSwitchCheckDefault" value="1" {{ $product->status == "1" ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Actived?</label>
                                        <span id="message_status" class="invalid-feedback" style="display:none">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($viewMode != "true")
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body> p-3">
                            <div class="row">
                                <div class="col-8">
                                    <h5 class="mt-2">Simpan?</h5>
                                </div>
                                <div class="col-sm-12 col-lg-2">
                                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-md w-100"><i class="bi bi-x-circle"></i> Batal</a>
                                </div>
                                <div class="col-sm-12 col-lg-2">
                                    <input type="hidden" value="{{ $product->product_type == "2" ? 'varian' : 'product' }}" name="product_type">
                                    <a onclick="saveForm({{ $product->id }})" class="btn btn-outline-danger btn-md w-100 submit"><i class="bi bi-save"></i> Simpan</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @method('PUT')
        </form>
    </section>
@endsection

@push('scripts')
    @include('dashboard.javascript.js-product')
    <script>
        var formControl = document.querySelectorAll('.form-control');
        formControl.forEach((item, index) => {
            if("{{ $viewMode }}" == "true") {
                formControl[index].setAttribute("disabled", true);
            }
        });
    </script>
@endpush