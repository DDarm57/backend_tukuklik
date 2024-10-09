@extends('layouts.dashboard.app', ['title' => 'Penawaran'])

@push('styles')
<style>
    .label-text {
        font-weight:600;
    }
</style>
@endpush

@section('content')
<div class="pagetitle">
    <h1>Penawaran</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Transaksi</li>
            <li class="breadcrumb-item active">Penawaran</li>
        </ol>
    </nav>
</div>
<section class="section">
    <form method="POST" id="formRFQ">
        <a href="{{ url('dashboard/quotation/'.$quote->number.'/download') }}" target="_blank" class="btn btn-outline-danger mb-4"><i class="bi bi-file-earmark-richtext"></i> Cetak Penawaran</a>
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
                                        <label class="col-form-label"><a href="{{ url('dashboard/request-for-quotation/'.$quote->channel->rfq_number.'') }}">{{ $quote->channel->rfq_number ?? '-' }}</a></label>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <label for="inputText" class="label-text col-sm-6 col-form-label">No. Penawaran</label>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">{{ $quote->channel->quotation_number ?? '-' }}</label>
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
                                {!! $quote->notes_for_merchant ?? '-' !!}
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Catatan Untuk Pembeli</label>
                            <div class="col-sm-8">
                                {!! $quote->notes_for_buyer !!}
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
                        <h5 class="card-title">Informasi Pengiriman</h5>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Jasa Pengiriman</label>
                            <div class="col-sm-8">
                                <p>{{ $quote->shippingRequest->shippingMethod->method_name }}</p>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Biaya Pengiriman</label>
                            <div class="col-sm-8">
                                <p>{{ "Rp. ". number_format($quote->shippingRequest->shipping_fee,0,'.','.') }}</p>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="inputText" class="label-text col-sm-4 col-form-label">Estimasi Pengiriman</label>
                            <div class="col-sm-8">
                                <p>{{ $quote->shippingRequest->date_estimation. " Hari" }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($quote->negotiations()->count() > 0)
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Negosiasi</h5>
                            <div class="news">
                                @foreach($quote->negotiations()->orderByDesc('created_at')->get() as $negotiate)
                                <div class="post-item clearfix mt-3"> 
                                    <img style="width:60px;" src="{{ $negotiate->user->photo ?? url('images/default-profile.png') }}" alt="Profile" class="rounded-circle img-thumbnail">
                                    <h4>{{ $negotiate->user->name }} - {{ $negotiate->user_id == $quote->user_id  ? 'Pembeli' : 'Penjual'  }}</h4>
                                    <p>{!! $negotiate->description !!}</p>
                                    <p>{{ date('d F Y H:i:s', strtotime($negotiate->created_at)) }}</p>
                                </div>
                                @endforeach
                            </div>
                            <label class="mt-4">Pesan</label>
                            <div class="row">
                                <div class="col-lg-10">
                                    <input type="text" class="form-control @error('description') is-invalid @enderror" id="description">
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-lg-2">
                                    <a href="#" id="sendNegotiateMessage" class="btn btn-outline-danger w-100">Kirim</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
                                    <td><span class="text-right" id="totalPpn">Rp. {{ number_format($quote->tax_amount,2,',','.') }}</td>
                                </tr>
                                <tr>
                                    <th style="width:50%">PPH</th>
                                    <td><span class="text-right" id="totalPph">(Rp. {{ number_format($quote->income_tax,2,',','.') }})</td>
                                </tr>
                                <tr>
                                    <th style="width:50%">Biaya Pengiriman</th>
                                    <td><span class="text-right" id="shippingAmount">Rp. {{ number_format($quote->shipping_amount,2,'.',',') }}</td>
                                </tr>
                                <tr class="bg-danger text-white">
                                    <th style="width:50%">Grand Total</th>
                                    <td><span class="text-right" id="grandTotal">Rp. {{ number_format($quote->grand_total,2,',','.') }}</td>
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
           @if($allowEdited == true && Auth::user()->hasRole('Customer') == true)
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body> p-3">
                            <div class="row">
                                {{-- <div class="col-1">
                                    <h5 class="mt-2">Proses?</h5>
                                </div> --}}
                                <div class="col-sm-12 col-lg-3">
                                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-md w-100"><i class="bi bi-x-circle"></i> Batal</a>
                                </div>
                                <div class="col-sm-12 col-lg-3">
                                    <a id="rejectQuote" class="btn btn-outline-warning btn-md w-100 reject"><i class="bi bi-x-circle"></i></i> Tolak Penawaran</a>
                                </div>
                                <div class="col-sm-12 col-lg-3">
                                    <a id="negotiate" class="btn btn-outline-primary btn-md w-100 reject"><i class="bi bi-save2"></i>  Negosiasi</a>
                                </div>
                                <div class="col-sm-12 col-lg-3">
                                    <a id="submitPO" class="btn btn-outline-danger btn-md w-100 submit"><i class="bi bi-save"></i> Proses Pesanan</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </form>
</section>
<div class="modal fade" id="negotiateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Form Negosiasi</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Deskripsi Permintaan Negosiasi</h5>
                            <form id="negotiate">
                                <textarea id="negotiateDesc" name="description" class="form-control" style="height:200px;"></textarea>
                                <span id="message_description" class="invalid-feedback" style="display:none">
                                </span>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a id="negotiateQuote" type="button" class="btn btn-outline-danger w-100"><i class="bi bi-save"></i> Kirim Permintaan Negosiasi</a>
        </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ url('admin/assets/vendor/ckeditor2/ckeditor.js') }}"></script>
    <script>

    $("#negotiate").click(function() {
        $("#negotiateModal").modal('show');
    });

    $("#negotiateQuote").click(function() {
        $("#negotiateQuote").attr('disabled', true).html("<img src='{{ url('images/loading2.gif') }}' style='height:20px'> Loading...");
        $.ajax({
            url : "{{ url('dashboard/quotation/'.$quote->number.'/negotiation') }}",
            type : "POST",
            dataType : "json",
            contentType: false,
            processData: false,
            data : new FormData($("form#negotiate")[0]),
            headers : {
                'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
            },
            success:function(res) {
                $("#negotiateQuote").attr('disabled', false).html('<i class="bi bi-save"></i> Kirim Permintaan Negosiasi');
                $("#negotiateModal").modal('hide');
                if(res.status == "success") {
                    window.location.href="/dashboard/quotation";
                }
            },
            error:function(jqXHR){
                $("#negotiateQuote").attr('disabled', false).html('<i class="bi bi-save"></i> Kirim Permintaan Negosiasi');
                var json = jqXHR.responseJSON;
                //if(typeof json.status !== 'undefined') {
                printError(json.errors);
                //}
            }
        })
    });

    $("#submitPO").click(function() {
        $(".submit").attr('disabled', true).html("<img src='{{ url('images/loading2.gif') }}' style='height:20px'> Loading...");
        setTimeout(() => {
            $.ajax({
                url : "{{ url('dashboard/purchase-order')  }}",
                type : 'POST',
                dataType : 'json',
                data : {
                    quotation_number : "{{ $quote->number }}"
                },
                headers : {
                    'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
                },
                success:function(res) {
                    $(".submit").attr('disabled', false).html('Save');
                    if(res.status == "success") {
                        window.location.href="/dashboard/purchase-order";
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

    $("#sendNegotiateMessage").click(function(e) {
        e.preventDefault();
        $.ajax({
            url : "{{ url('dashboard/quotation/'.$quote->number.'/send-negotiate-message') }}",
            type : 'POST',
            dataType : 'JSON',
            headers : {
                'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
            },
            data : {
                description : $("#description").val()
            },
            success:function() {
                window.location.reload();
            }
        });
    });

    $("#rejectQuote").click(function() {
        swal({
            text: 'Masukan Pesan Kepada Penjual Untuk Menolak Penawaran {{ $quote->number }}',
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
                url : "{{ url('dashboard/quotation/'.$quote->number.'/reject') }}",
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

    var formControl = document.querySelectorAll('.form-control');
    formControl.forEach((item, index) => {
        if("{{ $allowEdited }}" == false && "{{ $quote->quoteStatus->name }}" != "Negosiasi") {
            formControl[index].setAttribute("disabled", true);
        }
    })
    </script>
@endpush