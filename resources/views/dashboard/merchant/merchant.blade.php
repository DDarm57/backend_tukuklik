@extends('layouts.dashboard.app', ['title' => 'Penjual'])

@section('content')
    <div class="pagetitle">
        <h1>Penjual</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Penjual</li>
                <li class="breadcrumb-item active">List Penjual</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        @can('merchant.merchant_create')
            <a href="{{ url('dashboard/merchant/create') }}" class="btn btn-outline-danger mt-2 mb-4"><i class="bi bi-plus-circle"></i> Tambah Penjual</a>
        @endcan
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">List Penjual</h5>
                        <div class="table-responsive">
                            <table style="width:100%" class="w-100 table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Penjual</th>
                                        <th>Photo</th>
                                        <th>No. Telp</th>
                                        <th>NPWP</th>
                                        <th>PIC</th>
                                        <th>PKP?</th>
                                        <th>Alamat</th>
                                        <th>Aktif?</th>
                                        <th>Diubah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($merchant as $rows)
                                        <tr>
                                            <td>{{ $rows->id }}</td>
                                            <td>{{ $rows->name }}</td>
                                            <td><img class="rounded-circle" style="height:55px;width:55px;" src="{{ Helpers::image('/storage/'.$rows->photo) }}"></td>
                                            <td>{{ $rows->phone }}</td>
                                            <td>{{ $rows->npwp }}</td>
                                            <td>{{ $rows->pic->name }}</td>
                                            <td>{{ $rows->is_pkp == "Y" ? "Ya" : "Tidak" }}</td>
                                            <td>{{ Helpers::getMerchantAddress($rows->id) }}</td>
                                            <td>{{ $rows->status == 1 ? 'Ya' : 'Tidak' }}</td>
                                            <td>{{ date('d F Y H:i:s', strtotime($rows->updated_at)) }}</td>
                                            <td>
                                                <a class="icon text-danger text-bold" href="#" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots text-center"></i> Opsi</a>
                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="z-index:999 !important">
                                                    @can("merchant.merchant_update")<li><a class="dropdown-item" href="{{ url("dashboard/merchant/".$rows->id."/edit") }}">Update</a></li>@endcan
                                                    @can("merchant.merchant_delete")<li><a class="dropdown-item" onclick="deleted({{ $rows->id }})" href="#">Delete</a></li>@endcan
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
                        url : `{{ url('dashboard/merchant/${id}') }}`,
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
