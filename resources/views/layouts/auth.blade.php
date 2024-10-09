<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>{{ $title }}</title>
  <meta content="Masuk Sebelum Mulai Transaksi Bersama Tukuklik" name="description">
  <meta content="Login" name="keywords">

  <!-- Favicons -->
  <link href="https://tukuklik.com/public/uploads/settings/639888c978794.png" rel="icon">
  <link href="{{ url('admin/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ url('admin/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ url('admin/assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ url('admin/assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
  <link href="{{ url('admin/assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
  <link href="{{ url('admin/assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ url('admin/assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

  <style>

    body {
        color: #000;
        overflow-x: hidden;
        height: 100%;
        background-color: #B0BEC5;
        background-repeat: no-repeat;
    }

    .swal-modal .swal-text {
        text-align: center;
    }

    .card0 {
        box-shadow: 0px 4px 8px 0px #757575;
        border-radius: 0px;
    }

    .card2 {
        margin: 0px 40px;
    }

    .logo {
        width: 200px;
        height: 100px;
        margin-top: 20px;
        margin-left: 35px;
    }

    .image {
        width: 500px;
        height: 400px;
    }

    .border-line {
        border-right: 1px solid #EEEEEE;
    }

    .facebook {
        background-color: #ffffff;
        border: 2px solid #3d71e0;
        color: #3d71e0;
        width: 100%;
        padding: 5px 5px 5px;
        cursor: pointer;
    }

    .google {
        background-color: #ffffff;
        border : 2px solid rgb(158, 157, 157);
        color: #000000;
        padding: 5px 5px 5px;
        width: 100%;
        cursor: pointer;
    }

    .linkedin {
        background-color: #2867B2;
        color: #fff;
        font-size: 18px;
        padding-top: 5px;
        width: 35px;
        height: 35px;
        cursor: pointer;
    }

    .line {
        height: 1px;
        width: 35%;
        background-color: #E0E0E0;
        margin-top: 10px;
    }

    .or {
        width: 30%;
        font-weight: bold;
    }

    .text-sm {
        font-size: 14px !important;
    }

    ::placeholder {
        color: #BDBDBD !important;
        opacity: 1 !important;
        font-weight: 300 !important
    }

    :-ms-input-placeholder {
        color: #BDBDBD;
        font-weight: 300
    }

    ::-ms-input-placeholder {
        color: #BDBDBD;
        font-weight: 300
    }

    input.form-control, textarea {
        padding: 10px 12px 10px 12px;
        border: 1px solid lightgrey;
        border-radius: 2px;
        margin-bottom: 5px;
        margin-top: 2px;
        width: 100%;
        box-sizing: border-box;
        color: #2C3E50;
        font-size: 14px;
        letter-spacing: 1px;
        height:40px;
    }

    input:focus, textarea:focus {
        -moz-box-shadow: none !important;
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
        border: 1px solid #304FFE;
        outline-width: 0;
    }

    button:focus {
        -moz-box-shadow: none !important;
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
        outline-width: 0;
    }

    a {
        color: inherit;
        cursor: pointer;
    }

    .btn-blue {
        background-color: #ff0027;
        width: 150px;
        color: #fff;
        border-radius: 2px;
    }

    .btn-blue:hover {
        background-color: #000;
        cursor: pointer;
    }

    .bg-blue {
        color: #fff;
        background-color: #ff0027;
    }

    @media screen and (max-width: 991px) {
        .logo {
            margin-left: 0px;
        }

        .image {
            width: 350px;
            height: 300px;
        }

        .border-line {
            border-right: none;
        }

        .card2 {
            border-top: 1px solid #EEEEEE !important;
            margin: 0px 15px;
        }
    }
  </style>
  <!-- Template Main CSS File -->
  {{-- <link href="{{ url('admin/assets/css/style.css') }}" rel="stylesheet"> --}}

</head>

<body>

  @yield('content')

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ url('admin/assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ url('admin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ url('admin/assets/vendor/chart.js/chart.umd.js') }}"></script>
  <script src="{{ url('admin/assets/vendor/echarts/echarts.min.js') }}"></script>
  <script src="{{ url('admin/assets/vendor/quill/quill.min.js') }}"></script>
  <script src="{{ url('admin/assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ url('admin/assets/vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ url('admin/assets/vendor/php-email-form/validate.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ url('admin/assets/js/main.js') }}"></script>
  <script src="https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
  <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>

  @if(session()->has('success'))
        <script>
            swal("{{ session()->get('success') }}", {
                icon: "success",
                title: "Sukses",
            });
        </script>
  @endif
  
  @include('firebase')
  
</body>

</html>