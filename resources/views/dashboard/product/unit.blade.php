@extends('layouts.dashboard.app', ['title' => 'Unit'])

@section('content')
    <div class="pagetitle">
        <h1>Unit</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Produk</li>
                <li class="breadcrumb-item active">Unit</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            @can('product.unit_create')
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('unit.store') }}">
                            <h5 class="card-title">Tambah Atau Ubah Unit</h5>
                            <label for="inputText" class="col-form-label">Tipe Unit</label>
                            <input type="text" value="{{ old('name') }}" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Unit Type...">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <label for="inputText" class="col-form-label">Aktif?</label>
                            <select class="form-control selects @error('status') is-invalid @enderror" name="status">
                                <option></option>
                                <option value="1" {{ old('status') == "1" ? 'selected' : '' }}>Ya</option>
                                <option value="0" {{ old('status') == "0" ? 'selected' : '' }}>Tidak</option>
                            </select>
                            @error('status')
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
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
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
            ajax: "{{ url('dashboard/unit') }}",
            language: {
                'loadingRecords': '&nbsp;',
                'processing': '<img style="width:50px;" src="https://media.tenor.com/wpSo-8CrXqUAAAAi/loading-loading-forever.gif">'
            },
            columns: [
                {  data: 'DT_RowIndex' },
                {  data: 'name' },
                {  
                    data: null,
                    mRender : function(data) {
                        return data.status == "Actived" 
                        ? 
                        `<div style="border-radius:15px;" class="badge bg-success">${data.status}</div>` 
                        : 
                        `<div style="border-radius:15px;" class="badge bg-danger">${data.status}</div>`
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
                        $opsiHtml += '@can("product.unit_update")<li><a class="dropdown-item" onclick="unitUpdate('+data.id+')">Update</a></li>@endcan';
                        $opsiHtml += '@can("product.unit_delete")<li><a class="dropdown-item" onclick="deleted('+data.id+')" href="#">Delete</a></li>@endcan';
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
                        url : `{{ url('dashboard/unit/${id}') }}`,
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

        unitUpdate = (id) => {
            $.ajax({
                url : `{{ url('dashboard/unit') }}/${id}`,
                type : 'GET',
                dataType : 'json',
                data : { 
                    _token : $("meta[name='csrf_token']").attr('content')
                },
                success:function(res) {
                    $("input[name='name']").val(res.name);
                    $(".selects").val(res.status).trigger("change")
                    $("form")
                    .attr("action", "{{ route('unit.index') }}/"+res.id+"")
                    .append('<input type="hidden" name="_method" value="PUT">')
                }
            });
        }
    </script>
@endpush
