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
        <a href="{{ url('dashboard/quotation/'.$quote->number.'/download') }}" target="_blank" class="btn btn-outline-danger mb-4"><i class="bi bi-file-earmark-richtext"></i> Cetak Permintaan</a>
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
                                        @if($user->id != auth()->user()->id)
                                            <a target="_blank" href="{{ url('chat/'.$user->id.'') }}" class="btn btn-sm btn-outline-danger"><i class="bi bi-chat"></i> Kirim Pesan</a>
                                        @endif
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
                                        @if($merchant->pic->id != auth()->user()->id)
                                            <a target="_blank" href="{{ url('chat/'.$merchant->pic->id.'') }}" class="btn btn-sm btn-outline-danger"><i class="bi bi-chat"></i> Kirim Pesan</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Penjual Kena Pajak?</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $merchant->is_pkp == "Y" ? 'Ya' : 'Tidak' }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">No. Permintaan</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $quote->number ?? '-' }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">No. Penawaran</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label"><a href="{{ url('dashboard/quotation/'.$quote->quotation_number.'') }}">{{ $quote->quotation_number ?? '-' }}</a></label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Status</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{!! Helpers::colorStatus($quote->quoteStatus->name) ?? '-' !!}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Waktu Transaksi</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $date }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-4 col-form-label">Keperluan</label>
                                    <div class="col-sm-8">
                                        <p>{{ $quote->purpose_of }}</p>
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
                        <h5 class="card-title">Informasi Penerima & Tipe Pembayaran</h5>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Nama Penerima</label>
                            <div class="col-sm-8">
                                <p>{{ $quote->user_pic }}</p>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">No. Telp Penerima</label>
                            <div class="col-sm-8">
                                <p>{{ $quote->user_phone }}</p>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Catatan Untuk Penjual</label>
                            <div class="col-sm-8">
                                {!! $quote->notes_for_merchant !!}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Tipe Pembayaran</label>
                            <div class="col-sm-8">
                                <p>{{$quote->payment_type}} {{ $quote->termin != '' ? ' ('.$quote->termin.'Hari)' : '' }}</p>
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
                                        <td>{{ 'Rp. '. number_format($prod->base_price,2,',','.') }}</td>
                                        <td>
                                            {{ 'Rp. '. number_format($prod->subtotal,2,',','.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" rowspan="2" style="vertical-align : middle;text-align:center;"><div class="text-center">Pajak</div></td>
                                        <td>PPN <b>({{ $prod->tax_percentage * 100 }}%)</b></td>
                                        <td>Rp. {{ number_format($prod->tax_amount,2,',','.') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="width:20%">
                                            PPH <b>({{ $prod->income_tax_percentage * 100 }}%)</b>
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
                                    <th rowspan="{{ $quote->discount > 0 ? "2" : "" }}" style="width:50%">Subtotal</th>
                                    <td>
                                        <span class="text-right" id="totalSubTotal">
                                            {!! 
                                                $quote->discount > 0 ?
                                                '<del> Rp. '.number_format($quote->subtotal + $quote->discount,2,',','.'). '</del>'
                                                :
                                                'Rp. '. number_format($quote->subtotal,2,',','.')
                                            !!}
                                        </span>
                                    </td>
                                    @if($quote->discount > 0)
                                    <tr>
                                        <td>
                                            <span class="text-right" id="totalSubTotal">Rp. {{ number_format($quote->subtotal,2,',','.') }}</span>
                                        </td>
                                    </tr>
                                    @endif
                                </tr>
                                <tr>
                                    <th style="width:50%">PPN</th>
                                    <td><span class="text-right">Rp. {{ number_format($quote->tax_amount,2,',','.') }}</td>
                                </tr>
                                <tr>
                                    <th style="width:50%">PPH</th>
                                    <td><span class="text-right">(Rp. {{ number_format($quote->income_tax,2,',','.') }})</td>
                                </tr>
                                <tr class="bg-danger text-white">
                                    <th style="width:50%">Grand Total</th>
                                    <td><span class="text-right">Rp. {{ number_format($quote->grand_total,2,',','.') }}</td>
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
        </div>
    </form>
</section>
@endsection