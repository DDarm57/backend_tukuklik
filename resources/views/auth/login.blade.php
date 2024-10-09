@extends('layouts.auth', ['title' => 'Login'])

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
                    <h5 class="text-center">Masuk Akun Tukuklik</h6>
                    <p class="text-muted text-center mb-4">Selamat Datang Kembali</p>
                    <form action="{{ route('auth.login') }}" method="POST">
                        <div class="row px-3">
                            <label class="mb-1"><h6 class="mb-0 text-sm">Alamat Email</h6></label>
                            <input class="form-control @error('email') is-invalid @enderror" type="text" name="email" placeholder="Masukkan Alamat Email">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror                        
                        </div>
                        <div class="row px-3 py-4">
                            <label class="mb-1"><h6 class="mb-0 text-sm">Kata Sandi</h6></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Masukkan Kata Sandi">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror    
                        </div>
                        <div class="row px-3 mb-4">
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input id="chk1" type="checkbox" name="chk" class="custom-control-input"> 
                                <label for="chk1" class="custom-control-label text-sm">Remember me</label>
                            </div>
                            <a href="{{ url('forgot-password') }}" class="ml-auto mb-0 text-sm">Lupa Password?</a>
                        </div>
                        <div class="row mb-3 px-3">
                            <button type="submit" class="btn btn-blue text-center">Login</button>
                        </div>
                        @csrf
                    </form>
                    <div class="row mb-4 px-3">
                        <small class="font-weight-bold">Belum Punya Akun? <a href="{{ url('register') }}" class="text-danger ">Register</a></small>
                    </div>
                    <div class="row px-3 mb-4">
                        <div class="line"></div>
                        <small class="or text-center">Atau Masuk Dengan</small>
                        <div class="line"></div>
                    </div>
                    <div class="row mb-4 px-3 justify-content-center">
                        <div class="col-lg-6">
                            <div class="facebook text-center mt-2">
                                <i class="bi bi-facebook"></i> Facebook
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="google text-center mt-2">
                                <i class="bi bi-google"></i> Google
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-blue py-4">
            <div class="row px-3">
                <small class="ml-4 ml-sm-5 mb-2">Copyright &copy; {{ date('Y') . " ". Helpers::generalSetting()->company_name }} All rights reserved.</small>
                <div class="social-contact ml-4 ml-sm-auto">
                    <span class="fa fa-facebook mr-4 text-sm"></span>
                    <span class="fa fa-google-plus mr-4 text-sm"></span>
                    <span class="fa fa-linkedin mr-4 text-sm"></span>
                    <span class="fa fa-twitter mr-4 mr-sm-5 text-sm"></span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
