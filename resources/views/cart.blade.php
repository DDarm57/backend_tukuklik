@extends('layouts.frontend.app', ['title' => 'Keranjang'])

@section('content')
<main class="main">
	<div class="section-box">
		<div class="breadcrumbs-div">
			<div class="container">
				<ul class="breadcrumb">
					<li><a class="font-xs color-gray-1000" href="{{ url('') }}">Homepage</a></li>
					<li><a class="font-xs color-gray-500" href="{{ url('cart') }}">Keranjang</a></li>
				</ul>
			</div>
		</div>
	</div>
	<section class="section-box shop-template">
		<div class="container">
			<div class="row">
				<div class="col-lg-9">
					<div class="box-carts">
						<div class="head-wishlist">
							<div class="item-wishlist">
								<div class="wishlist-product">
									<span class="font-md-bold color-white">Produk</span>
								</div>
								<div class="wishlist-price">
									<span class="font-md-bold color-white">Harga</span>
								</div>
								<div class="wishlist-status">
									<span class="font-md-bold color-white">Jumlah</span>
								</div>
								<div class="wishlist-action">
									<span class="font-md-bold color-white">Subtotal</span>
								</div>
								<div class="wishlist-remove">
									<span class="font-md-bold color-white">Hapus</span>
								</div>
							</div>
						</div>
						<div class="content-wishlist mb-20">
							@if(count($carts) == 0)
								<p class="text-center mt-20 mb-20">Keranjang Belanja Kosong</p>
							@endif
							@foreach($carts as $cart)
								<div class="item-wishlist shadow-sm">
									<div class="wishlist-cb">
										<input class="cb-layout cb-select" type="checkbox">
									</div>
									<div class="wishlist-product">
										<div class="product-wishlist">
											<div class="product-image">
												<a href="{{ url('product/'.$cart->productSku->product->slug.'') }}">
													<img src="{{ Helpers::image($cart->productSku->product->thumbnail_image_source) }}" alt="Ecom">
												</a>
											</div>
											<div class="product-info">
												<a href="{{ url('product/'.$cart->productSku->product->slug.'') }}">
													<h6 class="color-brand-3">
														{{ $cart->productSku->product->product_name }}
														-
														{{ $cart->productSku->varian }}
													</h6>
												</a>
												<x-product::rating 
													star="{{ $cart->productSku->product->rating }}"
													review="{{ $cart->productSku->product->reviews->count() }}"
												/>
											</div>
										</div>
									</div>
									<div class="wishlist-price">
										<h6 class="color-brand-3">Rp. {{ number_format($cart->base_price,0,'.','.') }}</h6>
									</div>
									<div class="wishlist-status">
										<div class="box-quantity">
											<div class="input-quantity">
												<input class="font-md color-brand-3 qty" type="text" value="{{ $cart->qty }}" readonly>
												<span class="minus-cart" onclick="adjustQty({{ $cart->id }}, 'minus')"></span>
												<span class="plus-cart" onclick="adjustQty({{ $cart->id }}, 'plus')"> </span>
											</div>
										</div>
									</div>
									<div class="wishlist-action">
										<h6 class="color-brand-3" id="subTotal{{ $cart->id }}">Rp. {{ number_format($cart->subtotal,0,'.','.') }}</h6>
									</div>
									<div class="wishlist-remove">
										<a class="btn btn-delete" href="#" onclick="deleteCart({{ $cart->id }})"></a>
									</div>
								</div>
							@endforeach
						</div>
						<div class="row mb-40">
							<div class="col-lg-6 col-md-6 col-sm-6-col-6">
								<a class="btn btn-buy w-auto arrow-back mb-10" href="{{ url('') }}">Lanjut Belanja</a>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6-col-6 text-md-end">
								<a class="btn btn-buy w-auto update-cart mb-10" href="{{ url('cart') }}">
									Update Keranjang
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="summary-cart shadow-sm">
						<div class="border-bottom mb-10">
							<div class="row">
								<div class="col-4"><span class="font-md-bold color-brand-3">Subtotal</span></div>
								<div class="col-8 text-end">
									<h6 id="subTotal"> Rp. {{ number_format($subTotal,0, '.', '.') }}</h6>
								</div>
							</div>
						</div>
						<div class="border-bottom mb-10">
							<div class="row">
								<div class="col-6"><span class="font-md-bold color-brand-3">PPN</span></div>
								<div class="col-6 text-end">
									<h6 id="tax"> Rp. {{ number_format($tax,0, '.', '.') }}</h6>
								</div>
							</div>
						</div>
						<div class="border-bottom mb-10">
							<div class="row">
								<div class="col-6">
									<span class="font-md-bold color-brand-3">PPH</span>
								</div>
								<div class="col-6 text-end">
									<h6> Rp. {{ number_format($incomeTax,0, '.', '.') }}</h6>
								</div>
							</div>
						</div>
						<div class="mb-10">
							<div class="row">
								<div class="col-4"><span class="font-md-bold color-brand-3">Total</span></div>
								<div class="col-8 text-end">
									<h6 id="grandTotal"> Rp. {{ number_format($total,0, '.', '.') }}</h6>
								</div>
							</div>
						</div>
						<div class="box-button">
							<a class="btn btn-buy" href="{{ url('dashboard/request-for-quotation-form') }}">
								Membuat Permintaan RFQ
							</a>
						</div>
					</div>
				</div>
			</div>
			<h4 class="color-brand-4">Mungkin Anda Suka</h4>
			@if(count($productReferences) == 0)
				<p class="mt-20 mb-20 text-left">Produk Pilihan Tidak Tersedia</p>
			@endif
			<div class="list-products-5 mt-20 mb-40">
				@foreach($productReferences as $prods)
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
			<h4 class="color-brand-4">Yang Baru Anda Lihat</h4>
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
							<span class="font-xs color-brand-4">{{ $prodVisit->visitable->merchant->name }}</span>
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
	</section>
</main>
@endsection

@push('scripts')
	@include('javascript.js-cart')
@endpush