@extends('layouts.dashboard.app', ['title' => 'Pengiriman'])

@push('styles')
<style>
    .label-text {
        font-weight:600;
    }
</style>
@endpush

@section('content')
<div class="pagetitle">
    <h1>Order Pengiriman</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Pengiriman</li>
            <li class="breadcrumb-item active">Order Pengiriman</li>
        </ol>
    </nav>
</div>
<section class="section">
    <form method="POST">
        <a href="{{ url('dashboard/shipping-order/'.$deliveryOrder->do_number.'/download') }}" target="_blank" class="btn btn-outline-danger mb-4"><i class="bi bi-file-earmark-richtext"></i> 
            Cetak Pengiriman
        </a>
        <a href="{{ url('dashboard/shipping-order-bast/'.$deliveryOrder->do_number.'/download') }}" target="_blank" class="btn btn-outline-danger mb-4"><i class="bi bi-file-earmark-richtext"></i> 
            Cetak BAST
        </a>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informasi Pengiriman</h5>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Pengirim</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $merchant->name }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Penerima</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $deliveryOrder->delivery_recipient_name }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">PIC Penjual</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $merchant->pic->name }} ({{ $merchant->phone }})</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">No. PO</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label"><a href="{{ url('dashboard/purchase-order/'.$deliveryOrder->order_number.'') }}">{{ $deliveryOrder->order_number ?? '-' }}</a></label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">No. Resi</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $deliveryOrder->delivery_number ?? '-' }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Status</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{!! Helpers::colorStatus($deliveryOrder->deliveryStatus->name) ?? '-' !!}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Tanggal Pengiriman</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $date }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Biaya Pengiriman</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">Rp. {{ number_format($deliveryOrder->shippingRequest->shipping_fee,0,'.','.') }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Estimasi Sampai</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ date('d F Y', strtotime($deliveryOrder->date_estimation)) }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Dikirim Dari</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $merchantAddress }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Dikirim Ke</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $shippingAddress }}</label>
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
                        <h5 class="card-title">Informasi Paket Pengiriman</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-danger text-white">
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th>Kuantitas</th>
                                    <th>Total Berat</th>
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
                                        <td>{{ $prod->quantity }}</td>
                                        <td>{{ $prod->quantity * ($prod->productSku->weight / 1000). " Kg" }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @if(
                Auth::user()->hasRole('Customer')
                && $deliveryOrder->deliveryStatus->name == 'Dalam Pengiriman'
                && $deliveryOrder->purchaseOrder->status->name != 'Selesai'
            )
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body> p-3">
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
                    </div>
                </div>
            </div>
            @endif
        </div>
    </form>
</section>
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
                            <form id="">
                            <div class="row">
                                <div class="col-lg-12 mb-2">
                                    <label class="mt-2">Nama Penerima</label>
                                    <input name="delivery_recipient_name" class="form-control" value="{{ $deliveryOrder->delivery_recipient_name }}">
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
@endsection

@push('scripts')
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
                do_number : "{{ $deliveryOrder->do_number }}",
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
</script>
@endpush