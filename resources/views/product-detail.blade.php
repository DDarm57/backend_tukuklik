@extends('layouts.frontend.app', ['title' => 'Detil Produk'])

@push('meta')
<meta name="title" content="{{ $product->product_name }}">
<meta name="description" content="{{ $product->description }}">
<meta name="keywords" content="{{ $tags }}">
<meta name="author" content="{{ $product->merchant->name }}">
@endpush

@section('content')
<main class="main">
    <div class="section-box">
        <div class="breadcrumbs-div">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a class="font-xs color-gray-1000" href="{{ url('') }}">Home</a></li>
                    @foreach($product->categories()->get() as $cat)
                        <li><a class="font-xs color-gray-500" href="{{ url('products/'.$cat->slug.'/category') }}">{{ $cat->name }}</a></li>
                    @endforeach
                    <li><a class="font-xs color-gray-500">{{ $product->product_name }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <section class="section-box shop-template">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="gallery-image">
                        <div class="galleries">
                            <div class="detail-gallery">
                                @if($skuPrimary->product->disc_percentage > 0)
                                    <label class="label">{{ $skuPrimary->product->disc_percentage }}%</label>
                                @endif
                                <div class="product-image-slider">
                                    @foreach($product->productPhotos()->get() as $photo)
                                    <figure class="border-radius-10">
                                        <img src="{{ Helpers::image($photo->media->path) }}"alt="product image">
                                    </figure>
                                    @endforeach
                                </div>
                            </div>
                            <div class="slider-nav-thumbnails">
                                @foreach($product->productPhotos()->get() as $photo)
                                <div>
                                    <div class="item-thumb">
                                        <img src="{{ Helpers::image($photo->media->path) }}" alt="product image">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <h3 class="color-brand-3 mb-10x">{{ $product->product_name }}</h3>
                    <div class="row align-items-center">
                        <div class="col-lg-4 col-md-4 col-sm-3 mb-mobile">
                            <span class="bytext color-gray-500 font-xs font-medium">Penjual</span>
                            <a class="byAUthor color-gray-900 font-xs font-medium" href="{{ url('merchant/'.$product->merchant->id.'') }}"> {{ $product->merchant->name }}</a>
                            <div class="mt-10">
                                <x-product::rating 
                                    star="{{ $rating }}"
                                    review="{{ $product->reviews()->count() }}"
                                />
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-9 text-start text-sm-end">
                            <a class="mr-20" href="#" onclick="addToWishlist()">
                                <span class="btn btn-wishlist mr-5 opacity-100 transform-none"></span>
                                <span id="addWishlist" onclick="addWishlist()" class="font-md color-gray-900">Tambah Ke Wishlist</span>
                            </a>
                        </div>
                    </div>
                    <div class="border-bottom pt-10 mb-20"></div>
                    <div class="row">
                        <div class="col-lg-7">
                            <span id="stockStatus" class="btn {{ $stock == 0 ? "btn-out-stock" : 'btn-in-stock' }} font-sm-bold mb-15">
                                {{ $stock > 0 ? "In Stock": "Out Of Stock" }}
                            </span>
                            <div class="box-product-price">
                                <input type="hidden" id="defaultPrice" value="{{ $skuPrimary->product->disc_amt }}">
                                <h3 id="price" class="color-brand-3 price-main d-inline-block mr-10">
                                    Rp. {{ number_format($skuPrimary->product->disc_amt,0,'.','.') }}
                                </h3>
                                @if($skuPrimary->product->disc_percentage > 0)
                                    <span class="color-gray-500 price-line font-xl line-througt" id="sellingPrice">Rp. {{ number_format($skuPrimary->selling_price,0,'.','.') }}</span>
                                @endif
                            </div>
                            <div class="product-description color-gray-900 pt-20 mb-30">
                                {!! $product->description !!}
                                @if($product->video_link != '')
                                    <iframe class="pt-20" width="420" height="315" src="{{ $product->video_link }}"></iframe>
                                @endif
                            </div>
                            <div class="box-progress-product mt-15 mb-20">
                                <table class="table table-striped">
                                    <tr>
                                        <td>Stok</td>
                                        <td id="stock">{{ $stock }}</td>
                                    </tr>
                                    <tr>
                                        <td>Minimal Pembelian</td>
                                        <td>{{ $product->minimum_order_qty }} {{ $product->unit_type->name }}</td>
                                    <tr>
                                        <td>Tipe</td>
                                        <td>
                                            <span class="bg-color-danger p-1 color-white">
                                                {{ $product->stock_type }} 
                                            </span>
                                            <span class="ml-5">(Estimasi Proses {{ $product->processing_estimation }} Hari)</span>
                                        </td>
                                    </tr>
                                    @if($product->pdf != '')
                                    <tr>
                                        <td>Dokumen</td>
                                        <td>
                                            <a href="{{ $product->document->path }}" target="_blank"><i class="bi bi-file-earmark-text"></i>Link</a>
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                            @if($product->wholeSalers->count() > 0)
                            <div class="col-lg-12 mt-20 box-border-product">
                                <h6 class="mb-2">Harga Grosir</h6>
                                @foreach($product->wholeSalers()->get() as $wholeSale)
                                    <span class="font-sm font-medium color-gray-900">
                                        Minimal {{ $wholeSale->min_order_qty }} {{ $product->unit_type->name }} Rp. {{ number_format($wholeSale->selling_price,0,'.','.') }}
                                    </span><br/>
                                @endforeach
                            </div>
                            @endif
                            <div class="border-bottom mt-20 mb-20"></div>
                            <div class="info-product">
                                <div class="row">
                                    <div class="col-lg-7 col-md-7">
                                        <span class="font-sm font-medium color-gray-900">
                                            SKU
                                            <span class="color-brand-4" id="sku"> {{ $skuPrimary->sku }}</span>
                                            <br>
                                            Kategori
                                            <span class="color-brand-4"> {{ $categories }}</span>
                                            <br>
                                            Tags 
                                            <span  class="color-brand-4">{{ $tags }}</span>
                                        </span>
                                    </div>
                                    <div class="col-lg-5 col-md-5">
                                        <span class="font-sm font-medium color-gray-900">
                                            Berat
                                            <span class="color-brand-4"> {{ $skuPrimary->weight. " Gram" }}</span>
                                            <br>
                                            Dimensi
                                            <span class="color-brand-4"> 
                                                {{ $skuPrimary->length }}
                                                x
                                                {{ $skuPrimary->breadth }}
                                                x
                                                {{ $skuPrimary->height }}
                                            </span>
                                            <br>
                                            Satuan Unit
                                            <span class="color-brand-4"> 
                                                {{ $product->unit_type->name }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="col-lg-12">
                                <div class="box-border-product">
                                    <div class="box-product-style-size">
                                        <div class="row">
                                            @foreach($attribute as $attr)
                                            <div class="col-lg-12 mb-20">
                                                <p class="font-sm color-gray-900">
                                                    {{ $attr['attribute'] }}:
                                                    <span class="color-brand-4 {{ $attr['attribute'] }}">{{ $attr['primary'] }}</span>
                                                </p>
                                                <ul class="list-styles">
                                                    @foreach($attr['value'] as $value)
                                                        <li id="{{ $attr['attribute'] }}" class="{{ $value['is_primary'] == true ? 'active' : '' }}" title="{{ $value['name'] }}">{{ $value['name'] }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="buy-product mt-10 d-flex">
                                        <div class="font-sm text-quantity">Quantity</div>
                                        <div class="box-quantity">
                                            <div class="input-quantity">
                                                <input id="qty" class="font-xl color-brand-3" type="text" value="{{ $product->minimum_order_qty }}">
                                                <span class="minus-cart"></span>
                                                <span class="plus-cart"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="button-buy mt-15">
                                        <button id="addCart" class="btn btn-cart mb-15" onclick="addToCart()">Tambah Kedalam Keranjang</button>
                                        <p>
                                            Subtotal 
                                            <span class="color-brand-3 font-bold" id="subTotal">
                                                Rp. {{ number_format($skuPrimary->product->disc_amt * $product->minimum_order_qty,0,'.','.') }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-20">
                                <div class="box-border-product">
                                <div class="vendor-logo d-flex">
                                    <img style="height:50px" src="{{ Helpers::photoProfile($product->merchant->photo) }}" alt="{{ $product->merchant->name }}">
                                    <div class="vendor-name ml-15">
                                        <h6><a class="color-brand-4" href="{{ url('merchant/'.$product->merchant->id.'') }}">{{ $product->merchant->name }}</a></h6>
                                        <div class="product-rate-cover text-end">
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width: {{ $merchantRating * 20 }}%"></div>
                                            </div>
                                            <span class="font-small ml-5 text-muted"> ({{ $countMerchantRating }} ulasan)</span>
                                        </div>
                                        <span class="font-small text-muted">
                                            <a class="color-brand-4" href="{{ url('chat/'.$product->merchant->pic->id.'') }}" target="_blank">
                                                <i class="bi bi-chat-dots"></i> Kirim Pesan
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                <ul class="contact-infor mt-10">
                                    <li>
                                        <strong class="ml-4">
                                        <i class="bi bi-shop-window"></i>
                                        <span class="ml-8">Alamat</span>
                                        </strong>
                                        <span>{{ $merchantAddress }}</span>
                                    </li>
                                    <li>
                                        <strong class="ml-4">
                                        <i class="bi bi-shop"></i>
                                        <span class="ml-8">Kontak Penjual</span>
                                        </strong>
                                        <span>{{ $product->merchant->phone }}</span>
                                    </li>
                                    <li>
                                        <strong class="ml-4">
                                        <i class="bi bi-info-circle"></i>
                                        <span class="ml-8">Penjual Kena Pajak?</span>
                                        </strong>
                                        <span>{{ $product->merchant->is_pkp == "Y" ? "Ya" : "Tidak"}}</span>
                                    </li>
                                    <li>
                                        <strong class="ml-4">
                                        <i class="bi bi-check2-square"></i>
                                        <span class="ml-8">Status</span>
                                        </strong>
                                        <span>{{ $product->merchant->status == "1" ? "Aktif" : "Non Aktif"}}</span>
                                    </li>
                                </ul>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-bottom pt-30"></div>
        </div>
    </section>
    <section class="section-box shop-template">
        <div class="container">
            <div class="pt-10 mb-10">
                <ul class="nav nav-tabs nav-tabs-product" role="tablist">
                    <li>
                        <a class="active color-brand-4" href="#tab-reviews" data-bs-toggle="tab" role="tab" aria-controls="tab-reviews" aria-selected="true">
                            Ulasan ({{ $product->reviews->count() }})
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="tab-reviews" role="tabpanel" aria-labelledby="tab-reviews">
                        <div class="comments-area">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h4 class="mb-30 title-question color-brand-3">Ulasan Oleh Pembeli</h4>
                                    <div class="comment-list row">
                                        @if($product->reviews->count() == 0)
                                            <p class="text-center text-muted">Review Tidak Ditemukan</p>
                                        @endif
                                        @foreach($product->reviews()->get() as $review)
                                        <div class="col-lg-12">
                                            <div class="single-comment hover-up">
                                                <div class="row">
                                                    <div class="col-lg-2">
                                                        <div class="thumb text-center">
                                                            <img src="{{ Helpers::photoProfile($review->user->photo) }}" alt="Profile">
                                                            <a class="font-heading text-brand" href="#">{{ $review->user->name }}</a>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-10">
                                                        <div class="d-flex justify-content-between mb-10">
                                                            <div class="d-flex align-items-center">
                                                                <span class="font-xs color-gray-700">
                                                                    {{ date('d F Y H:i:s', strtotime($review->created_at)) }}
                                                                </span>
                                                            </div>
                                                            <div class="product-rate d-inline-block">
                                                                <div class="product-rating" style="width: {{ $review->rating * 20 }}%"></div>
                                                            </div>
                                                        </div>
                                                        <p class="mb-10 font-sm color-gray-900">
                                                            {{ $review->comment }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <h4 class="mb-30 title-question color-brand-4">Kategori Ulasan</h4>
                                    <div class="d-flex mb-30">
                                        <div class="product-rate d-inline-block mr-15">
                                            <div class="product-rating" style="width: {{ $rating * 20 }}%"></div>
                                        </div>
                                        <h6>{{ $rating }} dari 5</h6>
                                    </div>
                                    @foreach($catReview as $cr)
                                    <div class="progress"><span>{{ $cr['star'] }} star</span>
                                        <div class="progress-bar" role="progressbar" style="width: {{ $cr['calculate'] }}%" aria-valuenow="{{ $cr['calculate'] }}" aria-valuemin="0" aria-valuemax="100">{{ $cr['calculate'] }}%</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-bottom pt-30 mb-50"></div>
                        <h4 class="color-brand-3">Produk Terkait</h4>
                        @if(count($references) == 0)
                            <p class="mt-20 mb-20 text-left">Produk Pilihan Tidak Tersedia</p>
                        @endif
                        <div class="list-products-5 mt-20 mb-40">
                            @foreach($references as $prods)
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
                            @endforeach
                        </div>
                        <h4 class="color-brand-3">Yang Baru Anda Lihat</h4>
                        @if(count($newProductVisitors) == 0)
                            <p class="mt-20 mb-20 text-left">Produk Baru Dilihat Kosong</p>
                        @endif
                        <div class="row mt-40 mb-40">
                            @foreach($newProductVisitors as $prodVisit)
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="card-grid-style-2 card-grid-none-border hover-up shadow-sm">
                                    <div class="image-box">
                                        <a href="{{ url('product/'.$prodVisit->visitable->slug.'') }}">
                                            <img src="{{ Helpers::image($prodVisit->visitable->thumbnail_image_source) }}" alt="{{ $prodVisit->product_name }}">
                                        </a>
                                    </div>
                                    <div class="info-right">
                                        <span class="font-xs color-gray-500">{{ $prodVisit->visitable->merchant->name }}</span>
                                        <br>
                                        <a class="color-brand-3 font-xs-bold" href="{{ url('product/'.$prodVisit->visitable->slug.'') }}">
                                            {{ $prodVisit->visitable->product_name }}
                                        </a>
                                        <x-product::rating
                                            star="{{ $prodVisit->visitable->rating }}"
                                            review="{{ $prodVisit->visitable->reviews->count() }}"
                                        />
                                        <div class="price-info">
                                            <strong class="font-md-bold color-brand-3 price-main">
                                                Rp. {{ number_format($prodVisit->visitable->selling_price,0,'.','.') }}
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>

    $(".minus-cart").on("click", function () {
        var _parent = $(this).parents(".input-quantity");
        var _currentInput = _parent.find("input");
        var _number = parseInt(_currentInput.val());
        _number = _number - 1;
        if (_number > 1 && _number >= {{ $product->minimum_order_qty }}) {
            _currentInput.val(_number);
            getDetilSKU("minusQty");
        } 
        else {
            swal("Minimum Order Adalah {{ $product->minimum_order_qty }}", {
                icon: "error",
                title: "Gagal",
            });
        }
    });

    $(".plus-cart").on("click", function () {
        var _parent = $(this).parents(".input-quantity");
        var _currentInput = _parent.find("input");
        var _number = parseInt(_currentInput.val());
        _number = _number + 1;
        if  (
                _number >= 0  
                &&  (
                        {{ $product->max_order_qty ?? 0 }} >= 0 ||
                         _number <= {{ $product->max_order_qty ?? 0 }}
                    )
                && parseFloat($("#stock").html()) >= _number 
            ) 
        {
            _currentInput.val(_number);
            getDetilSKU("plusQty");
        } 
        else if(parseFloat($("#stock").html()) < _number){
            swal("Mohon maaf, stok yang tersedia tidak mencukupi", {
                icon: "error",
                title: "Gagal",
            });
        }
        else {
            
            swal("Maximum Order Adalah {{ $product->max_order_qty }}", {
                icon: "error",
                title: "Gagal",
            });

        }
    });

    $("ul.list-styles li").on("click", function () {
        if (!$(this).hasClass("disabled")) {
            var _title = $(this).attr("title");
            var _parent = $(this).parents(".box-product-style-size");
            var _id = $(this).attr('id');
            _parent.find(`.${_id}`).text(_title);
            _parent.find(`ul.list-styles li#${_id}`).removeClass("active");
            $(this).addClass("active");
        }
        getDetilSKU("variant");
    });

    function getDetilSKU(param)
    {
        var liStyle = document.querySelectorAll('ul.list-styles li');
        var values = [];
        liStyle.forEach((item, index) => {
            if(liStyle[index].getAttribute('class') == "active"){
                values.push(liStyle[index].getAttribute('title'));
            }
        });
        $.ajax({
            url : "{{ url('') }}/product_variants",
            type : "POST",
            data : {
                attribute_value : values,
                product_id : "{{ $product->id }}",
                qty : $("#qty").val()
            },
            headers : {
                'X-CSRF-TOKEN' : $("meta[name=csrf_token]").attr('content')
            },
            dataType : 'json',
            success:function(res) {
                var data = res.data;
                $("#stock").html(data.sku.stock);
                if(param == "variant") {
                    data.sku.stock >= data.minimum_order_qty ? $("#qty").val(data.minimum_order_qty) : $("#qty").val(data.sku.stock);
                }
                var qty = $("#qty").val();
                $("#sku").html(data.sku.sku);
                var newPrice = data.sku.price_after_disc * qty;
                var sellingPrice = data.sku.selling_price;
                var stockStatus = data.sku.stock > 0 ? "In Stock" : "Out Of Stock";
                $("#stockStatus").html(stockStatus)
                if(stockStatus == "In Stock") {
                    if($("#stockStatus").hasClass('btn-out-stock')) {
                        $("#stockStatus").removeClass("btn-out-stock");
                        $("#stockStatus").addClass('btn-in-stock');
                    }
                } else {
                    if($("#stockStatus").hasClass('btn-in-stock')) {
                        $("#stockStatus").removeClass("btn-in-stock");
                        $("#stockStatus").addClass('btn-out-stock');
                    }
                }
                $("#sellingPrice").html(formatRupiah(sellingPrice.toString(), "Rp. "));
                $("#subTotal").html(formatRupiah(newPrice.toString(), "xRp. "));
                $("#price").html(formatRupiah(data.sku.price_after_disc.toString(), "Rp. "));
                $("#defaultPrice").val(data.sku.price_after_disc);
            }
        })
    }
    
    function addToCart(e){

        $("#addCart").attr("disabled", true);
        $("#addCart").html("<img class='mt-1' src='{{ url('images/loading2.gif') }}' style='height:15px'>");

        setTimeout(() => {
            var liStyle = document.querySelectorAll('ul.list-styles li');
            var values = [];
            liStyle.forEach((item, index) => {
                if(liStyle[index].getAttribute('class') == "active"){
                    values.push(liStyle[index].getAttribute('title'));
                }
            });
            var qty = $("#qty").val();
            var productId = "{{ $product->id }}";

            $.ajax({
                url : "{{ url('cart') }}/add",
                type : 'POST',
                dataType : 'JSON',
                headers : {
                    'X-CSRF-TOKEN' : $("meta[name=csrf_token]").attr('content')
                },
                data : {
                    product_id : productId,
                    variant : values,
                    qty : qty
                },
                success:function(res) {
                    $("#addCart").attr("disabled", false);
                    $("#addCart").html("Tambah Kedalam Keranjang");
                    window.location.reload();
                },
                error:function(jqXHR) {
                    $("#addCart").attr("disabled", false);
                    $("#addCart").html("Tambah Kedalam Keranjang");
                    var response = jqXHR.responseJSON;
                    if(response.message == "Unauthenticated.") {
                        response = "Anda belum login, silahkan login terlebih dahulu";
                    }else {
                        response = jqXHR.responseJSON.message;
                    }
                    swal(response, {
                        icon: "error",
                        title: "Gagal",
                    });
                }
            })
        }, 1000);
    }

    function addWishlist(e){

        $("#addWishlist").attr("disabled", true);
        $("#addWishlist").html("<img class='mt-1' src='{{ url('images/loading2.gif') }}' style='height:15px'> Loading");

        setTimeout(() => {
            var liStyle = document.querySelectorAll('ul.list-styles li');
            var values = [];
            liStyle.forEach((item, index) => {
                if(liStyle[index].getAttribute('class') == "active"){
                    values.push(liStyle[index].getAttribute('title'));
                }
            });
            var productId = "{{ $product->id }}";

            $.ajax({
                url : "{{ url('wishlist') }}",
                type : 'POST',
                dataType : 'JSON',
                headers : {
                    'X-CSRF-TOKEN' : $("meta[name=csrf_token]").attr('content')
                },
                data : {
                    product_id : productId,
                    variant : values,
                },
                success:function(res) {
                    $("#addWishlist").attr("disabled", false);
                    $("#addWishlist").html("Tambah Ke Wishlist");
                    window.location.reload();
                },
                error:function(jqXHR) {
                    $("#addWishlist").attr("disabled", false);
                    $("#addWishlist").html("Tambah Kedalam Keranjang");
                    var response = jqXHR.responseJSON;
                    if(response.message == "Unauthenticated.") {
                        response = "Anda belum login, silahkan login terlebih dahulu";
                    }else {
                        response = jqXHR.responseJSON.message;
                    }
                    swal(response, {
                        icon: "error",
                        title: "Gagal",
                    });
                }
            })
        }, 1000);
    }
</script>
@endpush