@extends('layouts.dashboard.app', ['title' => 'Tambah Metode Pengiriman'])

@section('content')
    <div class="pagetitle">
        <h1>Tambah Metode Pengiriman</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Pengiriman</li>
                <li class="breadcrumb-item"><a href="{{ url('dashboard/shipping-method') }}">Metode Pengiriman</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form method="POST" action="{{ route('shipping-method.store') }}">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Informasi Metode Pengiriman</h5>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Nama Metode</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('method_name') }}" name="method_name" class="form-control @error('method_name') is-invalid @enderror">
                                    @error('method_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Biaya / Kg</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('cost') }}" name="cost" class="form-control @error('cost') is-invalid @enderror">
                                    @error('cost')
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