@extends('layouts.dashboard.app', ['title' => 'Banner'])

@section('content')
    <div class="pagetitle">
        <h1>Banner</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Marketing</li>
                <li class="breadcrumb-item active">Banner</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">List Banner</h5>
                        @can('marketing.banner_create')
                            <a href="{{ url('dashboard/banner/create') }}" class="btn btn-outline-danger mt-2 mb-4"><i class="bi bi-plus-circle"></i> Tambah Banner</a>
                        @endcan
                        <div class="table-responsive">
                            <table style="width:100%" class="w-100 table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Banner</th>
                                        <th>Gambar</th>
                                        <th>Aktif?</th>
                                        <th>Dibuat</th>
                                        <th>Diubah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($banner as $rows)
                                        <tr>
                                            <td>{{ $rows->id }}</td>
                                            <td>{{ $rows->name }}</td>
                                            <td><img style="width:50px;" src="{{ Storage::url($rows->slider_image) }}"></td>
                                            <td>{{ $rows->status == 1 ? 'Ya' : 'Tidak' }}</td>
                                            <td>{{ date('d F Y H:i:s', strtotime($rows->created_at)) }}</td>
                                            <td>{{ date('d F Y H:i:s', strtotime($rows->updated_at)) }}</td>
                                            <td>
                                                <a class="icon text-danger text-bold" href="#" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots text-center"></i> Opsi</a>
                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="z-index:999 !important">
                                                    @can("marketing.banner_update")<li><a class="dropdown-item" href="{{ url("dashboard/banner/".$rows->id."/edit") }}">Update</a></li>@endcan
                                                    @can("marketing.banner_delete")<li><a class="dropdown-item" onclick="deleted({{ $rows->id }})" href="#">Delete</a></li>@endcan
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
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

        $("#dataTable").DataTable();

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
                        url : `{{ url('dashboard/banner/${id}') }}`,
                        type : 'DELETE',
                        dataType: 'JSON',
                        data : { 
                            _token : $("meta[name='csrf_token']").attr('content')
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
