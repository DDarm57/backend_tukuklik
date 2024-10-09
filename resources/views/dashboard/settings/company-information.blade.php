@extends('layouts.dashboard.app', ['title' => 'Informasi Perusahaan'])

@section('content')
    <div class="pagetitle">
        <h1>Informasi Perusahaan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Pengaturan</li>
                <li class="breadcrumb-item">Informasi Perusahaan</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form method="POST" action="{{ url('dashboard/general-setting') }}">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Informasi Perusahaan</h5>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Nama Perusahaan</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('company_name') ?? Helpers::generalSetting()->company_name }}" name="company_name" class="form-control @error('company_name') is-invalid @enderror">
                                    @error('company_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('company_email') ?? Helpers::generalSetting()->company_email }}" name="company_email" class="form-control @error('company_email') is-invalid @enderror">
                                    @error('company_email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">No. Hp</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('company_phone') ?? Helpers::generalSetting()->company_phone }}" name="company_phone" class="form-control @error('company_phone') is-invalid @enderror">
                                    @error('company_phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Alamat</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="company_address">{{ old('company_address') ?? Helpers::generalSetting()->company_address }}</textarea>
                                    @error('company_address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Provinsi</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('province') ?? Helpers::generalSetting()->province }}" name="province" class="form-control @error('province') is-invalid @enderror">
                                    @error('province')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Kota</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('city') ?? Helpers::generalSetting()->city }}" name="city" class="form-control @error('city') is-invalid @enderror">
                                    @error('city')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Kecamatan</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('district') ?? Helpers::generalSetting()->district }}" name="district" class="form-control @error('district') is-invalid @enderror">
                                    @error('district')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Kelurahan</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('subdistrict') ?? Helpers::generalSetting()->subdistrict }}" name="subdistrict" class="form-control @error('subdistrict') is-invalid @enderror">
                                    @error('subdistrict')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Kode Pos</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('postcode') ?? Helpers::generalSetting()->postcode }}" name="postcode" class="form-control @error('postcode') is-invalid @enderror">
                                    @error('postcode')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Nama Direktur</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('director') ?? Helpers::generalSetting()->director }}" name="director" class="form-control @error('director') is-invalid @enderror">
                                    @error('director')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
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