@extends('layouts.dashboard.app', ['title' => 'Homepage SEO'])

@section('content')
    <div class="pagetitle">
        <h1>Homepage SEO</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Pengaturan</li>
                <li class="breadcrumb-item">Homepage SEO</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form method="POST" action="{{ url('dashboard/general-setting') }}">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Informasi Homepage SEO</h5>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Meta Title</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('meta_title') ?? Helpers::generalSetting()->meta_title }}" name="meta_title" class="form-control @error('meta_title') is-invalid @enderror">
                                    @error('meta_title')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Meta Keywords</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('meta_keywords') ?? Helpers::generalSetting()->meta_keywords }}" name="meta_keywords" class="form-control @error('meta_keywords') is-invalid @enderror">
                                    @error('meta_keywords')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Meta Deskripsi</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('meta_description') ?? Helpers::generalSetting()->meta_description }}" name="meta_description" class="form-control @error('meta_description') is-invalid @enderror">
                                    @error('meta_description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Meta Author</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('meta_author') ?? Helpers::generalSetting()->meta_author }}" name="meta_author" class="form-control @error('meta_author') is-invalid @enderror">
                                    @error('meta_author')
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