@extends('layouts.auth', ['title' => 'Lupa Password'])

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
            <div class="col-lg-6" style="margin:0px auto;">
                <div class="card2 card border-0 py-5">
                    <h5 class="mb-4">Lupa Password Akun Tukuklik</h6>
                    <form action="{{ route('auth.reset-password-link') }}" method="POST">
                        <div class="row px-3 py-2">
                            <label class="mb-1"><h6 class="mb-0 text-sm">Alamat Email</h6></label>
                            <input class="form-control @error('email') is-invalid @enderror" type="text" name="email" placeholder="Masukkan Alamat Email">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror                        
                        </div>
                        <div class="row mb-3 px-3">
                            <button type="submit" class="btn btn-blue text-center">Submit</button>
                        </div>
                        @csrf
                    </form>
                    <div class="row mb-4 px-3">
                        <small class="font-weight-bold">Sudah Punya Akun? <a href="{{ url('login') }}" class="text-danger ">Login</a></small>
                    </div>
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