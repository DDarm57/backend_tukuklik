<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>404 Not Found</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <!-- Stylesheet -->
    <link href="https://tukuklik.com/public/uploads/settings/639888c978794.png" rel="icon">
    <link href="{{ url('admin/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link href="{{ url('admin/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ url('admin/assets/css/style.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
<div class="container">
    <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
    <h1 class="text-danger">404</h1>
    <h2 class="text-danger">The page you are looking for doesn't exist.</h2>
    <a class="btn btn-danger bg-danger" href="{{ url()->previous() }}">Kembali Ke Halaman Sebelumnya</a>
    </section>
</div>
</body>
</html>