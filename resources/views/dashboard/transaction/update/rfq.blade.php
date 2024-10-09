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
            @if($allowEdited == false)
                <div class="col-lg-12">
                    <div class="alert alert-warning">
                        Permintaan tidak bisa Diubah karena sudah dikonfirmasi penjual atau sudah kadaluwarsa
                    </div>
                </div>
            @endif
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
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Penjual Kena Pajak?</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $merchant->is_pkp == "Y" ? 'Ya' : 'Tidak' }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">No. Permintaan</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $quote->channel->rfq_number ?? '-' }}</a></label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">No. Penawaran</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label"><a href="{{ url('dashboard/quotation/'.$quote->channel->quotation_number.'') }}">{{ $quote->channel->quotation_number ?? '-' }}</a></label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Status</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $quote->quoteStatus->name ?? '-' }}</label>
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
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Keperluan</label>
                            <div class="col-sm-8">
                                <p>{{ $quote->purpose_of ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Catatan Untuk Penjual</label>
                            <div class="col-sm-8">
                                {!! $quote->notes_for_merchant ?? '-' !!}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Catatan Untuk Pembeli</label>
                            <div class="col-sm-8">
                                <textarea id="notesBuyer" class="form-control w-100" name="notes_for_buyer" rows="100" cols="50"></textarea>
                                <span id="notes_for_buyer" class="invalid-feedback" style="display:none">
                                </span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Tipe Pembayaran</label>
                            <div class="col-sm-8">
                                <select class="selects form-control" name="payment_type">
                                    <option></option>
                                    @foreach($choosePaymentType as $type)
                                        <option {{ $paymentType == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                <span id="message_payment_type" class="invalid-feedback" style="display:none">
                                </span>
                                <div id="termin" class="mt-2 w-50" style="{{ $paymentType == "Term Of Payment" ? '' : 'display:none' }}">
                                    <div class="input-group mb-3"> 
                                        <input value="{{ $quote->termin }}" type="number" name="termin" placeholder="Termin..." class="form-control"> 
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
                        <h5 class="card-title">Informasi Pengiriman</h5>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Jasa Pengiriman</label>
                            <div class="col-sm-8 mb-3">
                                <select class="selects form-control" name="shipping_method" id="paymentType">
                                    <option></option>
                                    @foreach($shippingMethod as $method)
                                        <option value="{{ $method->id }}">{{ $method->method_name }}</option>
                                    @endforeach
                                </select>
                                <span id="message_shipping_method" class="invalid-feedback" style="display:none">
                                </span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Biaya Pengiriman</label>
                            <div class="col-sm-3">
                                <div class="input-group mb-3"> 
                                    <span class="input-group-text" id="basic-addon2">Rp</span>
                                    <input value="0" id="rupiah" name="shipping_fee" class="form-control" type="text">
                                    <span id="message_shipping_fee" class="invalid-feedback" style="display:none">
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <small class="text-muted">Estimasi Biaya {{ Helpers::getShippingFeeEstimation($quote->number) }}</small>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Estimasi Pengiriman</label>
                            <div class="col-sm-3">
                                <div class="input-group mb-3"> 
                                    <input name="date_estimation" class="form-control" type="text">
                                    <span class="input-group-text" id="basic-addon2">Hari</span>
                                    <span id="message_date_estimation" class="invalid-feedback" style="display:none">
                                    </span>
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
                            <table class="table">
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
                                        <td id="quantity">{{ $prod->quantity }}</td>
                                        <td>
                                            <div class="input-group mb-3"> 
                                                <span class="input-group-text" id="basic-addon2">Rp</span>
                                                <input id="rupiah" name="base_price[]" class="form-control basePrice" type="text" value="{{ number_format($prod->base_price,0,'.','.') }}">
                                            </div>
                                        </td>
                                        <td>
                                            <span id="subTotal">{{ 'Rp. '. number_format($prod->subtotal,2,',','.') }}</span>
                                            <input id="subTotalTemp" type="hidden" value="{{ $prod->subtotal }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" rowspan="2" style="vertical-align : middle;text-align:center;"><div class="text-center">Pajak</div></td>
                                        <td>
                                            PPN
                                            <div class="input-group mb-3"> 
                                                <input id="inputPpn" name="ppn[]" class="form-control" type="text" value="{{ $prod->tax_percentage *100 }}">
                                                <span class="input-group-text" id="basic-addon2">%</span>
                                            </div>
                                        </td>
                                        <td><span id="taxAmount">Rp. {{ number_format($prod->tax_amount,2,',','.') }}</span></td>
                                    </tr>
                                    <tr>
                                        <td style="width:20%">
                                            PPH
                                            <div class="input-group mb-3"> 
                                                <input id="inputpph" name="pph[]" class="form-control" type="text" value="{{ $prod->income_tax_percentage *100 }}">
                                                <span class="input-group-text" id="basic-addon2">%</span>
                                            </div>
                                        </td>
                                        <td id="pph" style="width:1%">
                                            (Rp. {{ number_format($prod->income_tax_amount,2,',','.') }})
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-12" style="margin-right:auto;">
                            <table class="mt-2 table" style="width:40%;float:right">
                                <tr>
                                    <th rowspan="{{ $quote->discount > 0 ? "2" : "" }}" style="width:50%">Subtotal</th>
                                    <td>
                                        <span class="text-right" id="{{ $quote->discount == 0 ? 'totalSubTotal' : '' }}">
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
                                            <span class="text-right" id="{{ $quote->discount > 0 ? 'totalSubTotal' : '' }}"> Rp. {{ number_format($quote->subtotal,2,',','.') }}</span>
                                        </td>
                                    </tr>
                                    @endif
                                </tr>
                                <tr>
                                    <th style="width:50%">PPN</th>
                                    <td><span class="text-right" id="totalPpn">Rp. {{ number_format($quote->tax_amount,2,',','.') }}</span></td>
                                </tr>
                                <tr>
                                    <th style="width:50%">PPH</th>
                                    <td><span class="text-right" id="totalPph">(Rp. {{ number_format($quote->income_tax,2,',','.') }})</span></td>
                                </tr>
                                <tr>
                                    <th style="width:50%">Biaya Pengiriman</th>
                                    <td><span class="text-right" id="shippingAmount">Rp. 0</span></td>
                                </tr>
                                <tr class="bg-danger text-white">
                                    <th style="width:50%">Grand Total</th>
                                    <td><span class="text-right" id="grandTotal">Rp. {{ number_format($quote->grand_total,2,',','.') }}</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
           @if($allowEdited == true)
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body> p-3">
                            <div class="row">
                                <div class="col-4">
                                    <h5 class="mt-2">Proses?</h5>
                                </div>
                                <div class="col-sm-12 col-lg-2">
                                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-md w-100"><i class="bi bi-x-circle"></i> Batal</a>
                                </div>
                                <div class="col-sm-12 col-lg-3">
                                    <a id="rejectRFQ" class="btn btn-outline-warning btn-md w-100 reject"><i class="bi bi-x-circle"></i></i> Tolak Permintaan</a>
                                </div>
                                <div class="col-sm-12 col-lg-3">
                                    <a id="submitQuote" class="btn btn-outline-danger btn-md w-100 submit"><i class="bi bi-save"></i> Submit Penawaran</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </form>
</section>
@endsection

@push('scripts')
    <script src="{{ url('admin/assets/vendor/ckeditor2/ckeditor.js') }}"></script>
    <script>
        var notesBuyer = document.getElementById("notesBuyer");
        CKEDITOR.replace(notesBuyer,{
            language:'en-gb'
        });
        CKEDITOR.config.allowedContent = true;
        CKEDITOR.config.width = "100%";

        $("#submitQuote").click(function() {
            $("[name=notes_for_buyer]").val(CKEDITOR.instances['notesBuyer'].getData());
            $(".submit").attr('disabled', true).html("<img src='{{ url('images/loading2.gif') }}' style='height:20px'> Loading...");
            setTimeout(() => {
                $.ajax({
                    url : "{{ url('dashboard/request-for-quotation').'/'. $quote->number }}",
                    type : 'POST',
                    dataType : 'json',
                    contentType: false,
                    processData: false,
                    data : new FormData($("form#formRFQ")[0]),
                    headers : {
                        'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
                    },
                    success:function(res) {
                        $(".submit").attr('disabled', false).html('Save');
                        if(res.status == "success") {
                            window.location.href="/dashboard/quotation";
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
        })

        var formControl = document.querySelectorAll('.form-control');
        formControl.forEach((item, index) => {
            if("{{ $allowEdited }}" == false) {
                formControl[index].setAttribute("disabled", true);
            }
        });

        $("#rejectRFQ").click(function() {
            swal({
                text: 'Masukan Pesan Kepada Pembeli Untuk Menolak Permintaan {{ $quote->number }}',
                content: "input",
                icon: "info",
                buttons: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            })
            .then(resp => {
            if (!resp) throw null;
                $.ajax({
                    url : "{{ url('dashboard/request-for-quotation/'.$quote->number.'/reject') }}",
                    type : 'POST',
                    dataType : 'JSON',
                    headers : {
                        'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
                    },
                    data : {
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
    </script>
    @include('dashboard.javascript.js-rfq')
@endpush