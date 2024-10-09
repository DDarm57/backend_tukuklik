<html>
    
    <head>
        <title>Pengiriman {{ Helpers::generalSetting()->system_name. " - ". $deliveryOrder->do_number }}</title>
        <link rel="global.css" type="text/css">
        <style type="text/css">
            @-ms-viewport {
                width: device-width
            }

            html {
                box-sizing: border-box;
                -ms-overflow-style: scrollbar;
                font-size: 10px;
                font-family: Arial, sans-serif;
            }

            h2 {
                font-size: 1.4em;
            }

            h4 {
                margin: 5px 0px;
            }

            h4:first-child {
                margin: 0px;
            }

            a {
                text-decoration: none;
                color: #000;
                font-style: normal;
            }

            p {
                margin: 3px 0px;
                line-height: 1.4em;
            }

            table th,
            table td {
                font-size: 11px;
            }

            *,
            ::after,
            ::before {
                box-sizing: inherit
            }

            .container {
                width: 100%;
                padding-right: 15px;
                padding-left: 15px;
                margin-right: auto;
                margin-left: auto
            }

            .container:first-of-type {
                margin-top: 15px;
            }

            thead {
                display: table-header-group
            }

            tfoot {
                display: table-row-group
            }

            tr {
                page-break-inside: avoid
            }

            .header td img {
                margin-top: -15px !important;
            }

            .detail-content {
                border: 1px solid #ccc;
            }

            .detail-content td {
                text-align: left;
                vertical-align: top;
                width: 30%;
            }

            .detail-content th {
                text-align: left;
                vertical-align: top;
                width: 20%;
            }

            .table-item {
                /* border: 1px solid #ccc; */
            }

            .table-item tr:not(.last) td,
            .table-item tr:not(.last) th {
                border-bottom: 1px solid #ccc !important;
            }

            .table-item tr:not(.last) th {
                border-bottom-width: 2px !important;
            }

            .row {
                width: 100%;
            }

            @page {
                margin: 15px 0px;
            }

            body {
                margin: 0px;
            }

            .clearfix &::after {
                display: block;
                content: "";
                clear: both;
                float: left;
            }

            .valign-top {
                vertical-align: top;
            }

            .positionModul {
                text-align: center;
            }

            .positionModul div {
                width: 200px;
                text-align: center;
                float: right;
            }

            .positionModul p.description {
                width: 100%;
                font-size: 10px;
                color: #0745a3;
                text-transform: uppercase;
                text-align: center;
            }

            .positionModul p.modulName {
                width: 100%;
                padding: 30px 0px;
                margin: 0px auto;
                display: block;
                background-color: #0745a3;
                color: #fff;
                font-size: 16px;
            }

            .table-detail-dashboard {
                float: left;
                width: 100%;
                margin-bottom: 15px;
            }

            .table-detail-dashboard th,
            .table-detail-dashboard td {
                text-align: left;
            }

            .table-detail-dashboard tr:Last-child td {
                border-bottom: none !important;
            }

            .text-right {
                text-align: right !important;
            }

            .text-left {
                text-align: left !important;
            }

            .small {
                font-size: 0.8em;
            }

            div.bordered,
            .table-detail-dashboard {
                border: 1px solid #ccc;
            }

            tr.bordered th,
            tr.bordered td {
                border: 1px solid #ccc;
                border-right: none;
            }

            tr.bordered th:not(:first-child),
            tr.bordered td:not(:first-child) {
                border-left: none;
            }

            tr.bordered th:last-child,
            tr.bordered td:last-child {
                border-right: 1px solid #ccc;
            }

            tr.transaction_total th {
                border-top: 1px solid #ccc !important;
            }

            .all-total table {
                margin: 15px 0px;
            }

            .small-font {
                font-size: 0.8em;
                vertical-align: top;
            }

            .margin-box {
                margin: 10px 0px;
            }

            .margin-box:first-child {
                margin-top: 0px;
            }

            .margin-box:last-child {
                margin-bottom: 0px;
            }

            .group-approval {
                background-color: #e8e8e8;
                border: 1px solid #ccc;
            }

            .td-top {
                vertical-align: top !important;
            }

            .text-blue {
                color: blue;
            }

            .text-bold {
                font-weight: bold;
            }

            @media print {
                .page-break {
                    display: block;
                    page-break-inside: avoid;
                    -webkit-region-break-inside: avoid;
                }

                .positionModul .modulName {
                    background-color: #0745a3;
                    -webkit-print-color-adjust: exact;
                }

                .group-approval {
                    background-color: #e8e8e8 !important;
                    -webkit-print-color-adjust: exact;
                    border: 1px solid #ccc;
                }
            }
        </style>
    </head>

    <body>

        <div class="container clearfix header">

            <table width="100%">
                <tr>
                    <td class="valign-top">
                        <div>
                            <img src="{{ Storage::url(Helpers::generalSetting()->logo) }}">
                        </div>
                    </td>
                    <td class="valign-top">
                        <div>
                            <h2 style="font-size:20px;text-align:right">Delivery Order</h2>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="container" style="margin-top:20px;">

            <table class="detail-content margin-box" width="100%" cellpadding="0">
                <tbody>
                    <tr>
                        <td>
                            <table width="100%" cellpadding="3">
                                <tbody>
                                    <tr>
                                        <th>Pengirim</th>
                                        <td>{{ $merchant->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Penerima</th>
                                        <td>{{ $deliveryOrder->delivery_recipient_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>PIC Penjual</th>
                                        <td>{{ $merchant->pic->name }} ({{ $merchant->phone }})</td>
                                    </tr>
                                    <tr>
                                        <th>No. PO</th>
                                        <td>{{ $deliveryOrder->order_number ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>No. Resi</th>
                                        <td>{{ $deliveryOrder->delivery_number ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>{{ $deliveryOrder->deliveryStatus->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Pengiriman</th>
                                        <td>{{ $date }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table width="100%" cellpadding="5">
                                <tbody>
                                    <tr>
                                        <th>Biaya Pengiriman</th>
                                        <td>{{ number_format($deliveryOrder->shippingRequest->shipping_fee,2,'.',',') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Estimasi Sampai</th>
                                        <td>{{ $date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Dikirim Dari</th>
                                        <td>{{ $merchantAddress }}</td>
                                    </tr>
                                    <tr>
                                        <th>Dikirim Ke</th>
                                        <td>{{ $shippingAddress }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Terima</th>
                                        <td>{{ $deliveredDate }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="box-dashboard margin-box">
                <div class="table-detail-dashboard">
                    <div class="table-responsive">
                        <table class="table-item" width="100%" cellpadding="5" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="2%">No.</th>
                                    <th width="10%">SKU</th>
                                    <th width="25%">Nama Produk</th>
                                    <th width="7%">Kuantitas</th>
                                    <th width="12%">Harga Satuan</th>
                                    <th width="10%">Total Berat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($produk as $index => $prod)
                                <tr class="td-top">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $prod->productSku->sku }}</td>
                                    <td>{{ $prod->productSku->product->product_name }}</td>
                                    <td>{{ $prod->quantity }}</td>
                                    <td class="price">{{ number_format($prod->base_price,2,'.',',') }}</td>
                                    <td>{{ $prod->quantity * ($prod->productSku->weight / 1000). " Kg" }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> <!-- .table-responsive -->
                    </div>
                </div><!-- .table-detail-dashboard -->
            </div><!-- .box-dashboard -->
            <style>
                .price:before {
                    content: "Rp.";
                    float: left;
                    padding-right: 5px;
                }

                .total-width {
                    width: 50%;
                }

                .td-top {
                    vertical-align: top !important;
                }

                .tax-total {
                    color: #707070;
                }

                .tax-total>th {
                    font-weight: normal;
                }
            </style>
        </div>

        <div class="container" style="line-height: 1.8em; clear: left;">

        </div>

        <script>
            window.print();
        </script>

    </body>

</html>