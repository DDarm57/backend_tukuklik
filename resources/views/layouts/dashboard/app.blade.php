<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ Helpers::generalSetting()->system_name. ' - ' . $title }}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta name="csrf_token" content = "{{ csrf_token() }}">
    <!-- Stylesheet -->
    <link href="{{ Storage::url(Helpers::generalSetting()->favicon) }}" rel="icon">
    <link href="{{ url('admin/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link href="{{ url('admin/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ url('admin/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ url('admin/assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ url('admin/assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ url('admin/assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ url('admin/assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('admin/assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ url('admin/assets/css/custom.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @stack('styles')
</head>
<body style="overflow-x:hidden">

    <div id="page-container">
        @include('layouts.dashboard.header')

        @include('layouts.dashboard.sidebar')

        <!-- Main Content -->
        <main id="main" class="main">
            @yield('content')
        </main>

        @include('layouts.dashboard.footer')
    </div>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ url('admin/assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ url('admin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('admin/assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ url('admin/assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ url('admin/assets/vendor/quill/quill.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.2/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ url('admin/assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ url('admin/assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ url('admin/assets/js/main.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://momentjs.com/downloads/moment-with-locales.min.js"></script>
    <script src="https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
    <script src="{{ url('admin/assets/js/backend.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
    @if(session()->has('success'))
        <script>
            swal("{{ session()->get('success') }}", {
                icon: "success",
                title: "Sukses",
            });
        </script>
    @endif
    <script>
    
    moment.locale('id');

    $('.selects').select2({
        placeholder: 'Select an option',
        theme: "bootstrap",
        width: "100%"
    });

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

    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });

    function setRupiah() {
        var rupiah = document.querySelectorAll("#rupiah");
        for(i = 0; i < rupiah.length; i++){
            var index = rupiah[i].value;
            rupiah[i].addEventListener('keyup', function() {
                this.value = formatRupiah(this.value);
            });
        }
    }

    setRupiah();

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

    function colorStatus(status) {
        switch(status) {
            case "Menunggu Konfirmasi Penjual" : 
                return "badge bg-warning";
            break;
            case "Menunggu Konfirmasi Pembeli" : 
                return "badge bg-warning";
            break;
            case "Negosiasi" : 
                return "badge bg-info";
            break;
            case "Quotation Dibuat" : 
                return "badge bg-primary";
            break;
            case "PO Dibuat" : 
                return "badge bg-primary";
            break;
            case "Pesanan Diproses" : 
                return "badge bg-info";
            break;
            case "Dalam Pengiriman" : 
                return "badge bg-info";
            break;
            case "Terkirim" : 
                return "badge bg-success";
            break;
            case "Selesai" : 
                return "badge bg-secondary";
            break;
            case "Kadaluwarsa" : 
                return "badge bg-danger";
            break;
            case "Ditolak Pembeli" : 
                return "badge bg-danger";
            break;
            case "Ditolak Penjual" : 
                return "badge bg-danger";
            break;
            case "Belum Dibayar" : 
                return "badge bg-info";
            break;
            case "Sudah Dibayar" : 
                return "badge bg-success";
            break;
            case "Jatuh Tempo" : 
                return "badge bg-danger";
            break;
            case "Kadaluwarsa" : 
                return "badge bg-danger";
            break;
            case "Menunggu Pembayaran" : 
                return "badge bg-warning";
            break;
        }
    }
    

    function message(opsi='', page=1){
        $.ajax({
            url : "{{ url('dashboard/messages') }}" + "?opsi="+opsi+"&page="+page,
            type : "GET",
            headers : {
                'X-CSRF-TOKEN' : $("meta[name=csrf_token]").attr('content'),
            },
            beforeSend:function() {
                if(page == 1) {
                    $(".messages").html("");
                }
            },
            success:function(res){
                var data = res.data.data;
                var html = "";
                if(page==1){
                    $(".messages").html('<li class="dropdown-header">You have '+res.message+' new messages</li>')
                }
                data.forEach((item, index) => {
                    var spanNew = "";
                    if(item.seen == 0) {
                        spanNew = '<span class="badge bg-danger">Baru</span>';
                    }
                    html += '<li>'+
                                '<hr class="dropdown-divider">'+
                            '</li>'+
                            '<li class="message-item">'+
                                '<a href="{{ url("chat") }}/'+item.from_id+'">'+
                                    '<div>'+
                                        '<h4>'+item.name+'&nbsp;'+spanNew+'</h4>'+
                                        '<p>'+item.body+'</p>'+
                                        '<p>'+moment(item.created_at).format("D MMMM Y hh:mm:ss")+'</p>'+
                                    '</div>'+
                                '</a>'+
                            '</li>';
                });

                $("#countMessage").html(res.message);
                if(opsi == "open") {
                    $(".messages").append(html);
                }
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
                var html = "";
                if(page==1){
                    $(".notifications").html('<li class="dropdown-header">You have '+res.notif+' new notifications</li>')
                }
                data.forEach((item, index) => {
                    var spanNew = "";
                    if(item.read_at == null) {
                        spanNew = '<span class="badge bg-danger">Baru</span>';
                    }
                    html += '<li>'+
                                '<hr class="dropdown-divider">'+
                            '</li>'+
                            '<a href="'+item.data.url+'"><li class="notification-item">'+
                                '<i class="bi bi-exclamation-circle text-info"></i>'+
                                '<div>'+
                                    '<h4>'+item.data.title+'&nbsp;'+spanNew+'</h4>'+
                                    '<p>'+item.data.message+'</p>'+
                                    '<p>'+moment(item.created_at).format("D MMMM Y")+'</p>'+
                                '</div>'+
                            '</li></a>';
                });

                $("#countNotif").html(res.notif);
                if(opsi == "markAsRead") {
                    $("#countNotif").html(0);
                    $(".notifications").append(html);
                }
            }
        });
    }
    
    notification();
    message();

    var page = 2;
    document.querySelector('.notifications').addEventListener("scroll", e => {
         var scrollHeight = e.currentTarget.scrollHeight - 10;
         var scrollTop = e.currentTarget.scrollTop + $(e.currentTarget).height();
         if(scrollHeight - scrollTop <= 15) {
            notification('markAsRead', page);
            page++;
         }
    });

    var pageMsg = 2;
    callMsg = true;
    document.querySelector('.messages').addEventListener("scroll", e => {
         var scrollHeight = e.currentTarget.scrollHeight - 10;
         var scrollTop = e.currentTarget.scrollTop + $(e.currentTarget).height();
         console.log(scrollHeight - scrollTop);
         if(scrollHeight - scrollTop <= 11) {
            message('open', pageMsg);
            pageMsg++;
         }
    });

    </script>
    @include('firebase')
    @stack('scripts')
</body>
</html>