@extends('layouts.dashboard.app', ['title' => 'Role'])

@section('content')
    <div class="pagetitle">
        <h1>Role</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Peran</li>
                <li class="breadcrumb-item active">Role</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Role Lists</h5>
                        @can('role.role_create')
                            <a href="{{ url('dashboard/role/create') }}" class="btn btn-outline-danger mt-2 mb-4"><i class="bi bi-plus-circle"></i> Create Role</a>
                        @endcan
                        <div class="table-responsive">
                            <table style="width:100%" class="w-100 table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
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
            ajax: "{{ url('dashboard/role') }}",
            language: {
                'loadingRecords': '&nbsp;',
                'processing': '<img style="width:50px;" src="https://media.tenor.com/wpSo-8CrXqUAAAAi/loading-loading-forever.gif">'
            },
            columns: [
                {  data: 'DT_RowIndex' },
                {  data: 'name' },
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
                        $opsiHtml += '@can("role.role_update")<li><a class="dropdown-item" href="{{ url("dashboard/role") }}/'+data.id+'/edit">Update</a></li>@endcan';
                        $opsiHtml += '@can("role.role_delete")<li><a class="dropdown-item" onclick="deleted('+data.id+')" href="#">Delete</a></li>@endcan';
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
                        url : `{{ url('dashboard/role/${id}') }}`,
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
