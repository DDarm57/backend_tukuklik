@extends('layouts.dashboard.app', ['title' => 'Pesanan'])

@push('styles')
<style>
    .label-text {
        font-weight:600;
    }
</style>
@endpush

@section('content')
<div class="pagetitle">
    <h1>Pesanan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Transaksi</li>
            <li class="breadcrumb-item active">Pesanan</li>
        </ol>
    </nav>
</div>
<section class="section">
    <form method="POST" id="formRFQ">
        <a href="{{ url('dashboard/purchase-order/'.$order->order_number.'/download') }}" target="_blank" class="btn btn-outline-danger mb-4"><i class="bi bi-file-earmark-richtext"></i> Cetak Pesanan</a>
        @if($order->status->name == "Menunggu Konfirmasi Penjual")
            <div class="alert alert-info">
                <span>Batas Konfirmasi Pesanan Sampai {{ date('d F Y H:i:s', strtotime($order->purchase_deadline_date)) }}</span>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informasi Pesanan</h5>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Pembeli</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $user->name }}</label>
                                        @if($user->id != auth()->user()->id)
                                            <a target="_blank" href="{{ url('chat/'.$user->id.'') }}" class="btn btn-sm btn-outline-danger"><i class="bi bi-chat"></i> Kirim Pesan</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Departemen Pembeli</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $user->lpse->nama_satker ?? '-' }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Nama Penjual</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $merchant->name }}</label>
                                        @if($merchant->pic->id != auth()->user()->id)
                                            <a target="_blank" href="{{ url('chat/'.$merchant->pic->id.'') }}" class="btn btn-sm btn-outline-danger"><i class="bi bi-chat"></i> Kirim Pesan</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">PIC Penjual</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $merchant->pic->name }} ({{ $merchant->phone }})</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">No. Penawaran</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label"><a href="{{ url('dashboard/quotation/'.$order->channel->quotation_number.'') }}">{{ $order->channel->quotation_number ?? '-' }}</a></label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">No. Pesanan</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $order->order_number ?? '-' }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Status</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{!! Helpers::colorStatus($order->status->name) ?? '-' !!}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Waktu Transaksi</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $date }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Estimasi Pesanan Dikirim</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $orderShippedEstimation }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-4 col-form-label">Keperluan</label>
                                    <div class="col-sm-8">
                                        <p>{{ $order->quotation->purpose_of }}</p>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-4 col-form-label">Alamat Penjual</label>
                                    <div class="col-sm-8">
                                        <label class="col-form-label">{{ $merchantAddress }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-4 col-form-label">Alamat Pengiriman</label>
                                    <div class="col-sm-8">
                                        <p>{{ $shippingAddress }}</p>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-4 col-form-label">Alamat Tagihan</label>
                                    <div class="col-sm-8">
                                        <p>{{ $billingAddress }}</p>
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
                        <h5 class="card-title">Informasi Lainnya</h5>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Nama Penerima</label>
                            <div class="col-sm-8">
                                <p>{{ $order->quotation->user_pic }}</p>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">No. Telp Penerima</label>
                            <div class="col-sm-8">
                                <p>{{ $order->quotation->user_phone }}</p>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Catatan Untuk Penjual</label>
                            <div class="col-sm-8">
                                {!! $order->quotation->notes_for_merchant ?? '-' !!}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Catatan Untuk Pembeli</label>
                            <div class="col-sm-8">
                                {!! $order->quotation->notes_for_buyer !!}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Tipe Pembayaran</label>
                            <div class="col-sm-8">
                                <p>{{$order->quotation->payment_type}} {{ $order->quotation->termin != '' ? ' ('.$order->quotation->termin.'Hari)' : '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informasi Pengiriman</h5>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Jasa Pengiriman</label>
                            <div class="col-sm-8">
                                <p>{{ $order->quotation->shippingRequest->shippingMethod->method_name }}</p>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Biaya Pengiriman</label>
                            <div class="col-sm-8">
                                <p>{{ "Rp. ". number_format($order->quotation->shippingRequest->shipping_fee,0,'.','.') }}</p>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Estimasi Pengiriman</label>
                            <div class="col-sm-8">
                                <p>{{ $order->quotation->shippingRequest->date_estimation. " Hari" }}</p>
                            </div>
                        </div>
                        @if($order->status->name == "Pesanan Diproses" && Auth::user()->hasRole('Customer') == false)
                            <a class="btn btn-outline-danger" id="shipping">Proses Pengiriman</a>
                        @endif
                        @if($order->deliveryOrder()->count() > 0)
                            <div class="row mb-2">
                                <label for="inputText" class="label-text col-sm-4 col-form-label">Status Pengiriman</label>
                                <div class="col-sm-8">
                                    <p>{{ $order->deliveryOrder->deliveryStatus->name }}</p>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <label for="inputText" class="label-text col-sm-4 col-form-label">No. Order Pengiriman</label>
                                <div class="col-sm-8">
                                    <a href="{{ url('dashboard/shipping-order/'.$order->deliveryOrder->do_number.'') }}">{{ $order->deliveryOrder->do_number }}</a>
                                </div>
                            </div>
                            <div class="table table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <th>No. Resi</th>
                                    <th>Penerima</th>
                                    <th>Tanggal Kirim</th>
                                    <th>Estimasi Sampai</th>
                                    <th>Tanggal Sampai</th>
                                    </thead>
                                    <tr>
                                        <td>{{ $order->deliveryOrder->delivery_number }}</td>
                                        <td>{{ $order->deliveryOrder->delivery_recipient_name }}</td>
                                        <td>{{ date('d F Y', strtotime($order->deliveryOrder->delivery_date)) }}</td>
                                        <td>{{ date('d F Y', strtotime($order->deliveryOrder->date_estimation)) }}</td>
                                        <td>{{ $order->deliveryOrder->delivered_date ? date('d F Y', strtotime($order->deliveryOrder->delivered_date)) : '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            @if(
                                $order->deliveryOrder->deliveryStatus->name == 'Dalam Pengiriman'
                                && $order->status->name != 'Selesai'
                                && Auth::user()->hasRole('Customer')
                            )
                            <div class="row">
                                {{-- <div class="col-6">
                                </div> --}}
                                {{-- <div class="col-sm-12 col-lg-3">
                                    <a id="complainDO" class="btn btn-outline-primary btn-md w-100 reject"><i class="bi bi-x-lg"></i> Komplain</a>
                                </div> --}}
                                <div class="col-sm-12 col-lg-3">
                                    <a id="confirmDO" class="btn btn-outline-danger btn-md w-100 submit"><i class="bi bi-check2"></i> Sudah Diterima</a>
                                </div>
                            </div>
                            @endif
                            <div class="modal fade" id="doModal" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Konfirmasi Pengiriman</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <form id="confirmDeliveryOrder">
                                                            <div class="row">
                                                                <div class="col-lg-12 mb-2">
                                                                    <label class="mt-2">Nama Penerima</label>
                                                                    <input name="delivery_recipient_name" class="form-control" value="{{ $order->deliveryOrder->delivery_recipient_name }}">
                                                                    <span id="message_delivery_recipient_name" class="invalid-feedback" style="display:none">
                                                                    </span>
                                                                </div>
                                                                <div class="col-lg-12 mb-2">
                                                                    <label class="mt-2">Tanggal Terima</label>
                                                                    <input name="delivered_date" class="form-control" value="{{ date('Y-m-d') }}">
                                                                    <span id="message_delivered_date" class="invalid-feedback" style="display:none">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="modal-footer">
                                            <button id="confirmDeliveryOrder" type="button" class="w-100 btn btn-outline-danger"><i class="bi bi-save"></i> Selesaikan Pengiriman</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Dokumen Pendukung</h5>
                        <div class="row mb-2">
                            <div class="col-lg-6">
                                <span id="file-upload-form" action="{{ url('') }}">
                                    <input id="file-upload" type="file" />
                                    <label for="file-upload" id="file-drag">
                                        Klik untuk unggah dokumen
                                        <br />Atau
                                        <br />Tarik dokumen ke sini
                                    </label>	
                                    <div class="progress" style="display:none">
                                        <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                        <div class="info"></div>
                                    </div>
                                    <div class="img-thumbs img-thumbs-hidden" id="img-preview"></div>
                                    <div id="message"></div>
                                    <div id="outputFile"></div>
                                </span>
                            </div>
                            <div class="col-lg-6">
                                <table class="table table-bordered">
                                    <thead class="bg-danger text-white">
                                        <th>No</th>
                                        <th>Dokumen</th>
                                        <th>Diupload Oleh</th>
                                        <th>Aksi</th>
                                    </thead>
                                    <tbody>
                                        @foreach($order->documents()->get() as $key => $documents)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td><a href="{{ url($documents->media->path) }}" target="_blank">{{ $documents->media->name }}</a></td>
                                                <td>{{ $documents->user->name }}</td>
                                                <td>
                                                    @if(Auth::user()->id == $documents->user->id)
                                                    <a href="#" onclick="deleteDocument({{ $documents->media->id }}); return false;"><i class="bi bi-trash text-danger"></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if($order->documents->count() == 0)
                                    <p class="text-center">Dokumen Tidak Ditemukan</p>
                                @endif
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
                                        <td>{{ $prod->quantity }}</td>
                                        <td>
                                            {{ "Rp. ". number_format($prod->base_price,2,'.',',') }}
                                        </td>
                                        <td>
                                            {{ "Rp. ". number_format($prod->subtotal,2,'.',',') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" rowspan="2" style="vertical-align : middle;text-align:center;"><div class="text-center">Pajak</div></td>
                                        <td>
                                            PPN ({{ $prod->tax_percentage*100 }}%)
                                        </td>
                                        <td><span>Rp. {{ number_format($prod->tax_amount,2,',','.') }}</span></td>
                                    </tr>
                                    <tr>
                                        <td style="width:20%">
                                            PPH ({{ $prod->income_tax_percentage*100 }}%)
                                        </td>
                                        <td style="width:1%">
                                            (Rp. {{ number_format($prod->income_tax_amount,2,',','.') }})
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-12" style="margin-right:auto;">
                            <table class="mt-2 table table-bordered" style="width:40%;float:right">
                                <tr>
                                    <th rowspan="{{ $order->discount > 0 ? "2" : "" }}" style="width:50%">Subtotal</th>
                                    <td>
                                        <span class="text-right">
                                            {!! 
                                                $order->discount > 0 ?
                                                '<del> Rp. '.number_format($order->subtotal + $order->discount,2,',','.'). '</del>'
                                                :
                                                'Rp. '. number_format($order->subtotal,2,',','.')
                                            !!}
                                        </span>
                                    </td>
                                    @if($order->discount > 0)
                                    <tr>
                                        <td>
                                            <span class="text-right">Rp. {{ number_format($order->subtotal,2,',','.') }}</span>
                                        </td>
                                    </tr>
                                    @endif
                                </tr>
                                <tr>
                                    <th style="width:50%">PPN</th>
                                    <td><span class="text-right" id="totalPpn">Rp. {{ number_format($order->tax_amount,2,',','.') }}</td>
                                </tr>
                                <tr>
                                    <th style="width:50%">PPH</th>
                                    <td><span class="text-right" id="totalPph">(Rp. {{ number_format($order->income_tax,2,',','.') }})</td>
                                </tr>
                                <tr>
                                    <th style="width:50%">Biaya Pengiriman</th>
                                    <td><span class="text-right" id="shippingAmount">Rp. {{ number_format($order->shipping_amount,2,'.',',') }}</td>
                                </tr>
                                <tr class="bg-danger text-white">
                                    <th style="width:50%">Grand Total</th>
                                    <td><span class="text-right" id="grandTotal">Rp. {{ number_format($order->grand_total,2,',','.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Aktivitas</h5>
                        <div class="activity">
                            @foreach($activities as $activity)
                                <div class="activity-item d-flex">
                                    <div class="activite-label">{{ date('d F Y', strtotime($activity->created_at)) }}</div>
                                    <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
                                    <div class="activity-content">
                                        {!! $activity->description !!}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
           @if($allowEdited == true)
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body> p-3">
                            <div class="row">
                                {{-- <div class="col-1">
                                    <h5 class="mt-2">Proses?</h5>
                                </div> --}}
                                @if(auth()->user()->getRoleNames()[0] == "Customer")
                                @else
                                    <div class="col-sm-12 col-lg-3">
                                        <a id="rejectPO" class="btn btn-outline-primary btn-md w-100 reject"><i class="bi bi-save2"></i> Batalkan Pesanan</a>
                                    </div>
                                    <div class="col-sm-12 col-lg-3">
                                        <a id="confirmPO" class="btn btn-outline-danger btn-md w-100 submit"><i class="bi bi-save"></i> Konfirmasi Pesanan</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </form>
</section>
<div class="modal fade" id="shippingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Proses Pengiriman</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="deliveryForm">
                            <div class="row">
                                <div class="col-lg-12 mb-2">
                                    <label class="mt-2">Dikirim Dari</label>
                                    <div class="alert alert-secondary mt-2"><b>{{ $merchantAddress }}</b></div>
                                </div>
                                <div class="col-lg-12 mb-2">
                                    <label class="mt-2">Dikirim Ke</label>
                                    <div class="alert alert-secondary mt-2"><b>{{ $shippingAddress }}</b></div>
                                </div>
                                <div class="col-lg-12 mb-4">
                                    <label>No. Resi / Pengiriman</label>
                                    <input type="text" name="delivery_number" class="form-control">
                                    <span id="message_delivery_number" class="invalid-feedback" style="display:none">
                                    </span>
                                </div>
                                <div class="col-lg-12 mb-4">
                                    <label>Tanggal Kirim</label>
                                    <input type="date" name="delivery_date" class="form-control" value="{{ date('Y-m-d') }}">
                                    <span id="message_delivery_date" class="invalid-feedback" style="display:none">
                                    </span>
                                </div>
                                <div class="col-lg-12 mb-4">
                                    <label>Estimasi Sampai</label>
                                    <input type="date" name="delivery_estimation_date" class="form-control" value="{{ $dateEstimation }}">
                                    <small class="text-muted">Estimasi Waktu Pengiriman : {{ $order->quotation->shippingRequest->date_estimation }} Hari</small>
                                    <span id="message_delivery_estimation_date" class="invalid-feedback" style="display:none">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button id="placeDeliveryOrder" type="button" class="w-100 btn btn-outline-danger"><i class="bi bi-save"></i> Proses Order Pengiriman</button>
        </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ url('admin/assets/vendor/ckeditor2/ckeditor.js') }}"></script>
    <script>

    $("#confirmDO").click(function() {
        $("#doModal").modal('show');
    });

    $("#confirmDeliveryOrder").click(function() {
        $("#confirmDeliveryOrder").attr('disabled', true).html("<img src='{{ url('images/loading2.gif') }}' style='height:20px'> Loading...");
        $.ajax({
            url : "{{ url('dashboard/shipping-order/confirm') }}",
            type : 'POST',
            dataType : 'json',
            data : {
                do_number : "{{ $order->deliveryOrder->do_number ?? '' }}",
                delivery_recipient_name : $("[name=delivery_recipient_name]").val(),
                delivered_date : $("[name=delivered_date]").val()
            },
            headers : {
                'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
            },
            success:function(res) {
                $("#confirmDeliveryOrder").attr('disabled', false).html('Save');
                if(res.status == "success") {
                    window.location.reload();
                }
            }, 
            error:function(jqXHR){
                $("#confirmDeliveryOrder").attr('disabled', false).html('Save')
                var json = jqXHR.responseJSON;
                printError(json.errors);
            }
        })
    });

    $("#shipping").click(function() {
        $("#shippingModal").modal('show');
    });

    $("#confirmPO").click(function() {
        $(".submit").attr('disabled', true).html("<img src='{{ url('images/loading2.gif') }}' style='height:20px'> Loading...");
        setTimeout(() => {
            $.ajax({
                url : "{{ url('dashboard/purchase-order/confirm')  }}",
                type : 'POST',
                dataType : 'json',
                data : {
                    order_number : "{{ $order->order_number }}"
                },
                headers : {
                    'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
                },
                success:function(res) {
                    $(".submit").attr('disabled', false).html('Save');
                    if(res.status == "success") {
                        window.location.reload();
                    }
                }, 
                error:function(jqXHR){
                    $(".submit").attr('disabled', false).html('Save')
                    var json = jqXHR.responseJSON;
                    //if(typeof json.status !== 'undefined') {
                    printError(json.errors);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    //}
                }
            });
        }, 1000);
    });

    $("#rejectPO").click(function() {
        swal({
            text: 'Masukan Pesan Kepada Pembeli Untuk Menolak Pesanan {{ $order->order_number }}',
            content: "input",
            icon: "info",
            buttons: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
        })
        .then(resp => {
            if (!resp){
                swal({
                    text    : "Pesan Harus Di isi",
                    icon    : "error"
                })
            };
            $.ajax({
                url : "{{ url('dashboard/purchase-order/reject') }}",
                type : 'POST',
                dataType : 'JSON',
                headers : {
                    'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
                },
                data : {
                    order_number : "{{ $order->order_number }}",
                    rejected_reason : resp
                },
                success:function() {
                    window.location.reload();
                }
            })
        })
        .catch(err => {
            if (err) {
                swal("Oh noes!", "The AJAX request failed!", "error");
            } else {
                swal.stopLoading();
                swal.close();
            }
        });
    });

    $("#placeDeliveryOrder").click(function() {
        $("#placeDeliveryOrder").attr('disabled', true).html("<img src='{{ url('images/loading2.gif') }}' style='height:20px'> Loading...");
        setTimeout(() => {

            var data = new FormData($("#deliveryForm")[0]);
            data.append('order_number', '{{ $order->order_number }}');

            $.ajax({
                url : "{{ url('dashboard/shipping-order')  }}",
                type : 'POST',
                dataType : 'json',
                contentType: false,
                processData: false,
                data : data,
                headers : {
                    'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
                },
                success:function(res) {
                    $("#placeDeliveryOrder").attr('disabled', false).html('Save');
                    if(res.status == "success") {
                        window.location.reload();
                    }
                }, 
                error:function(jqXHR){
                    $("#placeDeliveryOrder").attr('disabled', false).html('Proses Pengiriman')
                    var json = jqXHR.responseJSON;
                    printError(json.errors);
                }
            });
        }, 1000);
    });

    deleteDocument = (id) => {
        swal({
            title: "Are you sure?",
            text: "Data akan dihapus secara soft delete dan akan tetap tersimpan di database",
            icon: "warning",
            buttons: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url : `{{ url('dashboard/purchase-order-documents/${id}') }}`,
                    type : 'DELETE',
                    dataType: 'JSON',
                    data : { 
                        _token : $("meta[name='csrf_token']").attr('content')
                    },
                    success:function(res) {
                        swal(res.message, {
                            icon: "success",
                        });
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

    (function() {
        function Init() {
            var fileSelect = document.getElementById('file-upload'),
                fileDrag = document.getElementById('file-drag'),
                submitButton = document.getElementById('submit-button');

            fileSelect.addEventListener('change', fileSelectHandler, false);
            fileDrag.addEventListener('dragover', fileDragHover, false);
            fileDrag.addEventListener('dragleave', fileDragHover, false);
            fileDrag.addEventListener('drop', fileSelectHandler, false);
        }

        function fileDragHover(e) {
            var fileDrag = document.getElementById('file-drag');
            e.stopPropagation();
            e.preventDefault();
            fileDrag.className = (e.type === 'dragover' ? 'hover' : 'modal-body file-upload');
        }

        function fileSelectHandler(e) {
            // Fetch FileList object
            var files = e.target.files || e.dataTransfer.files;

            // Cancel event and hover styling
            fileDragHover(e);

            // Process all File objects
            for (var i = 0, f; f = files[i]; i++) {
                //parseFile(f);
                uploadFile(f);
            }
        }

        const bytesToSize = function(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';
            var k = 1024;
            var dm = decimals < 0 ? 0 : decimals;
            var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
            var i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        function uploadFile(file) {
            var data = new FormData();
            data.append('file', file);
            data.append('path', 'documents');
            data.append('media_type', 'documents');
            data.append('number', '{{ $order->order_number }}');
            $('.progress').show();
            $('#message').html('');
            $.ajax({
                xhr : function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(e) {
                        var bytesLoaded = e.loaded;
                        var sizeFiles = e.total;
                        var percent = Math.round((bytesLoaded / sizeFiles) * 100);
                        $('#progressBar').attr('aria-valuenow', percent).css('width', percent + '%').text(percent + '%');
                        $('.info').html(bytesToSize(bytesLoaded)+" dari "+bytesToSize(sizeFiles));
                    });
                    return xhr;
                },
                type : 'POST',
                url : "{{ url('dashboard/purchase-order-documents') }}",
                dataType : 'JSON',
                data : data,
                contentType: false,
                processData: false,
                headers : {
                    'X-CSRF-TOKEN' : $('meta[name="csrf_token"]').attr('content'),
                },
                success:function(res) {
                    $('.progress').hide();
                    window.location.reload();
                },
                error:function(jqXHR) {
                    $('.progress').hide();
                    $('#message').html("<p class='text-danger'>"+jqXHR.responseJSON.message+"</p>");
                }
            });
        }

        if (window.File && window.FileList && window.FileReader) {
            Init();
        } else {
            document.getElementById('file-drag').style.display = 'none';
        }
    })();

    </script>
@endpush