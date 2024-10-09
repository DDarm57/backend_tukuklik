@extends('layouts.dashboard.app', ['title' => 'Tax'])

@section('content')
    <div class="pagetitle">
        <h1>VAT / TAX</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Pengaturan</li>
                <li class="breadcrumb-item active">VAT / TAX</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            @can('setting.vat_create')
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('vat.store') }}">
                            <h5 class="card-title">Tambah Atau Ubah Tax</h5>
                            <label for="inputText" class="col-form-label">Nama / Tipe Tax</label>
                            <input type="text" value="{{ old('name') }}" name="name" class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <label for="inputText" class="col-form-label">Persentase</label>
                            <input type="text" value="{{ old('tax_percentage') }}" name="tax_percentage" class="form-control @error('tax_percentage') is-invalid @enderror">
                            @error('tax_percentage')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <label for="inputText" class="col-form-label">Aktif?</label>
                            <select class="form-control selects @error('is_active') is-invalid @enderror" name="is_active">
                                <option></option>
                                <option value="1" {{ old('is_active') == "1" ? 'selected' : '' }}>Ya</option>
                                <option value="0" {{ old('is_active') == "0" ? 'selected' : '' }}>Tidak</option>
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button class="btn btn-outline-danger mt-2"><i class="bi bi-save"></i> Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
            @endcan
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Unit Lists</h5>
                        <div class="table-responsive">
                            <table style="width:100%" class="w-100 table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tipe</th>
                                        <th>Persentase</th>
                                        <th>Status</th>
                                        <th>Ditambahkan</th>
                                        <th>Diubah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                            </table>
                        </div>
                        <div class="dataTables_wrapper">
                            <div class="row">
                                <div class="col-sm-12 col-md-5" id="dataTables_info">
                                </div>
                                <div class="col-sm-12 col-md-7" id="dataTables_pagination">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        var table = $("#dataTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('dashboard/vat') }}",
            language: {
                'loadingRecords': '&nbsp;',
                'processing': '<img style="width:50px;" src="https://media.tenor.com/wpSo-8CrXqUAAAAi/loading-loading-forever.gif">'
            },
            columns: [
                {  data: 'DT_RowIndex' },
                {  data: 'name' },
                {  data: 'percentage' },
                {  
                    data: null,
                    mRender : function(data) {
                        return data.is_active == 1
                        ? 
                        `<div style="border-radius:15px;" class="bg-success p-1 text-white text-center">Aktif</div>` 
                        : 
                        `<div style="border-radius:15px;" class="bg-danger p-1 text-white text-center">Non Aktif</div>`
                    }
                },
                { 
                    data : null,
                    mRender : function(data) {
                        return moment(data.created_at).format('D MMMM Y LT')
                    }
                },
                { 
                    data : null,
                    mRender : function(data) {
                        return moment(data.updated_at).format('D MMMM Y LT')
                    }
                },
                {
                    data: null,
                    mRender: function(data) {
                        $opsiHtml =  '<a class="icon text-danger text-bold" href="#" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots text-center"></i> Opsi</a>';
                        $opsiHtml += '<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="z-index:999 !important">';
                        $opsiHtml += '@can("setting.vat_update")<li><a class="dropdown-item" onclick="vatUpdate('+data.id+')">Update</a></li>@endcan';
                        $opsiHtml += '@can("setting.vat_delete")<li><a class="dropdown-item" onclick="deleted('+data.id+')" href="#">Delete</a></li>@endcan';
                        $opsiHtml += '</ul>';
                        return $opsiHtml;
                    }
                },
            ],
            initComplete: (settings, json) => {
                $('.dataTables_info').appendTo('#dataTables_info');
                $('.dataTables_paginate').appendTo('#dataTables_pagination');
            },
        });

        deleted = (id) => {
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
                        url : `{{ url('dashboard/vat/${id}') }}`,
                        type : 'DELETE',
                        dataType: 'JSON',
                        data : { 
                            _token : $("meta[name='csrf_token']").attr('content')
                        },
                        success:function(res) {
                            swal(res.message, {
                                icon: "success",
                            });
                            table.ajax.reload();
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

        vatUpdate = (id) => {
            $.ajax({
                url : `{{ url('dashboard/vat') }}/${id}`,
                type : 'GET',
                dataType : 'json',
                data : { 
                    _token : $("meta[name='csrf_token']").attr('content')
                },
                success:function(res) {
                    $("input[name='name']").val(res.name);
                    $("input[name='tax_percentage']").val(res.tax_percentage);
                    $(".selects").val(res.is_active).trigger("change")
                    $("form")
                    .attr("action", "{{ route('vat.index') }}/"+res.id+"")
                    .append('<input type="hidden" name="_method" value="PUT">')
                }
            });
        }
    </script>
@endpush
