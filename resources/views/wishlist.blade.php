@extends('layouts.frontend.app', ['title' => 'Wishlist'])

@section('content')
<main class="main">
	<div class="section-box">
		<div class="breadcrumbs-div">
			<div class="container">
				    <ul class="breadcrumb">
					<li><a class="font-xs color-gray-1000" href="{{ url('') }}">Homepage</a></li>
					<li><a class="font-xs color-gray-500" href="{{ url('wishlist') }}">Wishlist</a></li>
				</ul>
			</div>
		</div>
	</div>
    <section class="section-box shop-template">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
                    <div class="box-wishlist">
                        <div class="head-wishlist">
                            <div class="item-wishlist">
                                <div class="wishlist-product">
                                    <span class="font-md-bold color-white">Produk</span>
                                </div>
                                <div class="wishlist-price">
                                    <span class="font-md-bold color-white">Harga</span>
                                </div>
                                <div class="wishlist-status">
                                    <span class="font-md-bold color-white">Status Stok</span>
                                </div>
                                <div class="wishlist-action">
                                    <span  class="font-md-bold color-white">Aksi</span>
                                </div>
                                <div class="wishlist-remove">
                                    <span  class="font-md-bold color-white">Hapus</span>
                                </div>
                            </div>
                        </div>
                        <div class="content-wishlist">
                            @foreach($wishlist as $wish)
                            <div class="item-wishlist">
                                <div class="wishlist-product">
                                    <div class="product-wishlist">
                                        <div class="product-image">
                                            <a href="{{ url('product/'.$wish->productSku->product->slug.'') }}">
                                                <img src="{{ Helpers::image($wish->productSku->product->thumbnail_image_source) }}" style="height:60px;">
                                            </a>
                                        </div>
                                        <div class="product-info">
                                            <a href="{{ url('product/'.$wish->productSku->product->slug.'') }}">
                                                <h6 class="color-brand-3">
                                                    {{ $wish->productSku->product->product_name. " - ". $wish->productSku->varian }}
                                                </h6>
                                            </a>
                                            <x-product::rating 
                                                star="{{ $wish->productSku->product->rating }}"
                                                review="{{ $wish->productSku->product->reviews->count() }}"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="wishlist-price">
                                    <h4 class="color-brand-3 font-md-bold">Rp. {{ number_format($wish->productSku->product->selling_price,0,'.','.') }}</h4>
                                </div>
                                <div class="wishlist-status">
                                    <span class="btn {{ $wish->productSku->stock_status == "In Stock" ? "btn-in-stock" : "btn-out-stock" }} font-sm-bold">{{ $wish->productSku->stock_status }}</span>
                                </div>
                                <div class="wishlist-action">
                                    <a onclick="addCart({{ $wish->id }})" class="btn btn-cart font-sm-bold" href="#">+ Keranjang</a>
                                </div>
                                <div class="wishlist-remove">
                                    <a href="#" onclick="deleteWishlist({{ $wish->id }})" class="btn btn-delete"></a>
                                </div>
                            </div>
                            @endforeach
                            @if(count($wishlist) == 0)
                                <h6 class="text-center">Data tidak ditemukan</h6>
                            @endif
                            {!! $wishlist->links('pagination::bootstrap-5') !!}
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

        function addCart(wihslistId){
            $.ajax({
                url : `{{ url('wishlist/cart') }}`,
                type : 'POST',
                dataType: 'JSON',
                data : { 
                    wishlist_id : wihslistId,
                    _token : $("meta[name='csrf_token']").attr('content')
                },
                success:function(res) {
                    window.location.reload();
                },
                error:function(jqXHR){
                    swal(jqXHR.responseJSON.message, {
                        icon: "error",
                    });
                }
            })
        }

        deleteWishlist = (wihslistId) => {
            swal({
                title: "Apakah anda yakin?",
                text: "Wishlist akan segera dihapus",
                icon: "warning",
                buttons: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url : `{{ url('wishlist/${wihslistId}') }}`,
                        type : 'DELETE',
                        dataType: 'JSON',
                        data : { 
                            _token : $("meta[name='csrf_token']").attr('content')
                        },
                        success:function(res) {
                            window.location.reload();
                        },
                        error:function(jqXHR){
                            swal(jqXHR.responseJSON.message, {
                                icon: "error",
                            });
                        }
                    })
                }
            });
        }

    </script>
@endpush