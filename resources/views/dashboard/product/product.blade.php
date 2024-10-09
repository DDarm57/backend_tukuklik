@extends('layouts.dashboard.app', ['title' => 'Produk'])

@section('content')
    <div class="pagetitle">
        <h1>Produk</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Produk</li>
                <li class="breadcrumb-item active">List Produk</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            @can('product.product_create')
                <div class="col-lg-4">
                    <a href="{{ url('dashboard/product/create') }}" class="btn btn-outline-danger mt-2 mb-4"><i class="bi bi-plus-circle"></i> Create Product</a>
                </div>
            @endcan
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Filter Data</h5>
                        <form method="POST" action="{{ route('unit.store') }}">
                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="inputText" class="col-form-label">Stok</label>
                                    <select class="form-control selects" id="stock">
                                        <option></option>
                                        <option value="has_stock">Stok Tersedia</option>
                                        <option value="out_of_stock">Stok Habis</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label for="inputText" class="col-form-label">Status</label>
                                    <select class="form-control selects" id="status">
                                        <option></option>
                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak Aktif</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label for="inputText" class="col-form-label">Tipe Produk</label>
                                    <select class="form-control selects" id="product_type">
                                        <option></option>
                                        <option value="product">List Produk</option>
                                        <option value="sku">Produk SKU</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">List Produk</h5>
                        <div class="table-responsive">
                            <table style="width:100%" class="w-100 table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Tipe Produk</th>
                                        <th>Gambar</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
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

        var filtering = {};
        
        var select = document.querySelectorAll('.selects');
        select.forEach((item, index) => {
            var param = select[index].getAttribute('id');
            filtering[param] = '';
        });

        $(".selects").on('change', function() {
            filtering[$(this).attr('id')] = $(this).val();
            table.draw();
        });

        var table = $("#dataTable").DataTable({
            processing: true,
            serverSide: true,
            ajax : {
                "url"   : "{{ url('dashboard/product') }}",
                "data"  : function(data) {
                    data.filter = filtering;
                }
            },
            language: {
                'loadingRecords': '&nbsp;',
                'processing': '<img style="width:50px;" src="https://media.tenor.com/wpSo-8CrXqUAAAAi/loading-loading-forever.gif">'
            },
            columns: [
                {  data: 'DT_RowIndex' },
                {  data: 'product_name' },
                {  data: 'product_type' },
                {  
                    data: null ,
                    mRender : function(data) {
                        return '<img style="height:55px;width:55px;" src="'+data.thumbnail+'">'
                    }
                },
                {
                    data : null,
                    mRender : function(data) {
                        return formatRupiah(Math.round(data.product_skus[0].selling_price).toString(), "Rp. ")
                    }
                },
                {  data: 'stock' },
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
                        $opsiHtml += '@can("product.product_view")<li><a class="dropdown-item" href="{{ url("dashboard/product") }}/'+data.id+'/edit?viewMode=true">View</a></li>@endcan';
                        $opsiHtml += '@can("product.product_update")<li><a class="dropdown-item" href="{{ url("dashboard/product") }}/'+data.id+'/edit">Ubah</a></li>@endcan';
                        $opsiHtml += '@can("product.product_delete")<li><a class="dropdown-item" onclick="deleted('+data.id+')" href="#">Hapus</a></li>@endcan';
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
                        url : `{{ url('dashboard/product/${id}') }}`,
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
    </script>
@endpush
