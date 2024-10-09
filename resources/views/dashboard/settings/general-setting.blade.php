@extends('layouts.dashboard.app', ['title' => 'Pengaturan Umum'])

@section('content')
    <div class="pagetitle">
        <h1>Pengaturan Umum</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Pengaturan</li>
                <li class="breadcrumb-item">Pengaturan Umum</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form method="POST" action="{{ url('dashboard/general-setting') }}" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Informasi Pengaturan Umum</h5>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Nama Sistem</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('system_name') ?? Helpers::generalSetting()->system_name }}" name="system_name" class="form-control @error('system_name') is-invalid @enderror">
                                    @error('system_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Facebook URL</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('facebook_url') ?? Helpers::generalSetting()->facebook_url }}" name="facebook_url" class="form-control @error('facebook_url') is-invalid @enderror">
                                    @error('meta_keywords')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Instagram URL</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('instagram_url') ?? Helpers::generalSetting()->instagram_url }}" name="instagram_url" class="form-control @error('instagram_url') is-invalid @enderror">
                                    @error('instagram_url')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Twitter URL</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('twitter_url') ?? Helpers::generalSetting()->twitter_url }}" name="twitter_url" class="form-control @error('twitter_url') is-invalid @enderror">
                                    @error('twitter_url')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Expired Permintaan</label>
                                <div class="col-sm-10">
                                    <div class="input-group mb-3"> 
                                        <input value="{{ old('expired_rfq') ?? Helpers::generalSetting()->expired_rfq }}" type="number" name="expired_rfq" class="form-control @error('expired_rfq') is-invalid @enderror"> 
                                        <span class="input-group-text" id="basic-addon2">Hari</span>
                                        @error('expired_rfq')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Expired Penawaran</label>
                                <div class="col-sm-10">
                                    <div class="input-group mb-3"> 
                                        <input value="{{ old('expired_quotation') ?? Helpers::generalSetting()->expired_quotation }}" type="number" name="expired_quotation" class="form-control @error('expired_quotation') is-invalid @enderror"> 
                                        <span class="input-group-text" id="basic-addon2">Hari</span>
                                        @error('expired_quotation')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Expired Pesanan</label>
                                <div class="col-sm-10">
                                    <div class="input-group mb-3"> 
                                        <input value="{{ old('expired_po') ?? Helpers::generalSetting()->expired_po }}" type="number" name="expired_po" class="form-control @error('expired_po') is-invalid @enderror"> 
                                        <span class="input-group-text" id="basic-addon2">Hari</span>
                                        @error('expired_po')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">

                            <h5 class="card-title">Logo</h5>
                            <img src="{{ Storage::url(Helpers::generalSetting()->logo) }}">
                            <input type="file" class="form-control mt-4 @error('logo') is-invalid @enderror" name="logo">
                            @error('logo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <h5 class="card-title">Favicon</h5>
                            <img src="{{ Storage::url(Helpers::generalSetting()->favicon) }}">
                            <input type="file" class="form-control mt-4 @error('favicon') 'is-invalid' @enderror" name="favicon">
                            @error('favicon')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body> p-3">
                            <div class="row">
                                <div class="col-8">
                                    <h5 class="mt-2">Simpan?</h5>
                                </div>
                                <div class="col-sm-12 col-lg-2">
                                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-md w-100"><i class="bi bi-x-circle"></i> Batal</a>
                                </div>
                                <div class="col-sm-12 col-lg-2">
                                    <button class="btn btn-outline-danger btn-md w-100"><i class="bi bi-save"></i> Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @csrf
            @method('PUT')
        </form>
    </section>
@endsection