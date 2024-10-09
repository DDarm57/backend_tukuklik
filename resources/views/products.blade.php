@extends('layouts.frontend.app', ['title' => $title])

@section('content')
<main class="main">
    <div class="section-box">
        <div class="breadcrumbs-div">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a class="font-xs color-gray-1000" href="{{ url('') }}">Homepage</a></li>
                    <li><a class="font-xs color-gray-500" href="shop-grid.html">{{ $title }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="section-box shop-template mt-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 order-first order-lg-last">
                    <div class="box-filters mt-0 pb-5 border-bottom">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 mb-10 text-lg-end text-center">
                                <span class="font-sm color-gray-900 font-medium border-1-right span page-product"></span>
                                <div class="d-inline-block"><span class="font-sm color-gray-500 font-medium">Show</span>
                                    <div class="dropdown dropdown-sort border-1-right">
                                        <button class="btn dropdown-toggle font-sm color-gray-900 font-medium" id="dropdownSort2" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static"><span>Paling Update</span></button>
                                        <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="dropdownSort2">
                                            <li><a class="dropdown-item active" href="#">Paling Update</a></li>
                                            <li><a class="dropdown-item" href="#">Harga Termurah</a></li>
                                            <li><a class="dropdown-item" href="#">Harga Termahal</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="d-inline-block"><span class="font-sm color-gray-500 font-medium">Show</span>
                                    <div class="dropdown dropdown-sort border-1-right">
                                        <button class="btn dropdown-toggle font-sm color-gray-900 font-medium" id="dropdownSort2" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static"><span>15 Produk</span></button>
                                        <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="dropdownSort2">
                                            <li><a class="dropdown-item active" href="#">15 Produk</a></li>
                                            <li><a class="dropdown-item" href="#">30 Produk</a></li>
                                            <li><a class="dropdown-item" href="#">45 Produk</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="modul" value="products">
                    <div class="row mt-20" id="products">
                        
                    </div>
                    <div class="seeMore text-center mb-4">
                        <button class="btn btn-outline-danger">Lihat Lebih Banyak</button>
                    </div>
                    <div class="loading text-center" style="display:none">
                        <img src="{{ url('images/loading2.gif') }}" style='height:20px'>
                    </div>
                </div>
                <div class="col-lg-3 order-last order-lg-first">
                    <div class="sidebar-border mb-0">
                        <div class="sidebar-head">
                            <h6 class="color-gray-900">Kategori Produk</h6>
                        </div>
                        <div class="sidebar-content">
                            <ul class="list-nav-arrow">
                                @foreach($catOrTag as $cat)
                                    <li class="active">
                                        <a class="{{ $slug == $cat->slug ? "text-danger" : "" }}" href="{{ url('products/'.$cat->slug.'/category') }}">
                                            {{ $cat->name }}
                                            <span class="number">{{ $cat->products_count }}</span>
                                        </a>
                                    </li>
                                    @foreach($cat->subCategories()->get() as $subCat)
                                    <li class="ml-15">
                                        <a class="{{ $slug == $subCat->slug ? "text-danger" : "" }}" href="{{ url('products/'.$subCat->slug.'/category') }}">
                                            {{ $subCat->name }}
                                            <span class="number">{{ Helpers::countCat($subCat->id)}}</span>
                                        </a>
                                    </li>
                                    @endforeach
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="sidebar-border mb-40">
                        <div class="sidebar-head">
                            <h6 class="color-gray-900">Filter Produk</h6>
                        </div>
                        <div class="sidebar-content">
                            <h6 class="color-gray-900 mt-10 mb-10">Harga</h6>
                            <div class="box-slider-range mt-20 mb-15">
                                <div class="row mb-20">
                                    <div class="col-sm-12">
                                        <div id="slider-range"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="lb-slider font-sm color-gray-500">Rentang Harga:</label><br/>
                                        <span class="min-value-money font-sm color-gray-1000">
                                        </span>
                                        <label class="lb-slider font-sm font-medium color-gray-1000">
                                        </label>
                                        - 
                                        <span class="max-value-money font-sm font-medium color-gray-1000">
                                        </span>
                                    </div>
                                    <div class="col-lg-12">
                                        <input class="form-control min-value" type="hidden" name="min-value" value="">
                                        <input class="form-control max-value" type="hidden" name="max-value" value="">
                                    </div>
                                </div>
                            </div>
                            <h6 class="color-gray-900 mt-20 mb-10">Tipe</h6>
                            <ul class="list-checkbox">
                                <li>
                                    <label class="cb-container">
                                        <input type="checkbox" name="stockType" value="Pre Order">
                                        <span class="text-small">Pre Order</span><span class="checkmark"></span>
                                    </label>
                                </li>
                                <li>
                                    <label class="cb-container">
                                        <input type="checkbox" name="stockType" value="Ready Stock">
                                        <span class="text-small">Ready Stok</span>
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                            </ul>
                            <h6 class="color-gray-900 mt-20 mb-10">Status Pajak Penjual</h6>
                            <ul class="list-checkbox">
                                <li>
                                    <label class="cb-container">
                                        <input type="checkbox" name="taxStatus" value="Y">
                                        <span class="text-small">Penjual Kena Pajak</span><span class="checkmark"></span>
                                    </label>
                                </li>
                                <li>
                                    <label class="cb-container">
                                        <input type="checkbox" name="taxStatus" value="T">
                                        <span class="text-small">Penjual Tidak Kena Pajak</span>
                                        <span class="checkmark"></span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="box-slider-item mb-30">
                        <div class="head pb-15 border-brand-2">
                            <h5 class="color-gray-900">Paling Dicari</h5>
                        </div>
                        <div class="content-slider">
                            <div class="box-swiper slide-shop">
                                <div class="swiper-container swiper-best-seller">
                                    <div class="swiper-wrapper pt-5">
                                        @foreach($recommendation as $recom)
                                        <div class="swiper-slide">
                                            <div class="card-grid-style-2 card-grid-none-border border-bottom mb-10">
                                                <div class="image-box">
                                                    <a href="shop-single-product-3.html">
                                                        <img src="{{ Helpers::image($recom->visitable->thumbnail_image_source) }}">
                                                    </a>
                                                </div>
                                                <div class="info-right">
                                                    <a class="color-brand-3 font-xs-bold" href="shop-single-product-3.html">
                                                        {{ $recom->visitable->product_name }}
                                                    </a>
                                                    <x-product::rating
                                                        star="{{ $recom->visitable->rating }}"
                                                        review="{{ $recom->visitable->reviews->count() }}"
                                                    />
                                                    <div class="price-info">
                                                        <strong class="font-md-bold color-brand-3 price-main">{{ "Rp. ". number_format($recom->visitable->disc_amt,0,'.','.') }}</strong>
                                                        @if($recom->disc_percentage > 0)
                                                            <span class="label-danger">{{ $recom->visitable->disc_percentage }}%</span>
                                                            <span class="color-gray-500 font-sm price-line">{{ $recom->visitable->selling_price }}</span>
                                                        @endif
                                                        <br/>
                                                        <span class="color-gray-500 font-xs">{{ $recom->visitable->merchant->name }} - Terjual {{ $recom->visitable->count_sold }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div
                                    class="swiper-button-next swiper-button-next-style-2 swiper-button-next-bestseller">
                                </div>
                                <div
                                    class="swiper-button-prev swiper-button-prev-style-2 swiper-button-prev-bestseller">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-slider-item">
                        <div class="head pb-15 border-brand-2">
                            <h5 class="color-gray-900">Tag Produk</h5>
                        </div>
                        <div class="content-slider mb-50">
                            @foreach($tags as $tag)
                                <a class="btn btn-border mr-5" href="{{ url('products/'.$tag->tag->name.'/tag') }}">{{ $tag->tag->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
    <script>
        $(".noUi-handle").on("click", function () {
            $(this).width(50);
        });
        var rangeSlider = document.getElementById("slider-range");
        var rangeSlider2 = $("#slider-range");
        if (rangeSlider2.length > 0) {
            var moneyFormat = wNumb({
                decimals: 0,
                thousand: ".",
                prefix: "Rp. "
            });
            noUiSlider.create(rangeSlider, {
                start: [{{ $minPrice }}, {{ $maxPrice }}],
                step: 1,
                range: {
                    min: [{{ $minPrice }}],
                    max: [{{ $maxPrice }}]
                },
                format: moneyFormat,
                connect: true
            });

            // Set visual min and max values and also update value hidden form inputs
            rangeSlider.noUiSlider.on("update", function (values, handle) {
                $(".min-value-money").html(values[0]);
                $(".max-value-money").html(values[1]);
                $(".min-value").val(moneyFormat.from(values[0]));
                $(".max-value").val(moneyFormat.from(values[1]));
            });

            rangeSlider.noUiSlider.on("change", function (values, handle) {
                if(values[0] || values[1]){
                    resetWithFilter();
                }
            });
        }

        $("[type=checkbox]").change(function() {
            resetWithFilter();
        });

        resetWithFilter = () => {
            page = 2;
            $("#products").html("");
            callProducts();
        }

    </script>
    @include('javascript.js-product')
@endpush