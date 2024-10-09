@extends('layouts.dashboard.app', ['title' => 'Tambah Metode Pembayaran'])

@section('content')
    <div class="pagetitle">
        <h1>Tambah Metode Pembayaran</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Pembayaran</li>
                <li class="breadcrumb-item"><a href="{{ url('dashboard/payment-method') }}">Metode Pembayaran</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form method="POST" action="{{ route('payment-method.store') }}" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Informasi Metode Pembayaran</h5>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Tipe</label>
                                <div class="col-sm-10">
                                   <select class="selects  @error('payment_type') is-invalid @enderror" name="payment_type" class="form-control">
                                        <option></option>
                                        <option>Bank Transfer</option>
                                        <option>Virtual Account</option>
                                    </select>
                                    @error('payment_type')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Nama</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('payment_name') }}" name="payment_name" class="form-control @error('payment_name') is-invalid @enderror">
                                    @error('payment_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Cabang</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('branch_name') }}" name="branch_name" class="form-control @error('branch_name') is-invalid @enderror">
                                    @error('branch_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">No. Rek</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('account_number') }}" name="account_number" class="form-control @error('account_number') is-invalid @enderror">
                                    @error('account_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Atas Nama</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('account_holder') }}" name="account_holder" class="form-control @error('account_holder') is-invalid @enderror">
                                    @error('account_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Logo</label>
                                <div class="col-sm-10">
                                    <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror">
                                    @error('logo')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                             <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Service</label>
                                <div class="col-sm-10">
                                    <select class="selects  @error('payment_service') is-invalid @enderror" name="payment_service" class="form-control">
                                        <option></option>
                                        <option>Midtrans</option>
                                        <option>Manual</option>
                                    </select>
                                    @error('payment_service')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Aktif?</label>
                                <div class="col-sm-10">
                                    <select class="selects  @error('is_active') is-invalid @enderror" name="is_active" class="form-control">
                                        <option></option>
                                        <option value="1">Ya</option>
                                        <option value="0">Tidak</option>
                                    </select>
                                    @error('is_active')
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
        </form>
    </section>
@endsection