<!DOCTYPE html>
<html lang="en">

<head>
    <meta charSet="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="next-head-count" content="2" />
    <title>BAST {{ Helpers::generalSetting()->system_name. " - ". $deliveryOrder->do_number }}</title>
    <link rel="preload" as="font" crossorigin="true"
        data-href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&amp;display=swap" />
    <link rel="stylesheet" crossorigin="true"
        data-href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&amp;display=swap" />
    <style id="jss-server-side">
        html {
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        *,
        *::before,
        *::after {
            box-sizing: inherit;
        }

        strong,
        b {
            font-weight: 700;
        }

        th {
            text-align:left;
        }

        body {
            color: rgba(0, 0, 0, 0.87);
            margin: 0;
            font-size: 1rem;
            font-family: "Roboto", "Helvetica", "Arial", sans-serif;
            font-weight: 400;
            line-height: 1.43;
            letter-spacing: 0.01071em;
            background-color: #fafafa;
        }

        @media print {
            body {
                background-color: #fff;
            }
        }

        body::backdrop {
            background-color: #fafafa;
        }

        .MuiGrid-container {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            box-sizing: border-box;
        }

        .MuiGrid-item {
            margin: 0;
            box-sizing: border-box;
        }

        .MuiGrid-zeroMinWidth {
            min-width: 0;
        }

        .MuiGrid-direction-xs-column {
            flex-direction: column;
        }

        .MuiGrid-direction-xs-column-reverse {
            flex-direction: column-reverse;
        }

        .MuiGrid-direction-xs-row-reverse {
            flex-direction: row-reverse;
        }

        .MuiGrid-wrap-xs-nowrap {
            flex-wrap: nowrap;
        }

        .MuiGrid-wrap-xs-wrap-reverse {
            flex-wrap: wrap-reverse;
        }

        .MuiGrid-align-items-xs-center {
            align-items: center;
        }

        .MuiGrid-align-items-xs-flex-start {
            align-items: flex-start;
        }

        .MuiGrid-align-items-xs-flex-end {
            align-items: flex-end;
        }

        .MuiGrid-align-items-xs-baseline {
            align-items: baseline;
        }

        .MuiGrid-align-content-xs-center {
            align-content: center;
        }

        .MuiGrid-align-content-xs-flex-start {
            align-content: flex-start;
        }

        .MuiGrid-align-content-xs-flex-end {
            align-content: flex-end;
        }

        .MuiGrid-align-content-xs-space-between {
            align-content: space-between;
        }

        .MuiGrid-align-content-xs-space-around {
            align-content: space-around;
        }

        .MuiGrid-justify-content-xs-center {
            justify-content: center;
        }

        .MuiGrid-justify-content-xs-flex-end {
            justify-content: flex-end;
        }

        .MuiGrid-justify-content-xs-space-between {
            justify-content: space-between;
        }

        .MuiGrid-justify-content-xs-space-around {
            justify-content: space-around;
        }

        .MuiGrid-justify-content-xs-space-evenly {
            justify-content: space-evenly;
        }

        .MuiGrid-spacing-xs-1 {
            width: calc(100% + 8px);
            margin: -4px;
        }

        .MuiGrid-spacing-xs-1>.MuiGrid-item {
            padding: 4px;
        }

        .MuiGrid-spacing-xs-2 {
            width: calc(100% + 16px);
            margin: -8px;
        }

        .MuiGrid-spacing-xs-2>.MuiGrid-item {
            padding: 8px;
        }

        .MuiGrid-spacing-xs-3 {
            width: calc(100% + 24px);
            margin: -12px;
        }

        .MuiGrid-spacing-xs-3>.MuiGrid-item {
            padding: 12px;
        }

        .MuiGrid-spacing-xs-4 {
            width: calc(100% + 32px);
            margin: -16px;
        }

        .MuiGrid-spacing-xs-4>.MuiGrid-item {
            padding: 16px;
        }

        .MuiGrid-spacing-xs-5 {
            width: calc(100% + 40px);
            margin: -20px;
        }

        .MuiGrid-spacing-xs-5>.MuiGrid-item {
            padding: 20px;
        }

        .MuiGrid-spacing-xs-6 {
            width: calc(100% + 48px);
            margin: -24px;
        }

        .MuiGrid-spacing-xs-6>.MuiGrid-item {
            padding: 24px;
        }

        .MuiGrid-spacing-xs-7 {
            width: calc(100% + 56px);
            margin: -28px;
        }

        .MuiGrid-spacing-xs-7>.MuiGrid-item {
            padding: 28px;
        }

        .MuiGrid-spacing-xs-8 {
            width: calc(100% + 64px);
            margin: -32px;
        }

        .MuiGrid-spacing-xs-8>.MuiGrid-item {
            padding: 32px;
        }

        .MuiGrid-spacing-xs-9 {
            width: calc(100% + 72px);
            margin: -36px;
        }

        .MuiGrid-spacing-xs-9>.MuiGrid-item {
            padding: 36px;
        }

        .MuiGrid-spacing-xs-10 {
            width: calc(100% + 80px);
            margin: -40px;
        }

        .MuiGrid-spacing-xs-10>.MuiGrid-item {
            padding: 40px;
        }

        .MuiGrid-grid-xs-auto {
            flex-grow: 0;
            max-width: none;
            flex-basis: auto;
        }

        .MuiGrid-grid-xs-true {
            flex-grow: 1;
            max-width: 100%;
            flex-basis: 0;
        }

        .MuiGrid-grid-xs-1 {
            flex-grow: 0;
            max-width: 8.333333%;
            flex-basis: 8.333333%;
        }

        .MuiGrid-grid-xs-2 {
            flex-grow: 0;
            max-width: 16.666667%;
            flex-basis: 16.666667%;
        }

        .MuiGrid-grid-xs-3 {
            flex-grow: 0;
            max-width: 25%;
            flex-basis: 25%;
        }

        .MuiGrid-grid-xs-4 {
            flex-grow: 0;
            max-width: 33.333333%;
            flex-basis: 33.333333%;
        }

        .MuiGrid-grid-xs-5 {
            flex-grow: 0;
            max-width: 41.666667%;
            flex-basis: 41.666667%;
        }

        .MuiGrid-grid-xs-6 {
            flex-grow: 0;
            max-width: 50%;
            flex-basis: 50%;
        }

        .MuiGrid-grid-xs-7 {
            flex-grow: 0;
            max-width: 58.333333%;
            flex-basis: 58.333333%;
        }

        .MuiGrid-grid-xs-8 {
            flex-grow: 0;
            max-width: 66.666667%;
            flex-basis: 66.666667%;
        }

        .MuiGrid-grid-xs-9 {
            flex-grow: 0;
            max-width: 75%;
            flex-basis: 75%;
        }

        .MuiGrid-grid-xs-10 {
            flex-grow: 0;
            max-width: 83.333333%;
            flex-basis: 83.333333%;
        }

        .MuiGrid-grid-xs-11 {
            flex-grow: 0;
            max-width: 91.666667%;
            flex-basis: 91.666667%;
        }

        .MuiGrid-grid-xs-12 {
            flex-grow: 0;
            max-width: 100%;
            flex-basis: 100%;
        }

        @media (min-width:600px) {
            .MuiGrid-grid-sm-auto {
                flex-grow: 0;
                max-width: none;
                flex-basis: auto;
            }

            .MuiGrid-grid-sm-true {
                flex-grow: 1;
                max-width: 100%;
                flex-basis: 0;
            }

            .MuiGrid-grid-sm-1 {
                flex-grow: 0;
                max-width: 8.333333%;
                flex-basis: 8.333333%;
            }

            .MuiGrid-grid-sm-2 {
                flex-grow: 0;
                max-width: 16.666667%;
                flex-basis: 16.666667%;
            }

            .MuiGrid-grid-sm-3 {
                flex-grow: 0;
                max-width: 25%;
                flex-basis: 25%;
            }

            .MuiGrid-grid-sm-4 {
                flex-grow: 0;
                max-width: 33.333333%;
                flex-basis: 33.333333%;
            }

            .MuiGrid-grid-sm-5 {
                flex-grow: 0;
                max-width: 41.666667%;
                flex-basis: 41.666667%;
            }

            .MuiGrid-grid-sm-6 {
                flex-grow: 0;
                max-width: 50%;
                flex-basis: 50%;
            }

            .MuiGrid-grid-sm-7 {
                flex-grow: 0;
                max-width: 58.333333%;
                flex-basis: 58.333333%;
            }

            .MuiGrid-grid-sm-8 {
                flex-grow: 0;
                max-width: 66.666667%;
                flex-basis: 66.666667%;
            }

            .MuiGrid-grid-sm-9 {
                flex-grow: 0;
                max-width: 75%;
                flex-basis: 75%;
            }

            .MuiGrid-grid-sm-10 {
                flex-grow: 0;
                max-width: 83.333333%;
                flex-basis: 83.333333%;
            }

            .MuiGrid-grid-sm-11 {
                flex-grow: 0;
                max-width: 91.666667%;
                flex-basis: 91.666667%;
            }

            .MuiGrid-grid-sm-12 {
                flex-grow: 0;
                max-width: 100%;
                flex-basis: 100%;
            }
        }

        @media (min-width:960px) {
            .MuiGrid-grid-md-auto {
                flex-grow: 0;
                max-width: none;
                flex-basis: auto;
            }

            .MuiGrid-grid-md-true {
                flex-grow: 1;
                max-width: 100%;
                flex-basis: 0;
            }

            .MuiGrid-grid-md-1 {
                flex-grow: 0;
                max-width: 8.333333%;
                flex-basis: 8.333333%;
            }

            .MuiGrid-grid-md-2 {
                flex-grow: 0;
                max-width: 16.666667%;
                flex-basis: 16.666667%;
            }

            .MuiGrid-grid-md-3 {
                flex-grow: 0;
                max-width: 25%;
                flex-basis: 25%;
            }

            .MuiGrid-grid-md-4 {
                flex-grow: 0;
                max-width: 33.333333%;
                flex-basis: 33.333333%;
            }

            .MuiGrid-grid-md-5 {
                flex-grow: 0;
                max-width: 41.666667%;
                flex-basis: 41.666667%;
            }

            .MuiGrid-grid-md-6 {
                flex-grow: 0;
                max-width: 50%;
                flex-basis: 50%;
            }

            .MuiGrid-grid-md-7 {
                flex-grow: 0;
                max-width: 58.333333%;
                flex-basis: 58.333333%;
            }

            .MuiGrid-grid-md-8 {
                flex-grow: 0;
                max-width: 66.666667%;
                flex-basis: 66.666667%;
            }

            .MuiGrid-grid-md-9 {
                flex-grow: 0;
                max-width: 75%;
                flex-basis: 75%;
            }

            .MuiGrid-grid-md-10 {
                flex-grow: 0;
                max-width: 83.333333%;
                flex-basis: 83.333333%;
            }

            .MuiGrid-grid-md-11 {
                flex-grow: 0;
                max-width: 91.666667%;
                flex-basis: 91.666667%;
            }

            .MuiGrid-grid-md-12 {
                flex-grow: 0;
                max-width: 100%;
                flex-basis: 100%;
            }
        }

        @media (min-width:1280px) {
            .MuiGrid-grid-lg-auto {
                flex-grow: 0;
                max-width: none;
                flex-basis: auto;
            }

            .MuiGrid-grid-lg-true {
                flex-grow: 1;
                max-width: 100%;
                flex-basis: 0;
            }

            .MuiGrid-grid-lg-1 {
                flex-grow: 0;
                max-width: 8.333333%;
                flex-basis: 8.333333%;
            }

            .MuiGrid-grid-lg-2 {
                flex-grow: 0;
                max-width: 16.666667%;
                flex-basis: 16.666667%;
            }

            .MuiGrid-grid-lg-3 {
                flex-grow: 0;
                max-width: 25%;
                flex-basis: 25%;
            }

            .MuiGrid-grid-lg-4 {
                flex-grow: 0;
                max-width: 33.333333%;
                flex-basis: 33.333333%;
            }

            .MuiGrid-grid-lg-5 {
                flex-grow: 0;
                max-width: 41.666667%;
                flex-basis: 41.666667%;
            }

            .MuiGrid-grid-lg-6 {
                flex-grow: 0;
                max-width: 50%;
                flex-basis: 50%;
            }

            .MuiGrid-grid-lg-7 {
                flex-grow: 0;
                max-width: 58.333333%;
                flex-basis: 58.333333%;
            }

            .MuiGrid-grid-lg-8 {
                flex-grow: 0;
                max-width: 66.666667%;
                flex-basis: 66.666667%;
            }

            .MuiGrid-grid-lg-9 {
                flex-grow: 0;
                max-width: 75%;
                flex-basis: 75%;
            }

            .MuiGrid-grid-lg-10 {
                flex-grow: 0;
                max-width: 83.333333%;
                flex-basis: 83.333333%;
            }

            .MuiGrid-grid-lg-11 {
                flex-grow: 0;
                max-width: 91.666667%;
                flex-basis: 91.666667%;
            }

            .MuiGrid-grid-lg-12 {
                flex-grow: 0;
                max-width: 100%;
                flex-basis: 100%;
            }
        }

        @media (min-width:1920px) {
            .MuiGrid-grid-xl-auto {
                flex-grow: 0;
                max-width: none;
                flex-basis: auto;
            }

            .MuiGrid-grid-xl-true {
                flex-grow: 1;
                max-width: 100%;
                flex-basis: 0;
            }

            .MuiGrid-grid-xl-1 {
                flex-grow: 0;
                max-width: 8.333333%;
                flex-basis: 8.333333%;
            }

            .MuiGrid-grid-xl-2 {
                flex-grow: 0;
                max-width: 16.666667%;
                flex-basis: 16.666667%;
            }

            .MuiGrid-grid-xl-3 {
                flex-grow: 0;
                max-width: 25%;
                flex-basis: 25%;
            }

            .MuiGrid-grid-xl-4 {
                flex-grow: 0;
                max-width: 33.333333%;
                flex-basis: 33.333333%;
            }

            .MuiGrid-grid-xl-5 {
                flex-grow: 0;
                max-width: 41.666667%;
                flex-basis: 41.666667%;
            }

            .MuiGrid-grid-xl-6 {
                flex-grow: 0;
                max-width: 50%;
                flex-basis: 50%;
            }

            .MuiGrid-grid-xl-7 {
                flex-grow: 0;
                max-width: 58.333333%;
                flex-basis: 58.333333%;
            }

            .MuiGrid-grid-xl-8 {
                flex-grow: 0;
                max-width: 66.666667%;
                flex-basis: 66.666667%;
            }

            .MuiGrid-grid-xl-9 {
                flex-grow: 0;
                max-width: 75%;
                flex-basis: 75%;
            }

            .MuiGrid-grid-xl-10 {
                flex-grow: 0;
                max-width: 83.333333%;
                flex-basis: 83.333333%;
            }

            .MuiGrid-grid-xl-11 {
                flex-grow: 0;
                max-width: 91.666667%;
                flex-basis: 91.666667%;
            }

            .MuiGrid-grid-xl-12 {
                flex-grow: 0;
                max-width: 100%;
                flex-basis: 100%;
            }
        }
    </style>
    <style data-styled="" data-styled-version="5.3.3">
        .grmJJH {
            padding-left: 0px !important;
            padding-right: 0px !important;
            text-align: center;
        }

        /*!sc*/
        data-styled.g163[id="sc-77d9d13c-0"] {
            content: "grmJJH,"
        }

        /*!sc*/
        .ekA-dwS {
            margin-bottom: 10px;
        }

        /*!sc*/
        data-styled.g164[id="sc-77d9d13c-1"] {
            content: "ekA-dwS,"
        }

        /*!sc*/
        .GHZxH {
            font-weight: bold;
            margin-top: 0;
            margin-bottom: 0;
        }

        /*!sc*/
        data-styled.g165[id="sc-77d9d13c-2"] {
            content: "GHZxH,"
        }

        /*!sc*/
        .dVqztg {
            margin-top: 10px;
        }

        /*!sc*/
        data-styled.g166[id="sc-77d9d13c-3"] {
            content: "dVqztg,"
        }

        /*!sc*/
        .dluuCK {
            margin-top: 0;
            margin-bottom: 0;
            -webkit-text-decoration: underline;
            text-decoration: underline;
        }

        /*!sc*/
        data-styled.g167[id="sc-77d9d13c-4"] {
            content: "dluuCK,"
        }

        /*!sc*/
        .iuFDwI {
            margin-top: 0;
            margin-bottom: 0;
        }

        /*!sc*/
        data-styled.g168[id="sc-77d9d13c-5"] {
            content: "iuFDwI,"
        }

        /*!sc*/
        .ksviLe {
            font-weight: bold;
        }

        /*!sc*/
        data-styled.g169[id="sc-b86f0aa1-0"] {
            content: "ksviLe,"
        }

        /*!sc*/
        .bACQQm {
            margin-top: 0;
            margin-bottom: 0;
        }

        /*!sc*/
        data-styled.g170[id="sc-b86f0aa1-1"] {
            content: "bACQQm,"
        }

        /*!sc*/
        @media (min-width:809px),
        print {
            .ZscHj {
                padding-right: 180px;
            }
        }

        /*!sc*/
        data-styled.g171[id="sc-e7ca5239-0"] {
            content: "ZscHj,"
        }

        /*!sc*/
        .gxsNpE {
            border-collapse: collapse;
            width: 100%;
        }

        /*!sc*/
        .gxsNpE thead {
            margin-bottom: 10px;
        }

        /*!sc*/
        .gxsNpE tr {
            border-bottom: 1px solid #D3D3D3;
            line-height: 30px;
        }

        /*!sc*/
        .gxsNpE tr.tfoot>td:first-child {
            text-align: right;
            padding-right: 30px;
            text-transform: uppercase;
            font-weight: bold;
        }

        /*!sc*/
        .gxsNpE tr.tfoot {
            border-bottom: none !important;
        }

        /*!sc*/
        data-styled.g172[id="sc-7d7ea2c5-0"] {
            content: "gxsNpE,"
        }

        /*!sc*/
        .duxefO {
            margin-top: 20px;
        }

        /*!sc*/
        data-styled.g173[id="sc-67766d7c-0"] {
            content: "duxefO,"
        }

        /*!sc*/
        .ipRQin {
            font-size: 11px;
            margin-bottom: 1px;
            text-align: right;
        }

        /*!sc*/
        @media (min-width:809px) {
            .ipRQin {
                font-size: 14px;
            }
        }

        /*!sc*/
        @media print {
            .ipRQin {
                font-size: 14px;
                padding-right: 150px;
            }
        }

        /*!sc*/
        data-styled.g174[id="sc-67766d7c-1"] {
            content: "ipRQin,"
        }

        /*!sc*/
        .emvuMl {
            text-align: right;
            width: 100%;
            margin-top: 0;
        }

        /*!sc*/
        @media print {
            .emvuMl {
                padding-right: 150px;
            }
        }

        /*!sc*/
        data-styled.g175[id="sc-67766d7c-2"] {
            content: "emvuMl,"
        }

        /*!sc*/
        .iIhzAL {
            width: 100px;
            height: 25px;
            object-fit: contain;
        }

        /*!sc*/
        @media (min-width:809px),
        print {
            .iIhzAL {
                width: 226px;
                height: 50px;
            }
        }

        /*!sc*/
        data-styled.g176[id="sc-67766d7c-3"] {
            content: "iIhzAL,"
        }

        /*!sc*/
        .edlpdI {
            color: #d14747;
        }

        /*!sc*/
        data-styled.g177[id="sc-67766d7c-4"] {
            content: "edlpdI,"
        }

        /*!sc*/
        @page {
            size: A4;
            margin: 0mm;
        }

        /*!sc*/
        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        /*!sc*/
        @-ms-viewport {
                {
                width: device-width;
            }
        }

        /*!sc*/
        html {
            box-sizing: border-box;
            -ms-overflow-style: scrollbar;
            font-size: 12px;
            font-family: Arial, sans-serif;
        }

        /*!sc*/
        .page-header {
            height: 100px;
        }

        /*!sc*/
        .paper {
            padding: 10mm;
        }

        /*!sc*/
        @media (min-width:809px) {
            .paper {
                width: 210mm;
                min-height: 297mm;
                padding: 20mm;
                margin: 10mm auto;
                border: 1px #D3D3D3 solid;
                border-radius: 5px;
                background: white;
                box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            }
        }

        /*!sc*/
        @media print {
            .page-header-space {
                height: 100px;
            }

            .page-header {
                height: 0 !important;
                position: fixed;
                top: 0mm;
                width: 100%;
                left: 20mm;
            }

            .paper {
                margin: 0;
                border: initial;
                border-radius: initial;
                padding: 0mm 20mm 20mm 20mm;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            button {
                display: none;
            }

            body {
                margin: 0;
            }

            .content-last {
                display: inline-block;
            }
        }

        /*!sc*/
        .last-content {
            page-break-before: always;
        }

        /*!sc*/
        data-styled.g178[id="sc-global-PhNou1"] {
            content: "sc-global-PhNou1,"
        }

        /*!sc*/
    </style>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" />
</head>

<body id="body">
    <div class="paper">
        <div class="page-header">
            <div
                class="MuiGrid-root pdf-header MuiGrid-container MuiGrid-spacing-xs-3 MuiGrid-justify-content-xs-center">
                <div class="MuiGrid-root sc-67766d7c-0 duxefO MuiGrid-item MuiGrid-grid-xs-4"><img
                        src="{{ Storage::url(Helpers::generalSetting()->logo) }}" class="sc-67766d7c-3 iIhzAL" /></div>
                <div class="MuiGrid-root sc-67766d7c-0 duxefO MuiGrid-item MuiGrid-grid-xs-8">
                    <div class="sc-67766d7c-4 edlpdI">
                        <h2 class="sc-67766d7c-1 ipRQin">BERITA ACARA SERAH TERIMA</h2>
                        <p class="sc-67766d7c-2 emvuMl">Pengiriman {{ $deliveryOrder->do_number }}</p>
                    </div>
                </div>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <td>
                        <div class="page-header-space"></div>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="page">
                            <div class="content-block">
                                <p>Melalui dokumen Berita Acara Serah Terima Transaksi Tukuklik, kami yang
                                    bertanda tangan dibawah ini:</p>
                                <div>
                                    <p class="sc-b86f0aa1-1 bACQQm">{{ $deliveryOrder->purchaseOrder->user->name }}</p>
                                    @if(isset($deliveryOrder->purchaseOrder->quotation->user->lpse))
                                        <p class="sc-b86f0aa1-1 bACQQm">
                                            Pejabat Pengadaan {{ ucwords($deliveryOrder->purchaseOrder->quotation->user->lpse->nama_instansi) }}
                                            (Bidang {{ ucwords(strtolower($deliveryOrder->purchaseOrder->quotation->user->lpse->nama_satker)) }})
                                        </p>
                                    @endif
                                    <p class="sc-b86f0aa1-1 bACQQm">{{ $shippingAddress }}</p>
                                    <p class="sc-b86f0aa1-0 ksviLe">Disebut PIHAK PERTAMA ( Penjual )</p>
                                </div>
                                <div>
                                    <p class="sc-b86f0aa1-1 bACQQm">{{ Helpers::generalSetting()->director }}</p>
                                    <p class="sc-b86f0aa1-1 bACQQm">Direktur {{ $deliveryOrder->purchaseOrder->quotation->merchant->name }}</p>
                                    <p class="sc-b86f0aa1-1 bACQQm">{{ $merchantAddress }}</p>
                                    <p class="sc-b86f0aa1-0 ksviLe">Disebut PIHAK KEDUA ( Penjual )</p>
                                </div>
                                <p>Dengan ini menyatakan bahwa:<br /><b>PIHAK KEDUA</b> telah menyerahkan barang
                                    sesuai yang dijelaskan dibawah pada alamat tujuan sesuai dengan:</p>
                                <div
                                    class="MuiGrid-root sc-e7ca5239-0 ZscHj MuiGrid-container MuiGrid-spacing-xs-1">
                                    <div class="MuiGrid-root MuiGrid-item MuiGrid-grid-xs-6">Nomor Purchase Order
                                    </div>
                                    <div class="MuiGrid-root MuiGrid-item MuiGrid-grid-xs-6"> : {{ $deliveryOrder->purchaseOrder->order_number }}</div>
                                    <div class="MuiGrid-root MuiGrid-item MuiGrid-grid-xs-6">Tanggal Dikirim: </div>
                                    <div class="MuiGrid-root MuiGrid-item MuiGrid-grid-xs-6"> : {{ $date }}</div>
                                    <div class="MuiGrid-root MuiGrid-item MuiGrid-grid-xs-6">Jasa Pengiriman: </div>
                                    <div class="MuiGrid-root MuiGrid-item MuiGrid-grid-xs-6"> : {{ $deliveryOrder->purchaseOrder->quotation->shippingRequest->shippingMethod->method_name }}</div>
                                    <div class="MuiGrid-root MuiGrid-item MuiGrid-grid-xs-6">Nomor Resi: </div>
                                    <div class="MuiGrid-root MuiGrid-item MuiGrid-grid-xs-6"> : {{ $deliveryOrder->delivery_number ?? '-' }}</div>
                                    <div class="MuiGrid-root MuiGrid-item MuiGrid-grid-xs-6">Tanggal Diterima:
                                    </div>
                                    <div class="MuiGrid-root MuiGrid-item MuiGrid-grid-xs-6"> : {{ $deliveredDate }}</div>
                                    <div class="MuiGrid-root MuiGrid-item MuiGrid-grid-xs-6">Nama Penerima: </div>
                                    <div class="MuiGrid-root MuiGrid-item MuiGrid-grid-xs-6"> : {{ $deliveryOrder->delivery_recipient_name }}</div>
                                </div>
                                <p><b>PIHAK PERTAMA</b> telah memeriksa dan menerima dengan baik pesanan dengan
                                    rincian sebagai berikut:.</p>
                                <table class="sc-7d7ea2c5-0 gxsNpE">
                                    <thead>
                                        <tr>
                                            <th width="10%">No.</th>
                                            <th width="20%">SKU</th>
                                            <th witdth="30%">Nama Produk</th>
                                            <th width="10%">Kuantitas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php 
                                            $sumQty = 0;
                                        @endphp
                                        @foreach($produk as $index => $prod)
                                        @php $sumQty += $prod->quantity; @endphp
                                        <tr>
                                            <td>{{ $index+1 }}.</td>
                                            <td>{{ $prod->productSku->sku }}</td>
                                            <td>{{ $prod->productSku->product->product_name }}</td>
                                            <td>{{ $prod->quantity. " ". $prod->productSku->product->unit_type->name }}</td>
                                        </tr>
                                        @endforeach
                                        <tr class="tfoot">
                                            <td colSpan="3">Total Kuantitas</td>
                                            <td style="text-align:center"><b>{{ $sumQty }}</b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="content-last">
                            <p>Demikian Berita Acara Serah Terima ini dibuat dengan sebenarnya yang ditandatangani
                                oleh <b>PIHAK PERTAMA</b> dan <b>PIHAK KEDUA</b>.</p>
                            <div
                                class="MuiGrid-root sc-77d9d13c-0 grmJJH qr-signature MuiGrid-container MuiGrid-spacing-xs-3">
                                <div class="MuiGrid-root MuiGrid-item MuiGrid-grid-xs-6">
                                    <div class="sc-77d9d13c-1 ekA-dwS">
                                        <p class="sc-77d9d13c-2 GHZxH">PIHAK KEDUA</p><span>Penjual</span>
                                    </div><svg xmlns="http://www.w3.org/2000/svg" height="86" viewBox="0 0 33 33"
                                        width="86">
                                    </svg>
                                    <div class="sc-77d9d13c-3 dVqztg">
                                        <p class="sc-77d9d13c-4 dluuCK">{{ Helpers::generalSetting()->director }}</p><span>Direktur {{ $merchant->name }}</span>
                                    </div>
                                </div>
                                <div class="MuiGrid-root MuiGrid-item MuiGrid-grid-xs-6">
                                    <div class="sc-77d9d13c-1 ekA-dwS">
                                        <p class="sc-77d9d13c-2 GHZxH">PIHAK PERTAMA</p><span>Pembeli</span>
                                    </div><svg xmlns="http://www.w3.org/2000/svg" height="86" viewBox="0 0 33 33"
                                        width="86">
                                    </svg>
                                    <div class="sc-77d9d13c-3 dVqztg">
                                        <p class="sc-77d9d13c-4 dluuCK">{{ $deliveryOrder->purchaseOrder->user->name }}</p>
                                        @if(isset($deliveryOrder->purchaseOrder->quotation->user->lpse))
                                            <span>
                                                Pejabat Pengadaan {{ ucwords($deliveryOrder->purchaseOrder->quotation->user->lpse->nama_instansi) }}
                                                (Bidang {{ ucwords(strtolower($deliveryOrder->purchaseOrder->quotation->user->lpse->nama_satker)) }})
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
<script>
    window.print();
</script>