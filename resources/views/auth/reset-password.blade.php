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
                <div class="card2 card border-0 py-5">
                    <h5 class="mb-4">Reset Password Akun Tukuklik</h6>
                    @if($isReset == "T")
                    <form action="{{ route('auth.reset-password', $user->id) }}" method="POST">
                        <div class="row px-3 py-2">
                            <label class="mb-1"><h6 class="mb-0 text-sm">Email Address</h6></label>
                            <input class="form-control @error('email') is-invalid @enderror" type="text" name="email" placeholder="Enter a valid email address">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror                        
                        </div>
                        <div class="row px-3 py-2">
                            <label class="mb-1"><h6 class="mb-0 text-sm">Kata Sandi Baru</h6></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Kata Sandi">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror    
                        </div>
                        <div class="row px-3 py-2">
                            <label class="mb-1"><h6 class="mb-0 text-sm">Konfirmasi Kata Sandi Baru</h6></label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" placeholder="Konfirmasi Kata Sandi">
                            @error('password_confirmation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror    
                        </div>
                        <div class="row mb-3 px-3">
                            <button type="submit" class="btn btn-blue text-center">Reset Password</button>
                        </div>
                        @csrf
                    </form>
                    @else 
                        <p class="font-weight-600">Mohon maaf, link tidak valid atau sudah kadaluwarsa.</p>
                    @endif
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