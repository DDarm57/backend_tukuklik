@extends('layouts.auth', ['title' => 'Register'])

@section('content')
<div class="container-fluid px-1 px-md-5 px-lg-1 px-xl-5 py-5 mx-auto">
    <div class="card card0 border-0">
        <div class="row d-flex">
            <div class="col-lg-6">
                <div class="card1 pb-5">
                    <div class="row">
                        <img style="height:40px;" src="{{ Storage::url(Helpers::generalSetting()->logo) }}" class="logo">
                    </div>
                    <div class="row py-4 justify-content-center mt-4 border-line">
                        <img src="https://tukuklik.com/images/login_img.png" class="image">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card2 card border-0 px-4 py-5">
                    <h5 class="text-center">Verifikasi Akun Tukuklik</h6>
                    <p class="text-center">
                        @if($isVerified == "Y")
                            Verifikasi Berhasil, akun anda kini telah aktif.
                        @else 
                            Mohon maaf, verifikasi gagal dilakukan karena link tidak valid atau sudah kadaluwarsa.
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-blue py-4">
            <div class="row px-3">
                <small class="ml-4 ml-sm-5 mb-2">Copyright &copy; {{ date('Y') . " ". Helpers::generalSetting()->company_name }} All rights reserved.</small>
            </div>
        </div>
    </div>
</div>
@endsection