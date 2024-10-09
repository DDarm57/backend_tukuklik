@extends('layouts.dashboard.app', ['title' => 'Flash Deal'])

@section('content')
    <div class="pagetitle">
        <h1>Flash Deal</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Marketing</li>
                <li class="breadcrumb-item active">Flash Deal</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        @can('marketing.flash-deals_create')
            <a href="{{ url('dashboard/flash-deals/create') }}" class="btn btn-outline-danger mt-2 mb-4"><i class="bi bi-plus-circle"></i> Tambah Flash Deal</a>
        @endcan
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">List Flash Deal</h5>
                        <div class="table-responsive">
                            <table style="width:100%" class="w-100 table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Banner</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Berakhir</th>
                                        <th>Total Produk</th>
                                        <th>Diubah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($flashDeal as $rows)
                                        <tr>
                                            <td>{{ $rows->id }}</td>
                                            <td><img style="width:50px;" src="{{ Storage::url($rows->banner) }}"></td>
                                            <td>{{ date('d F Y', strtotime($rows->start_date)) }}</td>
                                            <td>{{ date('d F Y', strtotime($rows->end_date)) }}</td>
                                            <td>{{ $rows->flash_deal_products_count }}</td>
                                            <td>{{ date('d F Y H:i:s', strtotime($rows->updated_at)) }}</td>
                                            <td>
                                                <a class="icon text-danger text-bold" href="#" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots text-center"></i> Opsi</a>
                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="z-index:999 !important">
                                                    @can("marketing.flash-deals_update")<li><a class="dropdown-item" href="{{ url("dashboard/flash-deals/".$rows->id."/edit") }}">Update</a></li>@endcan
                                                    @can("marketing.flash-deals_delete")<li><a class="dropdown-item" onclick="deleted({{ $rows->id }})" href="#">Delete</a></li>@endcan
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
                        url : `{{ url('dashboard/flash-deals/${id}') }}`,
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
