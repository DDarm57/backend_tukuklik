@extends('layouts.frontend.app', ['title' => 'Homepage'])

@push('meta')
<meta name="title" content="{{ Helpers::generalSetting()->meta_title }}">
<meta name="description" content="{{ Helpers::generalSetting()->meta_description }}">
<meta name="keywords" content="{{ Helpers::generalSetting()->meta_keywords }}">
<meta name="author" content="{{ Helpers::generalSetting()->meta_description }}">
@endpush

@section('content')
<main class="main">

    {{-- Banner --}}
    <section class="section-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 mt-20 mb-5">
                    <div class="box-swiper swiper-home6">
                        <div class="swiper-container swiper-group-1">
                            <div class="swiper-wrapper">
                                @foreach($banner as $key => $ban)
                                    @if($key+1 == $ban->position)
                                        <div class="swiper-slide">
                                            <div class="banner-big-7">
                                                <img src="{{ Storage::url($ban->slider_image) }}" alt="{{ $ban->name }}">
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="swiper-pagination swiper-pagination-1"></div>
                            <div class="swiper-pagination swiper-pagination-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- End Banner --}}

    {{-- List Category --}}
    <section class="section-box pt-20">
       <div class="container">
           <div class="box-product-category">
               <div class="head-main bd-gray-200">
                   <div class="row">
                       <div class="col-xl-6 col-lg-6">
                           <h4 class="mb-5">Kategori Populer</h4>
                       </div>
                   </div>
               </div>
               <div class="mt-10">
                   <div class="box-swiper js-swiper-9">
                       <div class="swiper-container swiper-group-9 pb-50">
                           <div class="swiper-wrapper">
                            @foreach($categories as $cat)
                               <div class="swiper-slide">
                                   <div class="card-circle">
                                       <div class="card-image">
                                           <div class="inner-image">
                                                <a href="{{ url('products/'.$cat->slug.'/category') }}">
                                                    <img src="{{ Helpers::image($cat->categoryImage->image ?? '') }}">
                                                </a>
                                            </div>
                                       </div>
                                       <div class="card-info"> 
                                            <a class="font-md-bold" href="{{ url('products/'.$cat->slug.'/category') }}">{{ $cat->name }}</a>
                                            <p class="font-xs color-gray-500">{{ $cat->products_count }} Barang</p>
                                       </div>
                                   </div>
                               </div>
                            @endforeach
                           </div>
                           <div class="swiper-pagination swiper-pagination-3 text-center"></div>
                       </div>
                   </div>
               </div>
           </div>
        </div>
   </section>
    {{-- End List Category --}}

    {{-- Flash Deal --}}
    @if(count($flashDeal) > 0)
    <section class="section-box pt-20">
        <div class="container">
            <div class="pb-20 pt-35">
                <img src="{{ Storage::url($flashDeal[0]->flashDeal->banner) }}" class="w-100">
            </div>
            <div class="bg-9 box-bdrd-4 pt-35 pb-35 pl-25 pr-25">
                <div class="head-main">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="box-icon-flash">
                                <h3 class="mb-5 text-white">Promo Terbaru</h3>
                                <p class="font-bold text-white">Ambil Kesempatan Purchase Order Dengan Harga
                                    Promo!
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-6 text-end">
                            <div class="box-all-hurry box-all-hurry-round">
                                <div class="d-inline-block box-text-hurryup">
                                    <span class="font-md-bold color-gray-900">Hurry Up!</span>
                                    <br>
                                    <span class="font-xs color-gray-500">Offer End :</span>
                                </div>
                                <div class="box-count box-count-square hide-period">
                                    <div class="deals-countdown" data-countdown="{{ $flashDeal[0]->flashDeal->end_date. " 23:59:59" }}"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-0">
                    <div class="list-products-5">
                        @foreach($flashDeal as $flash)
                        <div class="card-grid-style-3 hover-show">
                            <div class="card-grid-inner shadow-sm">
                                <div class="tools">
                                    <a class="btn btn-wishlist btn-tooltip mb-10" href="" onclick="addToWishlist()" href="#" aria-label="Tambah Wishlist"></a>
                                </div>
                                <div class="image-box">
                                    <span class="label bg-brand-2">-{{ round($flash->disc,0) }}%</span>
                                    <a href="{{ url('product/'.$flash->slug.'') }}">
                                        <img src="{{ $flash->thumbnail_image_source }}" alt="{{ $flash->product_name }}">
                                    </a>
                                </div>
                                <div class="info-right">
                                    <span class="font-xs color-danger">Min Order {{ $flash->minimum_order_qty. " ". $flash->product->unit_type->name }}</span>
                                    <br>
                                    <a class="color-brand-3 font-md-bold" href="{{ url('product/'.$flash->slug.'') }}">{{ $flash->product_name }}</a>
                                    <div class="rating">
                                        <img src="{{ url('frontend/assets/imgs/template/icons/star.svg') }}" alt="Ecom">
                                        <img src="{{ url('frontend/assets/imgs/template/icons/star.svg') }}" alt="Ecom">
                                        <img src="{{ url('frontend/assets/imgs/template/icons/star.svg') }}" alt="Ecom">
                                        <img src="{{ url('frontend/assets/imgs/template/icons/star.svg') }}" alt="Ecom">
                                        <img src="{{ url('frontend/assets/imgs/template/icons/star.svg') }}" alt="Ecom">
                                        <span class="font-xs color-gray-500"> (65)</span>
                                    </div>
                                    <div class="price-info">
                                        <strong class="font-md-bold color-brand-3 price-main">{{ "Rp. ". number_format($flash->real_price,0,'.','.') }}</strong>
                                        <br/>
                                        <span class="color-gray-500 font-md price-line">{{ "Rp. ". number_format($flash->selling_price,0,'.','.') }}</span>
                                        <br/>
                                        <span class="color-gray-500">{{ $flash->product->merchant->name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    {{-- End Flash Deal --}}

    {{-- Product Recommendation --}}
    <section class="section-box pt-20">
        <div class="container">
            <div class="box-product-category">
                <div class="head-main bd-gray-200">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6">
                            <h4 class="mb-5">Rekomendasi Produk</h4>
                            <p class="font-base color-gray-500">Berdasarkan Pilihan Terbaik</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box-swiper">
                            <div class="swiper-container swiper-group-5">
                                <div class="swiper-wrapper pt-5 mb-20">
                                    @foreach($recommendation as $prods)
                                        <div class="swiper-slide">
                                            <x-product::cart-product
                                                productName="{{ $prods->visitable->product_name }}" 
                                                discPercentage="{{ $prods->visitable->disc_percentage }}"
                                                thumbnail="{{ $prods->visitable->thumbnail_image_source }}"
                                                minimum="{{ $prods->visitable->minimum_order_qty }}"
                                                unit="{{ $prods->visitable->unit_type->name }}"
                                                priceAfterDisc="{{ $prods->visitable->disc_amt }}"
                                                sellingPrice="{{ $prods->visitable->selling_price }}"
                                                merchant="{{ $prods->visitable->merchant->name }}"
                                                slug="{{ $prods->visitable->slug }}"
                                                rating="{{ $prods->visitable->rating }}"
                                                review="{{ $prods->visitable->reviews->count() }}"
                                                stockType="{{ $prods->visitable->stock_type }}"
                                                countSold="{{ $prods->visitable->count_sold }}"
                                            />
                                        </div>
                                    @endforeach
                                </div>
                                <div class="swiper-pagination swiper-pagination-3 text-center"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- End Product Recommendation --}}

    {{-- Product Per Category --}}
    @foreach($productByCategory as $prodCat)
    <section class="section-box pt-20">
        <div class="container">
            <div class="box-product-category">
                <div class="head-main bd-gray-200">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6">
                            <h4 class="mb-5">{{ $prodCat->name }}</h4>
                            <p class="font-base color-gray-500">{{ $prodCat->products_count }} Produk Ditemukan</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="card-grid-style-1 card-ads-2">
                            <div class="card-grid-inner text-center">
                                <div class="info-right">
                                    <span class="font-16 color-white text-uppercase"> Kategori Terbaik</span>
                                    <br>
                                    <h4 class="color-white font-32 mt-15 mb-15">{{ $prodCat->name }}</h4>
                                </div>
                                <div class="mt-30">
                                    <a class="btn btn-brand-3 btn-arrow-right" href="{{ url('products/'.$prodCat->slug.'/category') }}">Lihat Semua</a>
                                </div>
                                <div class="image-box">
                                    <a href="{{ url('products/'.$prodCat->slug.'/category') }}">
                                        <img src="{{ Helpers::image($prodCat->categoryImage->image ?? '') }}" alt="{{ $prodCat->name }}">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="box-swiper">
                            <div class="swiper-container swiper-group-4">
                                <div class="swiper-wrapper pt-5 mb-20">
                                    @foreach($prodCat->products()->where('status', 1)->limit(20)->get() as $prods)
                                        <div class="swiper-slide">
                                            <x-product::cart-product
                                                productName="{{ $prods->product_name }}" 
                                                discPercentage="{{ $prods->disc_percentage }}"
                                                thumbnail="{{ $prods->thumbnail_image_source }}"
                                                minimum="{{ $prods->minimum_order_qty }}"
                                                unit="{{ $prods->unit_type->name }}"
                                                priceAfterDisc="{{ $prods->disc_amt }}"
                                                sellingPrice="{{ $prods->selling_price }}"
                                                merchant="{{ $prods->merchant->name }}"
                                                slug="{{ $prods->slug }}"
                                                rating="{{ $prods->rating }}"
                                                review="{{ $prods->reviews->count() }}"
                                                stockType="{{ $prods->stock_type }}"
                                                countSold="{{ $prods->count_sold }}"
                                            />
                                        </div>
                                    @endforeach
                                </div>
                                <div class="swiper-pagination swiper-pagination-3 text-center"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endforeach
    {{-- End Product Per Category --}}

    {{-- All Products --}}
    <section class="section-box pt-20 mb-20">
        <div class="container">
            <div class="box-product-category">
                <div class="head-main bd-gray-200">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6">
                            <h4 class="mb-5">Produk Terbaru</h4>
                            <p class="font-base color-gray-500 page-product"></p>
                        </div>
                    </div>
                </div>
                <div class="list-products-5" id="products">
                    {{-- All Products Here --}}
                </div>
                <div class="loading text-center" style="display:none">
                    <img src="{{ url('images/loading2.gif') }}" style='height:20px'>
                </div>
            </div>
        </div>
    </section>
    {{-- End All Products --}}

</main>
@endsection

@push('scripts')
    @include('javascript.js-product')
@endpush