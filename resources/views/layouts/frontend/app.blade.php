<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    @stack('meta')
    <link rel="shortcut icon" type="image/x-icon" src="{{ Storage::url(Helpers::generalSetting()->favicon) }}">
    <link href="{{ url('frontend/assets/css/style.css?v=3.0.0') }}" rel="stylesheet">
    <link href="{{ url('frontend/assets/css/custom.css') }}" rel="stylesheet">
    <link rel="icon" sizes="512x512" href="{{ Storage::url(Helpers::generalSetting()->favicon) }}">
    <link href="{{ url('admin/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    @stack('styles')
    <title>{{ Helpers::generalSetting()->system_name. " - ". $title }}</title>
</head>
<body>
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="text-center">
                    <img class="mb-10" src="{{ Storage::url(Helpers::generalSetting()->favicon) }}" alt="Ecom">
                    <div class="preloader-dots"></div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="topbar">
        <div class="container-topbar">
            <div class="menu-topbar-left d-none d-xl-block">
                <ul class="nav-small">
                    <li><a class="font-xs" href="{{ url('contact') }}">Kontak Kami</a></li>
                    @foreach(Helpers::pages() as $page)
                    <li><a class="font-xs" href="{{ url($page->slug) }}">{{ $page->title }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="info-topbar text-center d-none d-xl-block">
            </div>
            @if(Helpers::generalSetting()->company_phone != "")
            <div class="menu-topbar-right">
                <span class="font-xs color-brand-3">Kontak Kami Di Nomor :</span>
                <span class="font-sm-bold color-success"> {{ Helpers::generalSetting()->company_phone }}</span>
            </div>
            @endif
        </div>
    </div> --}}

    @include('layouts.frontend.navbar')

    @yield('content')

    @include('layouts.frontend.footer')

    <script src="{{ url('frontend/assets/js/vendors/modernizr-3.6.0.min.js') }}"></script>
    <script src="{{ url('frontend/assets/js/vendors/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ url('frontend/assets/js/vendors/jquery-migrate-3.3.0.min.js') }}"></script>
    <script src="{{ url('frontend/assets/js/vendors/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('frontend/assets/js/vendors/waypoints.js') }}"></script>
    <script src="{{ url('frontend/assets/js/vendors/wow.js') }}"></script>
    <script src="{{ url('frontend/assets/js/vendors/magnific-popup.js') }}"></script>
    <script src="{{ url('frontend/assets/js/vendors/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ url('frontend/assets/js/vendors/select2.min.js') }}"></script>
    <script src="{{ url('frontend/assets/js/vendors/isotope.js') }}"></script>
    <script src="{{ url('frontend/assets/js/vendors/scrollup.js') }}"></script>
    <script src="{{ url('frontend/assets/js/vendors/swiper-bundle.min.js') }}"></script>
    <script src="{{ url('frontend/assets/js/vendors/noUISlider.js') }}"></script>
    <script src="{{ url('frontend/assets/js/vendors/slider.js') }}"></script>
    <!-- Count down-->
    <script src="{{ url('frontend/assets/js/vendors/counterup.js') }}"></script>
    <script src="{{ url('frontend/assets/js/vendors/jquery.countdown.min.js') }}"></script>
    <!-- Count down-->
    <script src="{{ url('frontend/assets/js/vendors/jquery.elevatezoom.js') }}"></script>
    <script src="{{ url('frontend/assets/js/vendors/slick.js') }}"></script>
    <script src="{{ url('frontend/assets/js/main.js') }}?v=3.0.0"></script>
    <script src="{{ url('frontend/assets/js/shop.js') }}?v=1.2.1"></script>
    <script src="https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>

    @stack('scripts')
    @include('firebase')
    @include('javascript.js-navbar')
    @include('javascript.js-advanced-search')

    @if(session()->has('success'))
        <script>
            swal("{{ session()->get('success') }}", {
                icon: "success",
                title: "Sukses",
            });
        </script>
    @endif

    <script>
        function formatRupiah(angka, prefix){
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split   		= number_string.split(','),
            sisa     		= split[0].length % 3,
            rupiah     		= split[0].substr(0, sisa),
            ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if(ribuan){
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }
        function printError(errors) {
            var input = document.querySelectorAll('.form-control');
            for(var i = 0; i < input.length; i++){
                input[i].classList.remove('is-invalid');
                var message = document.getElementById('message_'+input[i].name);
                if(message != null ){
                    message.innerHTML = '';
                    message.style.display = 'none';
                }
            }
            for (const input in errors) {
                if (errors.hasOwnProperty(input)) {
                    const messages = errors[input];
                    $(`[name='${input}']`).addClass('is-invalid');
                    $("#message_"+input).css('display', '').html(messages[0]);
                }
            }
        }

        function message() {
            $.ajax({
                url : "{{ url('count_message') }}",
                type : "GET",
                headers : {
                    'X-CSRF-TOKEN' : $("meta[name=csrf_token]").attr('content'),
                },
                beforeSend:function() {
          
                },
                success:function(res){
                    $("#countMessage").html(res.data);
                    var notif = $("#countNotif").html();
                    var message = $("#countMessage").html();
                    $("#notifTotal").html(parseFloat(notif) + parseFloat(message));
                }
            });
        }

        function notification(opsi='', page=1){
            $.ajax({
                url : "{{ url('dashboard/notifications') }}" + "?opsi="+opsi+"&page="+page,
                type : "GET",
                headers : {
                    'X-CSRF-TOKEN' : $("meta[name=csrf_token]").attr('content'),
                },
                beforeSend:function() {
                    if(page == 1) {
                        $(".notifications").html("");
                    }
                },
                success:function(res){
                    var data = res.data.data;
                    $("#countNotif").html(res.notif);
                }
            });
        }

    </script>

</body>

</html>