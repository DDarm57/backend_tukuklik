@extends('layouts.dashboard.app', ['title' => 'Pesanan'])

@section('content')
    <div class="pagetitle">
        <h1>Pesanan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Transaksi</li>
                <li class="breadcrumb-item active">Pesanan</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
          <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Filter Data</h5>
                        <form method="POST">
                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="inputText" class="col-form-label">Dari Tanggal</label>
                                    <input type="date" class="form-control" id="fromDate">
                                </div>
                                <div class="col-lg-4">
                                    <label for="inputText" class="col-form-label">Sampai Tanggal</label>
                                    <input type="date" class="form-control" id="toDate">
                                </div>
                                <div class="col-lg-4">
                                    <label for="inputText" class="col-form-label">Status</label>
                                    <select class="form-control selects" id="status">
                                        <option></option>
                                        @foreach(Helpers::transactionStatus('purchase_order') as $s)
                                            <option>{{ $s }}</option>
                                        @endforeach
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
                        <h5 class="card-title">Data Pesanan</h5>
                        <div class="table-responsive">
                            <table style="width:100%" class="w-100 table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>No. </th>
                                        <th>No Transaksi</th>
                                        <th>Pembeli</th>
                                        <th>Penjual</th>
                                        <th>Grand Total</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
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
        
        var search = document.querySelectorAll(".form-control");
        search.forEach((item, index) => {
            var param = search[index].getAttribute('id');
            filtering[param] = '';
        });

        $(".form-control").on('change', function() {
            filtering[$(this).attr('id')] = $(this).val();
            table.draw();
        });

        var table = $("#dataTable").DataTable({
            processing: true,
            serverSide: true,
            ajax : {
                "url"   : "{{ url('dashboard/purchase-order') }}",
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
                {  data: 'order_number' },
                {  data: 'quotation.user.name' },
                {  data: 'quotation.merchant.name' },
                {  data: 'grand_total' },
                { 
                    data : null,
                    mRender : function(data) {
                        return moment(data.purchase_date).format('D MMMM Y LT')
                    }
                },
                {  
                    data: null,
                    mRender : function(data) {
                        var deadlineDate = "";
                        if(data.status.name == "Menunggu Konfirmasi Penjual"){
                            deadlineDate = "<br><small class='text-muted'>Batas Waktu Konfirmasi Penjual "+moment(data.purchase_deadline_date).format('D MMMM Y LT')+"</small>"
                        }
                        return "<span class='"+colorStatus(data.status.name)+"'>"+data.status.name+"</span>" + deadlineDate;
                    }
                },
                {
                    data: null,
                    mRender: function(data) {
                        var btnUpdate = "Update";
                        if(data.status.name == "Menunggu Konfirmasi Penjual") {
                            btnUpdate = "Konfirmasi";
                        }
                        var update =  '@can("transaction.purchase-order_update")<li><a class="dropdown-item" href="{{ url("dashboard/purchase-order") }}/'+data.order_number+'">'+btnUpdate+'</a></li>@endcan';
                        if(data.status.name != "Terkirim" && "{{ Auth()->user()->hasRole('Customer') }}" == true){
                            update = '';
                        }
                        $opsiHtml =  '<a class="icon text-danger text-bold" href="#" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots text-center"></i> Opsi</a>';
                        $opsiHtml += '<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="z-index:999 !important">';
                        $opsiHtml += '@can("transaction.purchase-order_view")<li><a class="dropdown-item" href="{{ url("dashboard/purchase-order") }}/'+data.order_number+'">Lihat</a></li>@endcan';
                        $opsiHtml += update;
                        $opsiHtml += '@can("transaction.purchase-order_delete")<li><a class="dropdown-item" onclick="deleted('+data.id+')" href="#">Delete</a></li>@endcan';
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
                        url : `{{ url('dashboard/purchase-order/${id}') }}`,
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