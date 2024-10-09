<html>
    
    <head>
        <title>Penawaran {{ Helpers::generalSetting()->system_name. " - ". $quote->number }}</title>
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
                    <td class="valign-top" width="50%">
                        <div>
                            <img src="{{ Storage::url(Helpers::generalSetting()->logo) }}">
                        </div>
                    </td>
                    <td class="valign-top" width="50%">
                        <div>
                            <h2 style="font-size:20px;text-align:right">{{ $transType == "Penawaran" ? "Quotation" : "Request For Quotation" }}</h2>
                        </div>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td class="valign-top" width="30%">
                        <p>Dari :<br /></p>
                        <table style="margin-left: -3px">
                            <tr>
                                <td><b>{{ $transType == "Penawaran" ? $merchant->name : $quote->user_pic }}</b></td>
                                <td>
                                </td>
                            </tr>
                        </table>
                        <p>{{ strtoupper($transType == "Penawaran"  ? $merchantAddress : $shippingAddress) }}</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p>Kepada :<br /></p>
                        <table style="margin-left: -3px">
                            <tr>
                                <td><b>{{ $transType == "Penawaran"  ? $quote->user_pic : $merchant->name }}</b></td>
                                <td>
                                </td>
                            </tr>
                        </table>
                        <p>{{ strtoupper($transType == "Penawaran" ? $shippingAddress : $merchantAddress) }}</p>
                    </td>
                </tr>
            </table>

        </div>

        <div class="container">

            <table class="detail-content margin-box" width="100%" cellpadding="0">
                <tbody>
                    <tr>
                        <td style="width:50%">
                            <table width="100%" cellpadding="3">
                                <tbody>
                                    <tr>
                                        <th>Penjual Kena Pajak?</th>
                                        <td>{{ $merchant->is_pkp == 1 ? "Ya" : "Tidak" }}</td>
                                    </tr>
                                    @if(substr($quote->number,0,2) == "QN")
                                        <tr>
                                            <th>No. Quotation</th>
                                            <td>{{ $quote->channel->quotation_number ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>No. RFQ</th>
                                            <td>{{ $quote->channel->rfq_number }}</td>
                                        </tr>
                                    @else 
                                        <tr>
                                            <th>No. RFQ</th>
                                            <td>{{ $quote->channel->rfq_number }}</td>
                                        </tr>
                                        <tr>
                                            <th>No. Quotation</th>
                                            <td>{{ $quote->channel->quotation_number ?? '-' }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>Status</th>
                                        <td>{{ $quote->quoteStatus->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Transaksi</th>
                                        <td>{{ $date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tipe Transaksi</th>
                                        <td>{{ substr($quote->number,0,2) == "QN" ? "Penawaran" : "Permintaan Penawaran" }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="width:50%">
                            <table width="100%" cellpadding="5">
                                <tbody>
                                    <tr>
                                        <th>Untuk Keperluan</th>
                                        <td>{{ $quote->purpose_of }}</td>
                                    </tr>
                                    <tr>
                                        <th>Alamat Pengiriman</th>
                                        <td>{{ $shippingAddress }}</td>
                                    </tr>
                                    <tr>
                                        <th>Alamat Tagihan</th>
                                        <td>{{ $billingAddress }}</td>
                                    </tr>
                                    <tr>
                                        <th>Catatan untuk {{ $transType == "Penawaran" ? "Pembeli" : "Penjual" }}</th>
                                        <td>
                                            @php 
                                                $notes =  $transType == "Penawaran" ? $quote->notes_for_buyer : $quote->notes_for_merchant;
                                                $notes = wordwrap(strip_tags($notes), 50, "\n");
                                            @endphp
                                            {!! $notes !!}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>

            @if($quote->quoteStatus->name == "Kadaluwarsa")
                <div class="bordered" style="padding: 5px;">
                    <strong> Alasan Kedaluwarsa :</strong> {{ $quote->optional_status }}
                </div>
            @endif

            @if($quote->quoteStatus->name == "Ditolak Penjual")
                <div class="bordered" style="padding: 5px;">
                    <strong> Alasan Ditolak Penjual :</strong> {{ $quote->optional_status }}
                </div>
            @endif

            @if($quote->quoteStatus->name == "Ditolak Pembeli")
                <div class="bordered" style="padding: 5px;">
                    <strong> Alasan Ditolak Pembeli :</strong> {{ $quote->optional_status }}
                </div>
            @endif

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
                                    <th width="10%">Pajak</th>
                                    <th width="25%">Total Harga</th>
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
                                    <td>
                                        <div class="row">&nbsp;</div>
                                        <div class="row">
                                            <div>
                                                PPN - {{ $prod->tax_percentage * 100 }} %
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div>
                                                PPH - {{ $prod->income_tax_percentage * 100 }} %
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="text-right price total-width">
                                                {{ number_format($prod->subtotal,2,'.',',') }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="text-right price total-width">
                                                {{ number_format($prod->tax_amount,2,'.',',') }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="text-right price total-width">
                                                ({{ number_format($prod->income_tax_amount,2,'.',',') }})
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4" style="text-align: left;">
                                        Jasa Pengiriman : 
                                        {{ $quote->shippingRequest->shippingMethod->method_name ?? 'Menunggu Konfirmasi Penjual' }}
                                    </td>
                                    <td colspan="3">
                                        Pengiriman dari lokasi penjual : {{ $merchant->address->city->city_name }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div> <!-- .table-responsive -->
                    <div class="bordered">
                        <div class="col-5">
                            <div class="all-total">
                                <table class="page-break" width="30%" cellpadding="5" cellspacing="0" align="right"
                                    style="float: right;">
                                    <tbody>
                                        <tr>
                                            <th>Sub Total</th>
                                            <td>Rp.</td>
                                            <td class="text-right">{{ number_format($quote->subtotal,2,'.',',') }}</td>
                                        </tr>
                                        <tr class="tax-total">
                                            <th>
                                                PPN - {{ ($quote->tax_amount / $quote->subtotal * 100) ?? 0 }}%
                                            </th>
                                            <td>Rp.</td>
                                            <td class="text-right">
                                                {{ number_format($quote->tax_amount,2,'.',',') }}
                                            </td>
                                        </tr>
                                        <tr class="tax-total">
                                            <th>
                                                PPH - {{ ($quote->income_tax / $quote->subtotal * 100) ?? 0 }}%
                                            </th>
                                            <td>Rp.</td>
                                            <td class="text-right">
                                                ({{ number_format($quote->income_tax,2,'.',',') }})
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Ongkos Kirim</th>
                                            <td>Rp.</td>
                                            <td class="text-right">{{ number_format($quote->shipping_amount,2,'.',',') }}</td>
                                        </tr>
                                        <tr class="transaction_total bordered">
                                            <th>Total Transaksi</th>
                                            <th>Rp.</th>
                                            <th class="text-right"> {{ number_format($grandTotal,2,'.',',') }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div style="width: 100%;
        float: left;
        padding: 7px;
        border-top: 1px solid #ccc;
        margin: 2px; font-style: italic;">
                        Terbilang : {{ Helpers::countedAmount($grandTotal) }} </div>
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