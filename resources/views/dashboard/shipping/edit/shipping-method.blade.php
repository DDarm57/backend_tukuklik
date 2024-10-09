@extends('layouts.dashboard.app', ['title' => 'Ubah Metode Pengiriman'])

@section('content')
    <div class="pagetitle">
        <h1>Ubah Metode Pengiriman</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Pengiriman</li>
                <li class="breadcrumb-item"><a href="{{ url('dashboard/shipping-method') }}">Metode Pengiriman</a></li>
                <li class="breadcrumb-item active">Update</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form method="POST" action="{{ route('shipping-method.update', $shippingMethod->id) }}">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Informasi Metode Pengiriman</h5>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Nama Metode</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('method_name') ?? $shippingMethod->method_name }}" name="method_name" class="form-control @error('method_name') is-invalid @enderror">
                                    @error('method_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Biaya / Kg</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('cost') ?? $shippingMethod->cost }}" name="cost" class="form-control @error('cost') is-invalid @enderror">
                                    @error('cost')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Aktif?</label>
                                <div class="col-sm-10">
                                    <select class="selects  @error('is_active') is-invalid @enderror" name="is_active" class="form-control">
                                        <option></option>
                                        <option value="1" {{ old('is_active') == "1" || $shippingMethod->is_active == "1" ? 'selected' : '' }}>Ya</option>
                                        <option value="0" {{ old('is_active') == "0"  || $shippingMethod->is_active == "0" ? 'selected' : '' }}>Tidak</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Import Ongkos Kirim</h5>
                            <a href="{{ url('dashboard/shipping-method-export') }}" class="btn btn-outline-primary mb-4 w-100">Download Data</a>
                            <form action="{{ url('dashboard/shipping-method-import') }}" method="POST" enctype="multipart/form-data">
                                <input type="file" class="form-control" name="file">
                                <span id="message_file" class="invalid-feedback" style="display:none">
                                </span>
                                <a href="#" id="saveImport" class="btn btn-outline-danger mt-4 w-100">Submit</a>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Data Ongkos Kirim</h5>
                            <div class="table-responsive">
                                <table style="width:100%" class="w-100 table table-bordered" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Provinsi</th>
                                            <th>Kota/Kabupaten</th>
                                            <th>Ongkos Kirim</th>
                                            <th>Min. Kg</th>
                                            <th>Estimasi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($shippingMethod->shippingFees()->get() as $index => $fee)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $fee->city->province->prov_name }}</td>
                                                <td>{{ $fee->city->city_name }}</td>
                                                <td>{{ "Rp. ". number_format($fee->fee,0, '.', '.') }}</td>
                                                <td>{{ $fee->minimum_kg }}</td>
                                                <td>{{ $fee->shipping_estimation }}</td>
                                                <td>
                                                    <a href="#" onclick="updateOngkir({{ $fee->id }}); return false;"><i class="bi bi-pencil-square"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body> p-3">
                            <div class="row">
                                <div class="col-8">
                                    <h5 class="mt-2">Simpan?</h5>
                                </div>
                                <div class="col-sm-12 col-lg-2">
                                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-md w-100"><i class="bi bi-x-circle"></i> Batal</a>
                                </div>
                                <div class="col-sm-12 col-lg-2">
                                    <button class="btn btn-outline-danger btn-md w-100"><i class="bi bi-save"></i> Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @csrf
            @method('PUT')
        </form>
    </section>
    <div class="modal fade" id="updateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Ongkir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Informasi Ongkir</h5>
                                    <form id="formOngkir">
                                        <div class="row">
                                            <input type="hidden" id="ongkirId" value="">
                                            <div class="col-lg-6">
                                                <label class="text-bold"><b>Provinsi</b></label>
                                                <p id="province"></p>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="text-bold"><b>Kota</b></label>
                                                <p id="city"></p>
                                            </div>
                                            <div class="col-lg-12">
                                                <label class="text-bold">Ongkos Kirim</label>
                                                <input type="text" class="form-control" id="rupiah" name="fee">
                                            </div>
                                            <div class="col-lg-12 mt-4">
                                                <label class="text-bold">Min. Kg</label>
                                                <input type="number" class="form-control" name="minimum_kg">
                                            </div>
                                            <div class="col-lg-12 mt-4">
                                                <label class="text-bold">Estimasi</label>
                                                <input type="text" class="form-control" name="shipping_estimation">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a id="submitOngkir" type="button" class="btn btn-outline-danger w-100"><i class="bi bi-save"></i> Simpan</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $("#dataTable").DataTable();

    $("#saveImport").click(function(e) {
            
        $("#saveImport").attr('disabled', true).html("<img src='{{ url('images/loading2.gif') }}' style='height:20px'> Loading...");
        
        var formData = new FormData();
        formData.append('file', $("input[name=file]")[0].files[0]);
        formData.append('shipping_method_id', "{{ $shippingMethod->id }}");

        $.ajax({
            url : "{{ url('dashboard/shipping-method-import') }}",
            type : 'POST',
            dataType : 'json',
            data : formData,
            contentType: false,
            processData: false,
            headers : {
                'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
            },
            success:function(res){
                $("#saveImport").attr('disabled', false).html('Submit');
                if(res.status == "success") {
                    window.location.reload();
                }
            },
            error:function(jqXHR) {
                $("#saveImport").attr('disabled', false).html('Submit');
                var json = jqXHR.responseJSON;
                if(typeof json.status != "undefined") {
                    swal(jqXHR.responseText, {
                        icon : "error",
                        title : "Error!"
                    });
                }
                printError(json.errors);
            }
        });

        e.preventDefault();

    });

    function updateOngkir(feeId){
        $.ajax({
            url : "{{ url('dashboard/shipping-fee') }}/" + feeId,
            type : 'GET',
            dataType : 'json',
            headers : {
                'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
            },
            success:function(res){
                var data = res.data;
                $("#province").html(data.city.province.prov_name);
                $("#city").html(data.city.city_name);
                $("[name=fee]").val(formatRupiah(data.fee.toString()));
                $("[name=minimum_kg]").val(data.minimum_kg);
                $("[name=shipping_estimation]").val(data.shipping_estimation);
                $("#ongkirId").val(feeId);
            },
            error:function(jqXHR) {
                if(typeof json.status != "undefined") {
                    swal(jqXHR.responseText, {
                        icon : "error",
                        title : "Error!"
                    });
                }
            }
        });
        $("#updateModal").modal('show');
    }

    $("#submitOngkir").click(function() {

        var formData = new FormData($("#formOngkir")[0]);
        formData.append('fee', document.querySelector("[name=fee]").value.replace(/\./g,''));

        $.ajax({
            url : "{{ url('dashboard/shipping-fee') }}/" + $("#ongkirId").val(),
            type : 'POST',
            dataType : 'json',
            data : formData,
            contentType: false,
            processData: false,
            headers : {
                'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
            },
            beforeSend:function() {
                $("#submitOngkir").attr('disabled', true).html("<img src='{{ url('images/loading2.gif') }}' style='height:20px'> Loading...");
            },
            success:function(res){
                $("#submitOngkir").attr('disabled', false).html('<i class="bi bi-save"></i> Simpan');
                if(res.status == "success") {
                    window.location.reload();
                }
            },
            error:function(jqXHR) {
                $("#submitOngkir").attr('disabled', false).html('<i class="bi bi-save"></i> Simpan');
                var json = jqXHR.responseJSON;
                if(typeof json.status != "undefined") {
                    swal(jqXHR.responseText, {
                        icon : "error",
                        title : "Error!"
                    });
                }
                printError(json.errors);
            }
        });
    });

</script>
@endpush