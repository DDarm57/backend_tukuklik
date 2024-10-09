@extends('layouts.dashboard.app', ['title' => 'Halaman Frontend'])

@section('content')
    <div class="pagetitle">
        <h1>Halamat Frontend</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ url('dashboard/frontend') }}">Halaman Frontend</a></li>
                <li class="breadcrumb-item">Ubah</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form method="POST" action="{{ route('frontend.update', $page->id) }}">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Informasi Halaman</h5>
                             <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Judul</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('title') ?? $page->title }}" name="title" class="form-control @error('title') is-invalid @enderror">
                                    @error('title')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Konten</label>
                                <div class="col-sm-10">
                                    <textarea class="@error('content') is-invalid  @enderror" name="content" id="ckeditor">{{ old('content') ?? $page->content }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                             <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Slug</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('slug') ?? $page->slug }}" name="slug" class="form-control @error('slug') is-invalid @enderror">
                                    @error('slug')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Meta Title</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('meta_title') ?? $page->meta_title }}" name="meta_title" class="form-control @error('meta_title') is-invalid @enderror">
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
                                    <input type="text" value="{{ old('meta_keywords') ?? $page->meta_keywords }}" name="meta_keywords" class="form-control @error('meta_keywords') is-invalid @enderror">
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
                                    <input type="text" value="{{ old('meta_description') ?? $page->meta_description }}" name="meta_description" class="form-control @error('meta_description') is-invalid @enderror">
                                    @error('meta_description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Aktif?</label>
                                <div class="col-sm-10">
                                    <select class="selects form-control @error('is_actived') is-invalid @enderror" name="is_actived">
                                        <option></option>
                                        <option value="Y" {{ old('is_actived') == 'Y' || $page->is_actived == 'Y' ? 'selected' : '' }}>Ya</option>
                                        <option value="T" {{ old('is_actived') == 'T' || $page->is_actived == 'T' ? 'selected' : '' }}>Tidak</option>
                                    </select>
                                    @error('is_actived')
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

@push('scripts')
<script src="{{ url('admin/assets/vendor/ckeditor2/ckeditor.js') }}"></script>
<script src="https://unpkg.com/@yaireo/tagify"></script>
<script src="https://unpkg.com/@yaireo/tagify@3.1.0/dist/tagify.polyfills.min.js"></script>
<script>

var description = document.getElementById("ckeditor");
CKEDITOR.replace(description,{
    language:'en-gb'
});
CKEDITOR.config.allowedContent = true;
CKEDITOR.config.width = "100%";
CKEDITOR.config.height = "400px";
</script>
@endpush