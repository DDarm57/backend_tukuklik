@extends('layouts.dashboard.app', ['title' => 'Ubah Varian'])

@section('content')
    <div class="pagetitle">
        <h1>Ubah Varian</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Produk</li>
                <li class="breadcrumb-item"><a href="{{ url('dashboard/attribute') }}">Varian</a></li>
                <li class="breadcrumb-item active">Ubah</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form method="POST" action="{{ route('attribute.update', $attr->id) }}">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Informasi Varian</h5>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Nama Varian</label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('name') ?? $attr->name }}" name="name" class="form-control @error('description') is-invalid @enderror" placeholder="Varian name...">
                                            @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Deskripsi</label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('description') ?? $attr->description }}" name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Description...">
                                            @error('description')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-12">
                                            <a id="addNew" href="#" class="btn btn-outline-danger"><i class="bi bi-plus-circle"></i> Tambah Baru</a>
                                        </div>
                                    </div>
                                    @foreach($attr->attributeValues as $values)
                                    <div  class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Isi Varian</label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('values.*') ?? $values->value }}" name="values[]" class="form-control @error('values.*') is-invalid @enderror" placeholder="Value...">
                                            <input type="hidden" name="values_id[]" value="{{ $values->id }}">
                                            @error('values.*')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    @endforeach
                                    <div id="bodyAttribute"></div>
                                     <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Aktif?</label>
                                        <div class="col-sm-10">
                                            <select class="form-control selects @error('status') is-invalid @enderror" name="status">
                                                <option></option>
                                                <option value="1" {{ old('status') || $attr->status == "1" ? 'selected' : '' }}>Ya</option>
                                                <option value="0" {{ old('status') || $attr->status == "0" ? 'selected' : '' }}>Tidak</option>
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
        var counter = 0;
        $("#addNew").on('click', function(e) {
            counterNext = counter+1;
            e.preventDefault();
            var newElement = '<div class="row mb-3 attr'+counterNext+'">'+
                                '<label for="inputText" class="col-sm-2 col-form-label">Attribute Values</label>'+
                                '<div class="col-sm-8">'+
                                    '<input type="text" name="values[]" class="form-control" placeholder="Value...">'+
                                    '<input type="hidden" name="values_id[]" value="">'+
                                '</div>'+
                                '<div class="col-sm-2">'+
                                    '<a onclick="deleted('+counterNext+')" class="btn btn-outline-danger w-100"><i class="bi bi-patch-minus"></i></a>'+
                                '</div>'+
                             '</div>';
            counter++;
            $("#bodyAttribute").append(newElement);
        });

        deleted = (data) => {
            $(".attr"+data).remove();
        }
    </script>
@endpush