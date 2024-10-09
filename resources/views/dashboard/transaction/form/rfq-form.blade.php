@extends('layouts.dashboard.app', ['title' => 'Form RFQ'])

@push('styles')
<style>
    .label-text {
        font-weight:600;
    }
</style>
@endpush

@section('content')
<div class="pagetitle">
    <h1>Permintaan Penawaran</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Transaksi</li>
            <li class="breadcrumb-item active">Form RFQ</li>
        </ol>
    </nav>
</div>
<section class="section">
    <form method="POST" id="formRFQ">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informasi Penawaran</h5>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Nama Pengaju</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $user->name }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Departemen Pengaju</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $user->lpse->nama_satker ?? '-' }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Nama Penjual</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $merchant->name }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Status Pajak Penjual</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $merchant->is_pkp == "Y" ? 'Ya' : 'Tidak' }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Alamat Penjual</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $merchantAddress }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">No. Penawaran</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $quotation->rfq_number ?? '-' }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Waktu Transaksi</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $date }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Subtotal (Belum Termasuk Pajak & Biaya Pengiriman)</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ 'Rp. '. number_format($subTotal,2,',','.') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-4 col-form-label">Keperluan</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="purpose_of">
                                        <span id="message_purpose_of" class="invalid-feedback" style="display:none">
                                        </span>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-4 col-form-label">Alamat Pengiriman</label>
                                    <div class="col-sm-8">
                                        <textarea name="shipping_address" readonly style="height:150px;" type="text" class="form-control">{{ $customerAddress }}</textarea>
                                        <span id="message_shipping_address" class="invalid-feedback" style="display:none">
                                        </span>
                                        <button type="button" id="addAddress" data-value="shippingAddress" class="btn btn-outline-danger mt-2" style="{{ $customerAddress == 'Alamat belum ditambahkan' ? '' : 'display:none' }}">Tambah Alamat</button>
                                        <a id="changeAddress" data-value="shippingAddress" class="btn btn-outline-danger mt-2" style="{{ $customerAddress == 'Alamat belum ditambahkan' ? 'display:none' : '' }}">Ganti Alamat</a>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-4 col-form-label">Alamat Tagihan</label>
                                    <div class="col-sm-8">
                                        <input type="hidden" name="billing_address_id" value="{{ $customerAddressId }}">
                                        <input type="hidden" name="customer_address_id" value="{{ $customerAddressId }}">
                                        <textarea name="billing_address" readonly style="height:150px;" type="text" class="form-control">{{ $customerAddress }}</textarea>
                                        <span id="message_billing_address" class="invalid-feedback" style="display:none">
                                        </span>
                                        <button type="button" id="addAddress" data-value="billingAddress" class="btn btn-outline-danger mt-2" style="{{ $customerAddress == 'Alamat belum ditambahkan' ? '' : 'display:none' }}">Tambah Alamat</button>
                                        <a id="changeAddress" data-value="billingAddress" class="btn btn-outline-danger mt-2" style="{{ $customerAddress == 'Alamat belum ditambahkan' ? 'display:none' : '' }}">Ganti Alamat</a>
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
                        <h5 class="card-title">Informasi Penerima & Tipe Pembayaran</h5>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Nama Penerima</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="user_pic" value="{{ $user->name }}">
                                <span id="message_user_pic" class="invalid-feedback" style="display:none">
                                </span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">No. Telp Penerima</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="user_phone" value="{{ $user->phone_number }}">
                                 <span id="message_user_phone" class="invalid-feedback" style="display:none">
                                </span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Catatan Untuk Penjual</label>
                            <div class="col-sm-8">
                                <textarea id="notesMerchant" class="form-control w-100" name="notes_for_merchant" rows="100" cols="50"></textarea>
                                <span id="notes_for_merchant" class="invalid-feedback" style="display:none">
                                </span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Tipe Pembayaran</label>
                            <div class="col-sm-8">
                                <select class="selects form-control" name="payment_type" id="paymentType">
                                    <option></option>
                                    @foreach($paymentType as $type)
                                        <option>{{ $type }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="shipping_fee" value="0">
                                <span id="message_payment_type" class="invalid-feedback" style="display:none">
                                </span>
                                <div id="termin" class="mt-2 w-50" style="display:none">
                                    <div class="input-group mb-3"> 
                                        <input value="0" type="number" name="termin" placeholder="Termin..." class="form-control"> 
                                        <span class="input-group-text" id="basic-addon2">Hari</span>
                                        <span id="termin" class="invalid-feedback" style="display:none">
                                        </span>
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
                        <h5 class="card-title">Grand Total Produk</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-danger text-white">
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Produk</th>
                                    <th rowspan="2">Ketersediaan</th>
                                    <th rowspan="2">Kuantitas</th>
                                    <th rowspan="2">Base Price</th>
                                    <th rowspan="2" style="width:20%">Total</th>
                                </thead>
                                <tbody>
                                    @foreach($produk as $key => $prod)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-3">
                                                    <img src="{{ $prod->productSku->product->thumbnail_image_source }}" style="height:70px">
                                                </div>
                                                <div class="col-9">
                                                    {{ $prod->productSku->product->product_name }}<br>
                                                    SKU <b>{{ $prod->productSku->sku }}</b>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($prod->productSku->product->stock_type == "Ready Stock")
                                                <span class="badge bg-success">{{ $prod->productSku->product->stock_type }}</span>
                                            @elseif($prod->productSku->product->stock_type == "Pre Order")
                                                <span class="badge bg-info">{{ $prod->productSku->product->stock_type }}</span>
                                            @endif

                                            <br/>

                                            <small class="font-xs text-muted">Estimasi :  {{ $prod->productSku->product->processing_estimation. " Hari" }}</small>
                                        </td>
                                        <td id="quantity">{{ $prod->qty }}</td>
                                        <td>
                                            {{ 'Rp. '. number_format($prod->base_price,2,',','.') }}
                                        </td>
                                        <td>
                                            {{ 'Rp. '. number_format($prod->subtotal,2,',','.') }}
                                            <input type="hidden" id="subTotalTemp" value="{{ $prod->subtotal }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" rowspan="2" style="vertical-align : middle;text-align:center;"><div class="text-center">Pajak</div></td>
                                        <td>
                                            PPN <b>{{ $prod->tax_amount / $prod->subtotal * 100  }} %</b>
                                            <input id="inputPpn" type="hidden" class="form-control" type="text" value="{{ $prod->tax_amount / $prod->subtotal * 100 }}">
                                        </td>
                                        <td>Rp. {{ number_format($prod->tax_amount,2,',','.') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width:20%">
                                            PPH
                                            <input type="number" name="pph[]" value="0" id="inputpph" style="width:50%">
                                        </td>
                                        <td style="width:1%">
                                            <div id="pph">(Rp. 0)</div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div  style="float:right;width:40%">
                                    <input type="text" name="coupon_code" class="form-control" placeholder="Kupon Diskon (Jika Ada)">
                                    <span id="message_coupon_code" class="invalid-feedback" style="display:none">
                                    </span>
                                </div>
                            </div>
                            {{-- <div class="col-12" style="margin-right:auto;"> --}}
                            {{-- </div> --}}
                            <div class="col-12" style="margin-right:auto;">
                            <table class="mt-2 table table-bordered" style="width:40%;float:right">
                                <tr>
                                    <th style="width:40%">Subtotal</th>
                                    <td><span class="text-right">Rp. {{ number_format($subTotal,2,',','.') }}</td>
                                </tr>
                                <tr>
                                    <th style="width:30%">PPN</th>
                                    <td><span class="text-right">Rp. {{ number_format($taxTotal,2,',','.') }}</td>
                                </tr>
                                <tr>
                                    <th style="width:30%">PPH</th>
                                    <td><span class="text-right" id="totalPph">Rp. 0</td>
                                </tr>
                                <tr>
                                    <th style="width:30%">Ongkir</th>
                                    <td>{{ Helpers::getShippingFeeEstimation() }} (Estimasi)</td>
                                </tr>
                                <tr class="bg-danger text-white">
                                    <th style="width:30%">Grand Total</th>
                                    <td><span class="text-right" id="grandTotal">Rp. {{ number_format($grandTotal,2,',','.') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-12">
                            <span style="font-style:italic;float:right;width:40%">* Grand Total Belum Termasuk Ongkos Kirim</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body> p-3">
                        <div class="row">
                            <div class="col-8">
                                <h5 class="mt-2">Proses?</h5>
                            </div>
                            <div class="col-sm-12 col-lg-2">
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-md w-100"><i class="bi bi-x-circle"></i> Batal</a>
                            </div>
                            <div class="col-sm-12 col-lg-2">
                                <a id="saveRFQ" class="btn btn-outline-danger btn-md w-100 submit"><i class="bi bi-save"></i> Ajukan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
<div class="modal fade" id="changeAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Ganti Alamat</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <a class="btn btn-outline-danger btn-md w-100" id="addAnotherAddress">Tambah Alamat</a>
            </div>
        </div>
        <div class="modal-footer">
            <button id="saveChangeAddress" type="button" class="btn btn-outline-danger"><i class="bi bi-save"></i> Simpan</button>
        </div>
        </div>
    </div>
</div>
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
                        <select class="selects2 form-control" id="province" name="province">
                            <option></option>
                        </select>
                        <span id="message_province" class="invalid-feedback" style="display:none">
                        </span>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <label>Kota / Kabupaten</label>
                        <select class="selects2 form-control" id="city" name="city">
                            <option></option>
                        </select>
                        <span id="message_city" class="invalid-feedback" style="display:none">
                        </span>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <label>Kecamatan</label>
                        <select class="selects2 form-control" id="district" name="district">
                            <option></option>
                        </select>
                        <span id="message_district" class="invalid-feedback" style="display:none">
                        </span>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <label>Kelurahan</label>
                        <select class="selects2 form-control" id="subdistrict" name="subdistrict">
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
@endsection

@push('scripts')
    <script src="{{ url('admin/assets/vendor/ckeditor2/ckeditor.js') }}"></script>
    <script>
        var notesMerchant = document.getElementById("notesMerchant");
        CKEDITOR.replace(notesMerchant,{
            language:'en-gb'
        });
        CKEDITOR.config.allowedContent = true;
        CKEDITOR.config.width = "100%";
    </script>
    @include('dashboard.javascript.js-rfq')
@endpush