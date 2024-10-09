@extends('layouts.dashboard.app', ['title' => 'Ubah Kategori'])

@section('content')
    <div class="pagetitle">
        <h1>Ubah Category</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Produk</li>
                <li class="breadcrumb-item"><a href="{{ url('dashboard/category') }}">Kategori</a></li>
                <li class="breadcrumb-item active">Ubah</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form method="POST" action="{{ route('category.update', $category->id) }}" enctype="multipart/form-data">
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
                                            <input type="text" value="{{ old('name') ?? $category->name }}" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Category name...">
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
                                            <input type="text" value="{{ old('slug') ?? $category->slug }}" name="slug" class="form-control @error('slug') is-invalid @enderror" placeholder="Slug...">
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
                                            <input type="text" value="{{ old('Icon') ?? $category->icon }}" name="icon" class="form-control @error('Icon') is-invalid @enderror" placeholder="Bootstrap Icon...">
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
                                                <input class="form-check-input" {{ old('isSubcategory') || $category->parent_id > 0 ? 'checked' : '' }} type="checkbox" name="isSubcategory" id="flexSwitchCheckDefault" value="Y">
                                                <label class="form-check-label" for="flexSwitchCheckDefault">
                                                    <p><b>Add as sub-category</b></p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3" id="parentCategory" style="{{ old('isSubcategory') || $category->parent_id > 0 ? '' : 'display:none' }}">
                                        <label for="inputText" class="col-sm-2 col-form-label">Kepala Kategori</label>
                                        <div class="col-sm-10">
                                            <select class="form-control selects @error('parent_id') is-invalid @enderror" name="parent_id">
                                                <option></option>
                                                @foreach($allCategories as $cat)
                                                    <option value="{{ $cat->id }}" {{ old('parent_id') == $cat->id ? 'selected' : ($category->parent_id == $cat->id ? 'selected' : null) }}>{{ $cat->name }}</option>
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
                                                <option value="1" {{ old('status') == "1" ? 'selected' : ($category->status == 1 ? 'selected' : '') }}>Ya</option>
                                                <option value="0" {{ old('status') == "0" ? 'selected' : ($category->status == 0 ? 'selected' : '') }}>Tidak</option>
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
                            <h5 class="card-title">Category Image</h5>
                            @if($category->categoryImage)
                                <div class="row justify-content-center">
                                    <img style="width:200px;" src="{{ $category->categoryImage ? Storage::url($category->categoryImage->image) : url('images/default-profile.png') }}" alt="Profile" class="img-fluid">
                                </div>
                            @endif
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
                </div>
            </div>
            <input type="hidden" name="_method" value="PUT">
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