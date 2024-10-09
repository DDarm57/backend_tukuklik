@extends('layouts.dashboard.app', ['title' => 'Metode Pengiriman'])

@section('content')
    <div class="pagetitle">
        <h1>Metode Pengiriman</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Pengiriman</li>
                <li class="breadcrumb-item active">Metode Pengiriman</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        @can('shipping.shipping-method_create')
            <a href="{{ url('dashboard/shipping-method/create') }}" class="btn btn-outline-danger mt-2 mb-4"><i class="bi bi-plus-circle"></i> Tambah Metode Pengiriman</a>
        @endcan
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">List Metode Pengiriman</h5>
                        <div class="table-responsive">
                            <table style="width:100%" class="w-100 table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Metode</th>
                                        <th>Biaya</th>
                                        <th>Aktif?</th>
                                        <th>Diubah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($shippingMethod as $rows)
                                        <tr>
                                            <td>{{ $rows->id }}</td>
                                            <td>{{ $rows->method_name }}</td>
                                            <td>{{ $rows->cost }} / kg</td>
                                            <td>{{ $rows->is_active == 1 ? 'Ya' : 'Tidak' }}</td>
                                            <td>{{ date('d F Y H:i:s', strtotime($rows->updated_at)) }}</td>
                                            <td>
                                                <a class="icon text-danger text-bold" href="#" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots text-center"></i> Opsi</a>
                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="z-index:999 !important">
                                                    @can("shipping.shipping-method_update")<li><a class="dropdown-item" href="{{ url("dashboard/shipping-method/".$rows->id."/edit") }}">Update</a></li>@endcan
                                                    @can("shipping.shipping-method_delete")<li><a class="dropdown-item" onclick="deleted({{ $rows->id }})" href="#">Delete</a></li>@endcan
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                        url : `{{ url('dashboard/shipping-method/${id}') }}`,
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
