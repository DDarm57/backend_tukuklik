@extends('layouts.dashboard.app', ['title' => 'Tambah Kupon Diskon'])

@section('content')
    <div class="pagetitle">
        <h1>Tambah Kupon Diskon</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Marketing</li>
                <li class="breadcrumb-item"><a href="{{ url('dashboard/banner') }}">Banner</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form method="POST" action="{{ route('coupon.store') }}">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Informasi Kupon</h5>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Judul Diskon</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('title') }}" name="title" class="form-control @error('title') is-invalid @enderror">
                                    @error('title')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Kode Diskon</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('coupon_code') }}" name="coupon_code" class="form-control @error('coupon_code') is-invalid @enderror">
                                    @error('coupon_code')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Tanggal Mulai</label>
                                <div class="col-sm-10">
                                    <input type="date" value="{{ old('start_date') }}" name="start_date" class="form-control @error('start_date') is-invalid @enderror">
                                    @error('start_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Tanggal Berakhir</label>
                                <div class="col-sm-10">
                                    <input type="date" value="{{ old('end_date') }}" name="end_date" class="form-control @error('end_date') is-invalid @enderror">
                                    @error('end_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Diskon</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('discount') }}" name="discount" class="form-control @error('discount') is-invalid @enderror">
                                    @error('discount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Tipe Diskon</label>
                                <div class="col-sm-10">
                                    <select class="selects @error('discount_type') is-invalid @enderror" name="discount_type" class="form-control">
                                        <option></option>
                                        <option>Harga</option>
                                        <option>Persentase</option>
                                    </select>
                                    @error('discount_type')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Min. Belanja</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('minimum_shopping') }}" name="minimum_shopping" class="form-control @error('minimum_shopping') is-invalid @enderror">
                                    @error('minimum_shopping')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Maks. Diskon</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('maximum_discount') }}" name="maximum_discount" class="form-control @error('maximum_discount') is-invalid @enderror">
                                    @error('maximum_discount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Multiple Pembelian?</label>
                                <div class="col-sm-10">
                                    <select class="selects  @error('is_multiple_buy') is-invalid @enderror" name="is_multiple_buy" class="form-control">
                                        <option></option>
                                        <option value="1">Ya</option>
                                        <option value="0">Tidak</option>
                                    </select>
                                    @error('status')
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
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
    </section>
@endsection