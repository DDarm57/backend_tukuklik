@extends('layouts.dashboard.app', ['title' => 'Index'])

@section('content')
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Permintaan</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-basket3"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $rfq['count'] }}</h6>
                                        <span class="text-muted small pt-2 ps-1">Rp. {{ number_format($rfq['grandTotal'], 2, '.', ',') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Penawaran</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-cart"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $quotation['count'] }}</h6>
                                        <span class="text-muted small pt-2 ps-1">Rp. {{ number_format($quotation['grandTotal'], 2, '.', ',') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Pesanan</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-cart-check-fill"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $orders['count'] }}</h6>
                                        <span class="text-muted small pt-2 ps-1">Rp. {{ number_format($orders['grandTotal'], 2, '.', ',') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card info-card revenue-card">
                            <div class="card-body">
                                <h5 class="card-title">Tagihan</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-currency-dollar"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $invoice['count'] }}</h6>
                                        <span class="text-muted small pt-2 ps-1">Rp. {{ number_format($invoice['grandTotal'], 2, '.', ',') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(Auth::user()->hasRole('Customer') == false)
                    <div class="col-lg-4 col-md-6">
                        <div class="card info-card customers-card">
                            <div class="card-body">
                                <h5 class="card-title">Pelanggan</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $customer['count'] }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card info-card customers-card">
                            <div class="card-body">
                                <h5 class="card-title">Total Produk</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $product['count'] }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Permintaan Berdasarkan Status</h5>
                                <canvas id="rfqWidget" style="max-height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Penawaran Berdasarkan Status</h5>
                                <canvas id="quotationWidget" style="max-height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Pesanan Berdasarkan Status</h5>
                                <canvas id="orderWidget" style="max-height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Tagihan Berdasarkan Status</h5>
                                <canvas id="invoiceWidget" style="max-height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>
                    @if(Auth::user()->hasRole('Customer') == false)
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Ketersediaan Stok</h5>
                                <canvas id="productStockWidget" style="max-height: 200px;"></canvas>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

        function checkStatus(type){
            let chartStatus = Chart.getChart(type);
            if (chartStatus != undefined) {
                chartStatus.destroy();
            }
        }
        
        function makeWidget(data, period, canvasId){

            checkStatus(canvasId);

            var dataSets = [];
            for(x = 0; x < data.length; x++){
                dataSets.push({
                    label: data[x].item,
                    type: "bar",
                    stack: data[x].item,
                    backgroundColor: data[x].color,
                    data: data[x].data,
                });
            }
            var chart = new Chart(document.getElementById(canvasId), {
                type: 'bar',
                data: {
                    labels: period,
                    datasets: dataSets
                },
            });
        }

        function makeProductStock(data) {

            checkStatus("productStockWidget");

            var datas = [];
            var labels = [];

            for(x = 0; x < data.length; x++){
                datas.push(data[x].total);
                labels.push(data[x].name);
            }

            new Chart(document.querySelector('#productStockWidget'), {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Produk',
                        data: datas,
                        backgroundColor: [
                            'rgb(255, 99, 132)',
                            'rgb(54, 162, 235)',
                            'rgb(255, 205, 86)'
                        ],
                        hoverOffset: 4
                    }]
                }
            });
        }
        
        function getChart() {
            $.ajax({
            url : "{{ url('dashboard') }}",
                type : 'GET',
                dataType : 'json',
                headers : {
                    'X-CSRF-TOKEN' : $("meta[name=csrf_token]").attr('content')
                },
                success:function(res) {
                    makeWidget(res.rfq, res.period, 'rfqWidget');
                    makeWidget(res.quotation, res.period, 'quotationWidget');
                    makeWidget(res.order, res.period, 'orderWidget');
                    makeWidget(res.invoice, res.period, 'invoiceWidget');
                    makeProductStock(res.productStock);
                }
            })
        }

        getChart();

    </script>
@endpush