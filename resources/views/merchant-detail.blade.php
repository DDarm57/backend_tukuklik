@extends('layouts.frontend.app', ['title' => 'Info Penjual'])

@section('content')
<main class="main">
    <div class="section-box">
        <div class="breadcrumbs-div">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a class="font-xs color-brand-4" href="{{ url('') }}">Home</a></li>
                    <li><a class="font-xs color-brand-4">Penjual</a></li>
                    <li><a class="active font-xs color-gray-500">{{ $merchant->name }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <section class="section-box shop-template mt-30">
    <div class="container">
        <div class="d-flex box-banner-vendor">
            <div class="vendor-left">
                <div class="banner-vendor">
                    <img src="{{ url('frontend/assets/imgs/page/vendor/featured.png') }}" alt="Ecom">
                    <div class="d-flex box-info-vendor">
                        <div class="avarta">
                            <img class="mb-5" src="{{ Helpers::photoProfile($merchant->photo) }}" alt="Ecom" style="height:60px;">
                            <a class="btn btn-buy font-xs" href="{{ url('products') }}">{{ $merchant->products_count }} Produk</a>
                        </div>
                        <div class="info-vendor">
                            <h4 class="mb-5">{{ $merchant->name }}</h4>
                            <span class="font-xs color-brand-3 mr-20">{{ $merchant->is_pkp == "Y" ? "Penjual Kena Pajak" : "Penjual Tidak Kena Pajak" }}</span>
                            <x-product::rating
                                star="{{ $merchantRating }}"
                                review="{{ $countMerchantRating }}"
                            />
                        </div>
                        <div class="vendor-contact">
                            <div class="row">
                                <div class="col-xl-7 col-lg-12">
                                    <div class="d-inline-block font-md color-gray-500 location mb-10">
                                        {{ $merchantAddress }}
                                    </div>
                                </div>
                                <div class="col-xl-5 col-lg-12">
                                    <div class="d-inline-block font-md color-gray-500 phone">
                                        {{ $merchant->phone ?? 'No Telpon Belum Ditambahkan' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="vendor-right">
                <div class="box-featured-product">
                    <div class="item-featured">
                        <div class="featured-icon">
                            <img src="{{ url('frontend/assets/imgs/page/product/delivery.svg') }}" alt="Ecom"></div>
                        <div class="featured-info">
                            <span class="font-sm-bold color-gray-1000">Free Delivery</span>
                            <p class="font-sm color-gray-500 font-medium">From all orders over $10</p>
                        </div>
                    </div>
                    <div class="item-featured">
                        <div class="featured-icon">
                            <img src="{{ url('frontend/assets/imgs/page/product/support.svg') }}" alt="Ecom">
                        </div>
                        <div class="featured-info">
                            <span class="font-sm-bold color-gray-1000">Support 24/7</span>
                            <p class="font-sm color-gray-500 font-medium">Shop with an expert</p>
                        </div>
                    </div>
                    <div class="item-featured">
                        <div class="featured-icon">
                            <img src="{{ url('frontend/assets/imgs/page/product/return.svg') }}" alt="Ecom">
                        </div>
                        <div class="featured-info">
                            <span class="font-sm-bold color-gray-1000">Return & Refund</span>
                            <p class="font-sm color-gray-500 font-medium">Free return over $200</p>
                        </div>
                    </div>
                    <div class="item-featured">
                        <div class="featured-icon">
                            <img src="{{ url('frontend/assets/imgs/page/product/payment.svg') }}" alt="Ecom">
                        </div>
                        <div class="featured-info">
                            <span class="font-sm-bold color-gray-1000">Secure payment</span>
                            <p class="font-sm color-gray-500 font-medium">100% Protected</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="border-bottom mb-20 border-vendor"></div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="box-filters mt-0 pb-5 border-bottom">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 mb-10 text-lg-end text-center">
                                <span class="page-product font-sm color-gray-900 font-medium border-1-right span">
                                    
                                </span>
                                <div class="d-inline-block"><span class="font-sm color-gray-500 font-medium">Sort by:</span>
                                    <div class="dropdown dropdown-sort border-1-right">
                                        <button class="btn dropdown-toggle font-sm color-gray-900 font-medium" id="dropdownSort" type="button" data-bs-toggle="dropdown" aria-expanded="false">Latest products</button>
                                        <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="dropdownSort" style="margin: 0px;">
                                            <li><a class="dropdown-item active" href="#">Latest products</a></li>
                                            <li><a class="dropdown-item" href="#">Oldest products</a></li>
                                            <li><a class="dropdown-item" href="#">Comments products</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="d-inline-block"><span class="font-sm color-gray-500 font-medium">Show</span>
                                    <div class="dropdown dropdown-sort border-1-right">
                                        <button class="btn dropdown-toggle font-sm color-gray-900 font-medium" id="dropdownSort2" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static"><span>30 items</span></button>
                                        <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="dropdownSort2">
                                            <li><a class="dropdown-item active" href="#">30 items</a></li>
                                            <li><a class="dropdown-item" href="#">50 items</a></li>
                                            <li><a class="dropdown-item" href="#">100 items</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="d-inline-block">
                                    <a class="view-type-grid mr-5 active" href="shop-grid.html"></a>
                                    <a class="view-type-list" href="shop-list.html"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="list-products-5 mt-20" id="products">
                    </div>
                    <div class="loading text-center" style="display:none">
                        <img src="{{ url('images/loading2.gif') }}" style='height:20px'>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')

    <script>
        var merchant = ["{{ $merchant->id }}"];
    </script>

    @include('javascript.js-product')
@endpush