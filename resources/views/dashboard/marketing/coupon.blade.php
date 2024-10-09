@extends('layouts.dashboard.app', ['title' => 'Kupon Diskon'])

@section('content')
    <div class="pagetitle">
        <h1>Kupon Diskon</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Marketing</li>
                <li class="breadcrumb-item active">Kupon Diskon</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        @can('marketing.coupon_create')
            <a href="{{ url('dashboard/coupon/create') }}" class="btn btn-outline-danger mt-2 mb-4"><i class="bi bi-plus-circle"></i> Tambah Kupon Diskon</a>
        @endcan
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">List Kupon Diskon</h5>
                        <div class="table-responsive">
                            <table style="width:100%" class="w-100 table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kupon</th>
                                        <th>Tgl Mulai</th>
                                        <th>Tgl Berakhir</th>
                                        <th>Diskon</th>
                                        <th>Tipe Diskon</th>
                                        <th>Minimum Belanja</th>
                                        <th>Maks. Diskon</th>
                                        <th>Multiple?</th>
                                        <th>Diubah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($coupon as $rows)
                                        <tr>
                                            <td>{{ $rows->id }}</td>
                                            <td>{{ $rows->title }}</td>
                                            <td>{{ date('d F Y', strtotime($rows->start_date)) }}</td>
                                            <td>{{ date('d F Y', strtotime($rows->end_date)) }}</td>
                                            <td>{{ $rows->discount }}</td>
                                            <td>{{ $rows->discount_type }}</td>
                                             <td>{{ $rows->minimum_shopping }}</td>
                                            <td>{{ $rows->maximum_discount }}</td>
                                            <td>{{ $rows->is_multiple_buy == 1 ? 'Ya' : 'Tidak' }}</td>
                                             <td>{{ date('d F Y H:i:s', strtotime($rows->updated_at)) }}</td>
                                            <td>
                                                <a class="icon text-danger text-bold" href="#" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots text-center"></i> Opsi</a>
                                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="z-index:999 !important">
                                                    @can("marketing.coupon_update")<li><a class="dropdown-item" href="{{ url("dashboard/coupon/".$rows->id."/edit") }}">Update</a></li>@endcan
                                                    @can("marketing.coupon_delete")<li><a class="dropdown-item" onclick="deleted({{ $rows->id }})" href="#">Delete</a></li>@endcan
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
                        url : `{{ url('dashboard/coupon/${id}') }}`,
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
