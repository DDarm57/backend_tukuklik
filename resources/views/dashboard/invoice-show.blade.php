@extends('layouts.dashboard.app', ['title' => 'Tagihan'])

@push('styles')
<style>
    .label-text {
        font-weight:600;
    }
</style>
@endpush

@section('content')
<div class="pagetitle">
    <h1>Tagihan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Tagihan</li>
            <li class="breadcrumb-item active">Tagihan</li>
        </ol>
    </nav>
</div>
<section class="section">
    <form method="POST">
        <a href="{{ url('dashboard/invoice/'.$invoice->invoice_number.'/download') }}" target="_blank" class="btn btn-outline-danger mb-4"><i class="bi bi-file-earmark-richtext"></i> Cetak Tagihan</a>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informasi Tagihan</h5>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Penjual</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $invoice->order->quotation->merchant->name }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Pembeli</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $invoice->order->quotation->user->name }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">No. PO</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label"><a href="{{ url('dashboard/purchase-order/'.$invoice->order_number.'') }}">{{ $invoice->order_number ?? '-' }}</a></label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Status</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{!! Helpers::colorStatus($invoice->status) ?? '-' !!}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Tanggal Tagihan</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $date }}</label>
                                    </div>
                                </div>
                                @if($invoice->payment_method_id != null)
                                    @if($invoice->histories()->count() > 0 && $invoice->paymentMethod->payment_service == "Manual")
                                        <div class="row mb-1">
                                            <label for="inputText" class="label-text col-sm-6 col-form-label">Bukti Transfer</label>
                                            <div class="col-sm-6">
                                                <img src="{{ Storage::url($invoice->histories->evidence) }}" style="width:100px;">
                                                <br/>
                                                <a href="#" onclick="downloadImage('{{ url(Storage::url($invoice->histories->evidence)) }}', 'Bukti Transfer.jpg'); return false;">
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row mb-1">
                                        <label for="inputText" class="label-text col-sm-6 col-form-label">Tgl Bayar</label>
                                        <div class="col-sm-6">
                                            <label class="col-form-label">{{ date('d F Y H:i:s', strtotime($invoice->paid_date)) }}</label>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <label for="inputText" class="label-text col-sm-6 col-form-label">Metode Pembayaran</label>
                                        <div class="col-sm-6">
                                            <label class="col-form-label">{{ $invoice->paymentMethod->payment_name ?? '-' }}</label>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <label for="inputText" class="label-text col-sm-6 col-form-label">{{ $invoice->paymentMethod->payment_service == "Manual" ? "Tujuan Transfer" : "No. Virtual Account" }}</label>
                                        <div class="col-sm-6">
                                            <label class="col-form-label" style="font-weight:600">{{ $invoice->billing_number }} {{ $invoice->paymentMethod->payment_service == "Manual" ? " Atas Nama " : "" }} {{ $invoice->paymentMethod->account_holder ?? ''  }}</label>
                                        </div>
                                    </div>
                                @endif
                                @if($invoice->status == "Menunggu Konfirmasi Penjual" && Auth::user()->hasRole('Customer') == false)
                                    <a onclick="confirmPayment()" class="btn btn-outline-danger">Konfirmasi Pembayaran</a>
                                @endif
                            </div>
                            <div class="col-lg-6">
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Jumlah Tagihan</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">Rp. {{ number_format($invoice->invoice_amount,2,',','.') }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Jatuh Tempo</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $dueDate }}</label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">Alamat Tagihan</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $billingAddress }}</label>
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
                                    <th rowspan="{{ $invoice->order->discount > 0 ? "2" : "" }}" style="width:50%">Subtotal</th>
                                    <td>
                                        <span class="text-right">
                                            {!! 
                                                $invoice->order->discount > 0 ?
                                                '<del> Rp. '.number_format($invoice->order->subtotal + $invoice->order->discount,2,',','.'). '</del>'
                                                :
                                                'Rp. '. number_format($invoice->order->subtotal,2,',','.')
                                            !!}
                                        </span>
                                    </td>
                                    @if($invoice->order->discount > 0)
                                    <tr>
                                        <td>
                                            <span class="text-right">Rp. {{ number_format($invoice->order->subtotal,2,',','.') }}</span>
                                        </td>
                                    </tr>
                                    @endif
                                </tr>
                                <tr>
                                    <th style="width:50%">PPN</th>
                                    <td><span class="text-right" id="totalPpn">Rp. {{ number_format($invoice->order->tax_amount,2,',','.') }}</td>
                                </tr>
                                <tr>
                                    <th style="width:50%">PPH</th>
                                    <td><span class="text-right" id="totalPph">(Rp. {{ number_format($invoice->order->income_tax,2,',','.') }})</td>
                                </tr>
                                <tr>
                                    <th style="width:50%">Biaya Pengiriman</th>
                                    <td><span class="text-right" id="shippingAmount">Rp. {{ number_format($invoice->order->shipping_amount,2,'.',',') }}</td>
                                </tr>
                                <tr class="bg-danger text-white">
                                    <th style="width:50%">Grand Total</th>
                                    <td><span class="text-right" id="grandTotal">Rp. {{ number_format($invoice->order->grand_total,2,',','.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @if(
                auth()->user()->getRoleNames()[0] == "Customer" 
                && 
                (   
                    $invoice->status == "Belum Dibayar"
                    ||
                    $invoice->status == "Jatuh Tempo" 
                )
                && $invoice->billing_number == ""
            )
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body> p-3">
                        <div class="row">
                            <div class="col-8">
                                <h5></h5>
                            </div>
                            <div class="col-sm-12 col-lg-4">
                                <a id="chooseMethod" class="btn btn-outline-danger btn-md w-100 submit"><i class="bi bi-check2"></i> Pilih Metode Pembayaran</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @elseif(
                auth()->user()->getRoleNames()[0] == "Customer" 
                && 
                (   
                    $invoice->status == "Belum Dibayar"
                    ||
                    $invoice->status == "Jatuh Tempo" 
                )
                && $invoice->billing_number != ""
            )
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <h5 class="card-title">
                                    SEGERA LAKUKAN PEMBAYARAN SEBELUM
                                    <br><br>
                                    {{ $dueDate }} 
                                </h5>
                                @if($invoice->paymentMethod->payment_service == "Manual")
                                    <p>
                                        Ke Akun Bank Berikut 
                                    </p>
                                    <div class="mb-2">
                                        <img class="mt-1" src="{{ Storage::url($invoice->paymentMethod->logo) }}" style="width:100px;"><br>
                                    </div>
                                    <span style="font-weight:800;font-size:16px"> {!! $invoice->billing_number. " Atas Nama ". $invoice->paymentMethod->account_holder !!} </span> 
                                    <br>
                                    <a href="#" class="btn btn-outline-danger mt-2">Salin No. Rekening</a> 
                                    <a id="havePaid" class="btn btn-outline-info mt-2">Saya Sudah Bayar</a>
                                @else
                                     <p>
                                        Ke Virtual Account Berikut 
                                    </p>
                                        <div class="">
                                            <img class="mt-1" src="{{ Storage::url($invoice->paymentMethod->logo) }}" style="width:100px;"><br>
                                        </div>
                                        <br>
                                    <b> {!! $invoice->billing_number !!} </b> <br>
                                    <a id="copyBtn" href="#">Salin Virtual Account</a>
                                    <input type="hidden" value="{{ $invoice->billing_number }}" id="copyText" readonly>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </form>
</section>
<div class="modal fade" id="billModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Pilih Metode Pembayaran</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            @foreach($paymentMethod as $method)
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row mb-1">
                                            <div class="col-sm-12 justify-content-center text-left">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <img class="mt-4" src="{{ Storage::url($method->logo) }}" style="width:100px;">
                                                    </div>
                                                    <div class="col-lg-8 mt-2">
                                                        <input name="payment" value="{{ $method->id }}" type="radio">
                                                        <label class="col-form-label">{{ $method->payment_name }}</label><br>
                                                        <span class="text-secondary text-sm">{{ $method->payment_type }}</span>
                                                        <span class="message_payment"></span>
                                                    </div>
                                                </div>
                                            </div>
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
        <div class="modal-footer">
            <button id="makePayment" type="button" class="w-100 btn btn-outline-danger"><i class="bi bi-save"></i> Bayar</button>
        </div>
        </div>
    </div>
</div>
<div class="modal fade" id="paidModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Bukti Transfer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <form id="paidForm">
                                    <label class="mt-3">Jumlah Tagihan</label>
                                    <input type="text" name="invoice_amount" id="rupiah" value="{{ number_format($invoice->invoice_amount,0,'.','.') }}" class="form-control">
                                    <span id="message_invoice_amount" class="invalid-feedback" style="display:none"></span>
                                    <label class="mt-3">Bukti Transfer</label>
                                    <input type="file" name="file" class="form-control">
                                    <span id="message_file" class="invalid-feedback" style="display:none"></span>
                                    <label class="mt-3">Tgl Bayar</label>
                                    <input type="date" name="paid_date" value="{{ date('Y-m-d') }}" class="form-control">
                                    <span id="message_file" class="invalid-feedback" style="display:none"></span>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div class="modal-footer">
                    <button id="submitPayment" type="button" class="w-100 btn btn-outline-danger"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

    <script>

    $("#chooseMethod").click(function() {
        $("#billModal").modal("show");
    });

    $("#havePaid").click(function() {
        $("#paidModal").modal('show');
    });

    $("#submitPayment").click(function() {
        $("#submitPayment").attr('disabled', true).html("<img src='{{ url('images/loading2.gif') }}' style='height:20px'> Loading...");
        var data = new FormData($("#paidForm")[0]);
        data.append('invoice_number', "{{ $invoice->invoice_number }}");
        $.ajax({
            url : "{{ url('dashboard/invoice/submit-payment') }}",
            type : 'POST',
            dataType : 'json',
            data : data,
            contentType: false,
            processData: false,
            headers : {
                'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
            },
            success:function(res) {
                $("#submitPayment").attr('disabled', false).html('<i class="bi bi-save"></i> Simpan');
                if(res.status == "success") {
                    window.location.reload();
                }
            }, 
            error:function(jqXHR){
                $("#submitPayment").attr('disabled', false).html('<i class="bi bi-save"></i> Simpan')
                var json = jqXHR.responseJSON;
                printError(json.errors);
            }
        });
    });

    $("#makePayment").click(function() {
        $("#makePayment").attr('disabled', true).html("<img src='{{ url('images/loading2.gif') }}' style='height:20px'> Loading...");
        $.ajax({
            url : "{{ url('dashboard/invoice/payment') }}",
            type : 'POST',
            dataType : 'json',
            data : {
                invoice_number : "{{ $invoice->invoice_number }}",
                payment : $("[name=payment]:checked").val()
            },
            headers : {
                'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
            },
            success:function(res) {
                $("#makePayment").attr('disabled', false).html('<i class="bi bi-save"></i> Bayar');
                if(res.status == "success") {
                    window.location.reload();
                }
            }, 
            error:function(jqXHR){
                $("#makePayment").attr('disabled', false).html('<i class="bi bi-save"></i> Bayar')
                swal(jqXHR.responseJSON.message, {
                    icon: "error",
                    title: "Error",
                });
            }
        })
    });

    function confirmPayment() {
        swal({
            title: "Are you sure?",
            text: "Apakah Anda Sudah Yakin Mengkonfirmasi Pembayaran Tersebut?",
            icon: "info",
            buttons: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
        })
        .then((willSubmit) => {
            if (willSubmit) {
                $.ajax({
                    url : `{{ url('dashboard/invoice/confirm-payment') }}`,
                    type : 'POST',
                    dataType: 'JSON',
                    data : { 
                        _token : $("meta[name='csrf_token']").attr('content'),
                        invoice_number : "{{ $invoice->invoice_number }}"
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

@push('scripts')
<script>
    const copyText = document.getElementById('copyText');
    $("#copyBtn").on('click', function(e){
    copyText.select();
    navigator.clipboard.writeText(copyText.value);
        swal("Virtual Account Berhasil Disalin", {
            icon: "success",
            title: "Sukses",
        });
        e.preventDefault();
    });

    function downloadImage(url, name){
        fetch(url)
            .then(resp => resp.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                // the filename you want
                a.download = name;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            })
            .catch(() => alert('An error sorry'));
    }
</script>
@endpush