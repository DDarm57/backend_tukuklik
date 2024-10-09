@extends('layouts.frontend.app', ['title' => 'Profil Saya'])

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet">
{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
<link href="{{ url('frontend/assets/css/custom.css') }}" rel="stylesheet">
@endpush

@section('content')
<main class="main">
    <section class="section-box shop-template mt-30 mb-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 mt-20">
                    <div class="box-border-product">
                        <div class="vendor-logo d-flex mb-20">
                            <img style="height:50px;" src="{{ Helpers::photoProfile(auth()->user()->photo) }}">
                            <div class="vendor-name ml-15 mt-15">
                                <h6><a href="">{{ Auth::user()->name }}</a></h6>
                            </div>
                        </div>
                        <h6 class="mt-20 color-brand-4">Info Profil</h6>
                        <div class="border-bottom pt-10 mb-20"></div>
                        <ul class="contact-infor">
                            <li class="pb-5">
                                <strong class="ml-4 mt">
                                <i class="bi bi-envelope"></i>
                                </strong>
                                <span>{{ auth()->user()->email }}</span>
                            </li>
                            <li class="pb-5">
                                <strong class="ml-4">
                                <i class="bi bi-telephone"></i>
                                </strong>
                                <span>{{ auth()->user()->phone ?? 'Belum Diatur' }}</span>
                            </li>
                            <li class="pb-5">
                                <div class="d-flex">
                                <strong class="ml-4">
                                <i class="bi bi-tags"></i>
                                </strong>
                                <span class="ml-5">{{ auth()->user()->lpse->nama_instansi ?? '-' }}</span>
                                </div>
                            </li>
                            <li class="pb-5">
                                <strong class="ml-4">
                                <i class="bi bi-zoom-in"></i>
                                </strong>
                                <span>{{ auth()->user()->lpse->nama_satker ?? '-' }}</span>
                            </li>
                            <li class="pb-5">
                                <strong class="ml-4">
                                <i class="bi bi-info-circle"></i>
                                </strong>
                                <span>{{ date('d F Y H:i:s', strtotime(auth()->user()->created_at)) }}</span>
                            </li>
                            <li class="pb-5">
                                <strong class="ml-4">
                                <i class="bi bi-check2-square"></i>
                                <span class="ml-8">Aktif?</span>
                                </strong>
                                <span>{{ auth()->user()->status == "Y" ? "Ya" : "Tidak" }}</span>
                            </li>
                        </ul>
                        <h6 class="mt-20 color-brand-4">Transaksi</h6>
                        <div class="border-bottom pt-10 mb-20"></div>
                        <ul class="contact-infor">
                            <li class="pb-5">
                                <strong class="ml-4 mt">
                                <span class="ml-8">
                                    <a class="color-brand-3" href="{{ url('dashboard/request-for-quotation') }}">Permintaan</a>
                                </span>
                                </strong>
                                <span>({{ $countRfq }})</span>
                            </li>
                            <li class="pb-5">
                                <strong class="ml-4 mt">
                                <span class="ml-8">
                                    <a class="color-brand-3" href="{{ url('dashboard/quotation') }}">Penawaran</a>
                                </span>
                                </strong>
                                <span>({{ $countQuote }})</span>
                            </li>
                            <li class="pb-5">
                                <strong class="ml-4 mt">
                                <span class="ml-8">
                                    <a class="color-brand-3" href="{{ url('dashboard/purchase-order') }}">Pesanan</a>
                                </span>
                                </strong>
                                <span>({{ $countOrder }})</span>
                            </li>
                            <li class="pb-5">
                                <strong class="ml-4 mt">
                                <span class="ml-8">
                                    <a class="color-brand-3" href="{{ url('dashboard/invoice') }}">Tagihan</a>
                                </span>
                                </strong>
                                <span>({{ $countInvoice }})</span>
                            </li>
                        </ul>
                        <h6 class="mt-20 color-brand-4">Profil</h6>
                        <div class="border-bottom pt-10 mb-20"></div>
                        <ul class="contact-infor">
                            <li class="pb-5">
                                <strong class="ml-4 mt">
                                <i class="bi bi-grid-3x3-gap"></i>
                                <span class="ml-8">
                                    <a class="color-brand-3" href="?tab=setting">Pengaturan</a>
                                </span>
                                </strong>
                            </li>
                            <li class="pb-5">
                                <strong class="ml-4 mt">
                                <i class="bi bi-box-arrow-in-left"></i>
                                <span class="ml-8">
                                    <a class="color-brand-3" href="{{ url('logout') }}">Keluar</a>
                                </span>
                                </strong>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="box-tabs">
                        <ul class="nav nav-tabs nav-tabs-account" role="tablist">
                            <li>
                                <a class="{{ request()->get('tab') == "notification" || request()->get('tab') == "" ? "active" : "" }}" href="{{ url('profile?tab=notification') }}">Notifikasi ({{ auth()->user()->unreadNotifications()->count() }})</a>
                            </li>
                            <li>
                                <a class="{{ request()->get('tab') == "wishlist" ? "active" : "" }}" href="{{ url('profile?tab=wishlist') }}">Wishlist (0)</a>
                            </li>
                            <li>
                                <a class="{{ request()->get('tab') == "transaction" ? "active" : "" }}" href="{{ url('profile?tab=transaction') }}">Transaksi</a>
                            </li>
                            <li>
                                <a class="{{ request()->get('tab') == "setting" ? "active" : "" }}" href="{{ url('profile?tab=setting') }}">Pengaturan</a>
                            </li>
                        </ul>
                        <div class="border-bottom mt-10 mb-20"></div>
                        <div class="tab-content mt-10">
                            @if(request()->get('tab') == "notification" || request()->get('tab') == "")
                                <div class="list-notifications">
                                    @foreach($notification as $notif)
                                    @php $notif->markAsRead() @endphp
                                    <div class="item-notification">
                                        <div class="image-notification">
                                            <img style="height:70px;" src="{{ url('images/inpoh.png') }}" alt="Ecom">
                                        </div>
                                        <div class="info-notification">
                                            <h5 class="mb-5 color-brand-3">{{ $notif->data['title'] }}</h5>
                                            <p class="font-md color-brand-3">
                                                {{ $notif->data['message'] }}
                                            </p>
                                            <span class="text-muted color-brand-4">{{ date('d F Y H:i:s', strtotime($notif->created_at)) }}</span>
                                        </div>
                                        <div class="button-notification">
                                            <a href="{{ $notif->data['url'] }}" class="btn btn-buy w-auto">Selengkapnya</a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                {!! $notification->links('pagination::bootstrap-5') !!}
                            @elseif(request()->get('tab') == "wishlist")
                                <div class="box-wishlist">
                                    <div class="head-wishlist">
                                        <div class="item-wishlist">
                                            <div class="wishlist-product">
                                                <span class="font-md-bold color-brand-3">Produk</span>
                                            </div>
                                            <div class="wishlist-price">
                                                <span class="font-md-bold color-brand-3">Harga</span>
                                            </div>
                                            <div class="wishlist-status">
                                                <span class="font-md-bold color-brand-3">Status Stok</span>
                                            </div>
                                            <div class="wishlist-action">
                                                <span  class="font-md-bold color-brand-3">Aksi</span>
                                            </div>
                                            <div class="wishlist-remove">
                                                <span  class="font-md-bold color-brand-3">Hapus</span>
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
                            @elseif(request()->get('tab') == "transaction")
                                <div class="box-slider-item mb-20">
                                    <div class="head border-brand-2">
                                        <h5 class="color-gray-900">Kategori</h5>
                                    </div>
                                    <div class="content-slider">
                                        <a class="btn btn-border mr-5 {{ request()->get('type') == "rfq" || request()->get('type') == "" ? "bg-color-danger color-white" : "" }}" href="{{ url('profile') }}?tab=transaction&type=rfq">Permintaan ({{ $countRfq }})</a>
                                        <a class="btn btn-border mr-5 {{ request()->get('type') == "quote" ? "bg-color-danger color-white" : "" }}" href="{{ url('profile') }}?tab=transaction&type=quote">Penawaran ({{ $countQuote }})</a>
                                        <a class="btn btn-border mr-5 {{ request()->get('type') == "order" ? "bg-color-danger color-white" : "" }}" href="{{ url('profile') }}?tab=transaction&type=order">Pesanan ({{ $countOrder }})</a>
                                        <a class="btn btn-border mr-5 {{ request()->get('type') == "invoice" ? "bg-color-danger color-white" : "" }}" href="{{ url('dashboard/invoice') }}">Tagihan ({{ $countInvoice }})</a>
                                    </div>
                                </div>
                                @if($transaction->count() == 0)
                                    <h6 class="text-center">Data tidak ditemukan</h6>
                                @endif
                                @if($transaction->count() > 0)
                                    <h6 class="text-left mt-10 mb-20">Menampilkan 5 Transaksi Terakhir</h6>
                                @endif
                                @foreach($transaction->get() as $trans)
                                @php $number = $trans->number ?? $trans->order_number @endphp
                                <div class="box-orders">
                                    <div class="head-orders">
                                        <div class="head-left">
                                            <h6 class="mr-20">ID : {{ $number }}</h6>
                                            <span class="font-md color-brand-3 mr-20">Date: {{ date('d F Y', strtotime($trans->created_at)) }}</span>
                                            <span class="font-md color-brand-3 mr-20">Rp. {{ number_format($trans->grand_total,0,'.','.') }}</span>
                                            {!! Helpers::colorStatus($trans->quoteStatus->name ?? $trans->status->name) !!}
                                        </div>
                                        <div class="head-right">
                                            <a href="{{ substr($number,0,2) == "PO" ? url('dashboard/purchase-order/'.$number.'') : (substr($number,0,2) == "QN" ? url('dashboard/quotation/'.$number.'') : url('dashboard/request-for-quotation/'.$number.'') ) }}" class="btn btn-buy font-sm-bold w-auto">Lihat Detail</a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @if($transaction->count() > 0)
                                    <a href="{{ substr($number,0,2) == "PO" ? url('dashboard/purchase-order') : (substr($number,0,2) == "QN" ? url('dashboard/quotation') : url('dashboard/request-for-quotation')) }}" class="btn btn-buy font-sm-bold w-auto">Lihat Semua</a>
                                @endif
                            @elseif(request()->get('tab') == "setting")
                                <div class="row">
                                    <form action="{{ route('profile.update', auth()->user()->id) }}" method="POST">
                                        <div class="col-lg-12 mb-20">
                                            <h5 class="font-md-bold color-brand-3 text-sm-start text-center">
                                                Informasi Pesonal
                                            </h5>
                                        </div>
                                        <div class="col-lg-10">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input name="name" value="{{ old('name') ?? auth()->user()->name }}" class="form-control font-sm @error('name') is-invalid @enderror" type="text" placeholder="Nama Lengkap">
                                                    @error('name')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input name="email" value="{{ auth()->user()->email }}" class="form-control font-sm @error('email') is-invalid @enderror" type="text" placeholder="Email">
                                                    @error('email')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input name="date_of_birth" value="{{ auth()->user()->date_of_birth }}" class="form-control font-sm @error('date_of_birth') is-invalid @enderror" type="date" placeholder="Tanggal Lahir">
                                                    @error('date_of_birth')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input name="phone_number" value="{{ auth()->user()->phone_number }}" class="form-control font-sm @error('phone_number') is-invalid @enderror" type="text" placeholder="Nomor Telepon">
                                                    @error('phone_number')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mb-20">
                                                <h5 class="font-md-bold color-brand-3 text-sm-start text-center">
                                                    Password
                                                </h5>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input name="old_password" value="{{ auth()->user()->phone }}" class="form-control font-sm @error('old_password') is-invalid @enderror" type="password" placeholder="Password Lama">
                                                    @error('old_password')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input name="password" value="{{ auth()->user()->phone }}" class="form-control font-sm @error('password') is-invalid @enderror" type="password" placeholder="Password Baru">
                                                    @error('password')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <input name="password_confirmation" value="{{ auth()->user()->phone }}" class="form-control font-sm" type="password" placeholder="Konfirmasi Password Baru">
                                                </div>
                                            </div>
                                            <button class="btn btn-buy font-sm-bold w-100">Simpan</button>
                                            @method('PUT')
                                            @csrf
                                        </form>
                                        <div class="col-lg-12">
                                            <h5 class="font-md-bold color-brand-3 mt-15 mb-20">
                                                Alamat
                                            </h5>
                                            <a id="addAddress" class="btn btn-buy font-sm-bold w-auto mb-20">Tambah Alamat</a>
                                            @foreach(auth()->user()->customerAddresses()->get() as $address)
                                            <div class="box-orders">
                                                <div class="head-orders">
                                                    <div class="head-left">
                                                        <h6 class="mr-20">{{ $address->address_name }}</h6>
                                                        <span class="font-md color-brand-3 mr-20">{{ $address->full_address }}</span>
                                                        <span class="font-md color-brand-3 mr-20"><strong>Utama?</strong> {{ $address->is_default == 1 ? "Ya" : "Tidak" }}</span>
                                                    </div>
                                                    <div class="head-right">
                                                        <a id="changeAddress" data-value="{{ $address->id }}" class="btn btn-sm btn-outline-primary">Ubah</a>
                                                    </div>
                                                    <div class="head-right">
                                                        <a onclick="deleted({{ $address->id }})" class="btn btn-sm btn-outline-danger">Hapus</a>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Tambah Alamat</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="saveAddrForm">
                <div class="row">
                    <div class="col-lg-12 mb-2">
                        <label>Provinsi</label>
                        <select class="selects1 form-control province" name="province">
                            <option></option>
                        </select>
                        <span id="message_province" class="invalid-feedback" style="display:none">
                        </span>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <label>Kota / Kabupaten</label>
                        <select class="selects1 form-control city" name="city">
                            <option></option>
                        </select>
                        <span id="message_city" class="invalid-feedback" style="display:none">
                        </span>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <label>Kecamatan</label>
                        <select class="selects1 form-control district" name="district">
                            <option></option>
                        </select>
                        <span id="message_district" class="invalid-feedback" style="display:none">
                        </span>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <label>Kelurahan</label>
                        <select class="selects1 form-control subdistrict" name="subdistrict">
                            <option></option>
                        </select>
                        <span id="message_subdistrict" class="invalid-feedback" style="display:none">
                        </span>
                    </div> 
                    <div class="col-lg-12 mb-2">
                        <label>Alamat Lengkap</label>
                        <textarea class="form-control" name="address"></textarea>
                        <span id="message_address" class="invalid-feedback" style="display:none">
                        </span>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <label>Kode Pos</label>
                        <input class="form-control" name="postcode" placeholder="Ex: 17612">
                        <span id="message_postcode" class="invalid-feedback" style="display:none">
                        </span>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <label>Nama Alamat</label>
                        <input class="form-control" name="address_name" placeholder="Ex: Alamat Kantor, Alamat Cabang">
                        <span id="message_address_name" class="invalid-feedback" style="display:none">
                        </span>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button id="saveAddAddress" type="button" class="btn btn-outline-danger"><i class="bi bi-save"></i> Simpan</button>
        </div>
        </div>
    </div>
</div>
<div class="modal fade" id="changeAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Ubah Alamat</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="changeAddrForm">
                <div class="row">
                    <div class="col-lg-12 mb-2">
                        <label>Provinsi</label>
                        <select class="selects2 form-control province" name="province">
                            <option></option>
                        </select>
                        <span id="message_province" class="invalid-feedback" style="display:none">
                        </span>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <label>Kota / Kabupaten</label>
                        <select class="selects2 form-control city" name="city">
                            <option></option>
                        </select>
                        <span id="message_city" class="invalid-feedback" style="display:none">
                        </span>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <label>Kecamatan</label>
                        <select class="selects2 form-control district" name="district">
                            <option></option>
                        </select>
                        <span id="message_district" class="invalid-feedback" style="display:none">
                        </span>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <label>Kelurahan</label>
                        <select class="selects2 form-control subdistrict" name="subdistrict">
                            <option></option>
                        </select>
                        <span id="message_subdistrict" class="invalid-feedback" style="display:none">
                        </span>
                    </div> 
                    <div class="col-lg-12 mb-2">
                        <label>Alamat Lengkap</label>
                        <textarea class="form-control" name="address"></textarea>
                        <span id="message_address" class="invalid-feedback" style="display:none">
                        </span>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <label>Kode Pos</label>
                        <input class="form-control" name="postcode" placeholder="Ex: 17612">
                        <span id="message_postcode" class="invalid-feedback" style="display:none">
                        </span>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <label>Nama Alamat</label>
                        <input class="form-control" name="address_name" placeholder="Ex: Alamat Kantor, Alamat Cabang">
                        <span id="message_address_name" class="invalid-feedback" style="display:none">
                        </span>
                    </div>
                </div>
                @method('PUT')
            </form>
        </div>
        <div class="modal-footer">
            <button id="saveChangeAddress" type="button" class="btn btn-outline-danger"><i class="bi bi-save"></i> Simpan</button>
        </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>

        $("#addAddress").click(function() {
            $("#addAddressModal").modal('show');
        });

        $("#changeAddress").on('click', function(){

            var id = $(this).data('value');

            $.ajax({
                url : "{{ url('dashboard/address') }}/" + id,
                type : 'GET',
                headers : {
                    'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
                },
                beforeSend:function(){
                    addAddress("changeAddrForm");
                },
                success:function(res) {

                    $("#saveChangeAddress").attr('data-value', id);

                    var province = "<option></option>";
                    res.additional.province.forEach((item, index) => {
                        province += `<option value='${item.prov_id}'>${item.prov_name}</option>`;
                    });
                    $("#changeAddrForm .province").html(province);

                    var city = "<option></option>";
                    res.additional.city.forEach((item, index) => {
                        city += `<option value='${item.city_id}'>${item.city_name}</option>`;
                    });
                    $("#changeAddrForm .city").html(city);

                    var district = "<option></option>";
                    res.additional.district.forEach((item, index) => {
                        district += `<option value='${item.dis_id}'>${item.dis_name}</option>`;
                    });
                    $("#changeAddrForm .district").html(district);

                    var subdistrict = "<option></option>";
                    res.additional.subdistrict.forEach((item, index) => {
                        subdistrict += `<option value='${item.subdis_id}'>${item.subdis_name}</option>`;
                    });
                    $("#changeAddrForm .subdistrict").html(subdistrict);
                    $("#changeAddressModal").modal('show');

                    $("#changeAddrForm .province").val(res.data.shipping_province_id);
                    $("#changeAddrForm .city").val(res.data.shipping_city_id);
                    $("#changeAddrForm .district").val(res.data.shipping_district_id);
                    $("#changeAddrForm .subdistrict").val(res.data.shipping_subdistrict_id);
                    $("#changeAddrForm [name=address]").val(res.data.full_address);
                    $("#changeAddrForm [name=postcode]").val(res.data.shipping_postcode);
                    $("#changeAddrForm [name=address_name]").val(res.data.address_name);
                }
            })
        });

        function addAddress(type) {

            $("#"+type+" .province").html('<option></option>');
            $("#"+type+" .city").html('<option></option>');
            $("#"+type+" .district").html('<option></option>');
            $("#"+type+" .subdistrict").html('<option></option>');

            var triggerButton = type == "saveAddrForm" ? "addAddress" : "changeAddress";
            var addAddress = document.querySelectorAll("#"+triggerButton+"");
            addAddress.forEach((item, index) => {
                addAddress[index].addEventListener('click', function() {
                    $("#"+type+" .province").html('<option></option>');
                    $("#"+type+" .city").html('<option></option>');
                    $("#"+type+" .district").html('<option></option>');
                    $("#"+type+" .subdistrict").html('<option></option>');
                    $.ajax({
                        url : "{{ url('dashboard/province') }}",
                        type : 'GET',
                        headers : {
                            'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
                        },
                        success:function(res) {
                            var province = "<option></option>";
                            res.data.forEach((item, index) => {
                                province += `<option value='${item.prov_id}'>${item.prov_name}</option>`;
                            });
                            $("#"+type+" .province").html(province);
                        }
                    });
                    $("#addAddressModal").modal('show');
                });
            });

            var province = document.querySelector("#"+type+" .province");
            $("#"+type+" .province").on('change', function(){
                $("#"+type+" .district").html("<option></option>");
                $("#"+type+" .subdistrict").html("<option></option>");
                $.ajax({
                    url : `{{ url('dashboard/city') }}/${$(this).val()}`,
                    type : 'GET',
                    headers : {
                        'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
                    },
                    success:function(res) {
                        var city = "<option></option>";
                        res.data.forEach((item, index) => {
                            city += `<option value='${item.city_id}'>${item.city_name}</option>`;
                        });
                        $("#"+type+" .city").html(city);
                    }
                });
            });

            $("#"+type+" .city").on('change', function(){
                $("#"+type+" .subdistrict").html("<option></option>");
                $.ajax({
                    url : `{{ url('dashboard/district') }}/${$(this).val()}`,
                    type : 'GET',
                    headers : {
                        'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
                    },
                    success:function(res) {
                        var district = "<option></option>";
                        res.data.forEach((item, index) => {
                            district += `<option value='${item.dis_id}'>${item.dis_name}</option>`;
                        });
                        $("#"+type+" .district").html(district);
                    }
                });
            });

           
            $("#"+type+" .district").on('change', function(){
                $.ajax({
                    url : `{{ url('dashboard/subdistrict') }}/${$(this).val()}`,
                    type : 'GET',
                    headers : {
                        'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
                    },
                    success:function(res) {
                        var subdistrict = "<option></option>";
                        res.data.forEach((item, index) => {
                            subdistrict += `<option value='${item.subdis_id}'>${item.subdis_name}</option>`;
                        });
                        $("#"+type+" .subdistrict").html(subdistrict);
                    }
                });
            });

            $(".selects1").select2({
                placeholder: 'Select an option',
                theme: "bootstrap",
                dropdownParent: $('#addAddressModal'),
                width : '100%',
            });

             $(".selects2").select2({
                placeholder: 'Select an option',
                theme: "bootstrap",
                dropdownParent: $('#changeAddressModal'),
                width : '100%',
            });
        }

        addAddress("saveAddrForm");

        $("#saveAddAddress").click(function() {
            data = new FormData($("#saveAddrForm")[0]);
            data.append('from_frontend', true);
            $("#saveAddAddress").attr('disabled', true).html("<img src='{{ url('images/loading2.gif') }}' style='height:20px'> Loading...");
            $.ajax({
                url : "{{ url('dashboard/address') }}",
                type : 'POST',
                dataType : 'JSON',
                contentType: false,
                processData: false,
                headers : {
                    'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
                },
                data : data,
                success:function(res) {
                    $("#addAddressModal").modal('hide');
                    window.location.reload();
                },
                error:function(jqXHR){
                    $("#saveAddAddress").attr('disabled', false).html('<i class="bi bi-save"></i> Simpan')
                    var json = jqXHR.responseJSON;
                    //if(typeof json.status !== 'undefined') {
                    printError(json.errors);
                    //}
                }
            })
        });

        $("#saveChangeAddress").click(function() {
            data = new FormData($("#changeAddrForm")[0]);
            data.append('from_frontend', true);
            $("#saveChangeAddress").attr('disabled', true).html("<img src='{{ url('images/loading2.gif') }}' style='height:20px'> Loading...");
            $.ajax({
                url : "{{ url('dashboard/address') }}/" + $(this).data('value'),
                type : 'POST',
                dataType : 'JSON',
                contentType: false,
                processData: false,
                headers : {
                    'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
                },
                data : data,
                success:function(res) {
                    window.location.reload();
                },
                error:function(jqXHR){
                    $("#saveChangeAddress").attr('disabled', false).html('<i class="bi bi-save"></i> Simpan')
                    var json = jqXHR.responseJSON;
                    //if(typeof json.status !== 'undefined') {
                    printError(json.errors);
                    //}
                }
            })
        });

        deleted = (id) => {
            swal({
                title: "Apakah anda yakin?",
                text: "Alamat yang sekarang disimpan akan dihapus",
                icon: "warning",
                buttons: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url : `{{ url('dashboard/address/${id}') }}`,
                        type : 'DELETE',
                        dataType: 'JSON',
                        data : { 
                            _token : $("meta[name='csrf_token']").attr('content')
                        },
                        success:function(res) {
                            window.location.reload();
                        },
                        error:function(jqXHR){
                            swal(jqXHR.responseText, {
                                icon: "error",
                            });
                        }
                    })
                }
            });
        }
    </script>
@endpush