@extends('layouts.dashboard.app', ['title' => 'Tambah Kategori'])

@section('content')
    <div class="pagetitle">
        <h1>Tambah Kategori</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">User</li>
                <li class="breadcrumb-item"><a href="{{ url('dashboard/category') }}">Kategori</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form method="POST" action="{{ route('category.store') }}" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Informasi Kategori</h5>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Nama Kategori</label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('name') }}" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Category name...">
                                            @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Slug</label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('slug') }}" name="slug" class="form-control @error('slug') is-invalid @enderror" placeholder="Slug...">
                                            @error('slug')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Ikon</label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('Icon') }}" name="icon" class="form-control @error('Icon') is-invalid @enderror" placeholder="Bootstrap Icon...">
                                            @error('icon')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Sub-Kategori</label>
                                        <div class="col-sm-10">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" {{ old('isSubcategory') ? 'checked' : '' }} type="checkbox" name="isSubcategory" id="flexSwitchCheckDefault" value="Y">
                                                <label class="form-check-label" for="flexSwitchCheckDefault">
                                                    <p><b>Add as sub-category</b></p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3" id="parentCategory" style="{{ old('isSubcategory') ? '' : 'display:none' }}">
                                        <label for="inputText" class="col-sm-2 col-form-label">Kepala Kategori</label>
                                        <div class="col-sm-10">
                                            <select class="form-control selects @error('parent_id') is-invalid @enderror" name="parent_id">
                                                <option></option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : null }}>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('parent_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Aktif?</label>
                                        <div class="col-sm-10">
                                            <select class="form-control selects @error('status') is-invalid @enderror" name="status">
                                                <option></option>
                                                <option value="1" {{ old('status') == "1" ? 'selected' : '' }}>Ya</option>
                                                <option value="0" {{ old('status') == "0" ? 'selected' : '' }}>Tidak</option>
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
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Gambar Kategori</h5>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror mt-2">
                            @error('image')
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
                </div>>
            </div>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        $("input[name='isSubcategory']").on('change', function() {
            $("#parentCategory").css('display', 'none');
            if(this.checked) {
                $("#parentCategory").css('display', '');
            }
        });
    </script>
@endpush