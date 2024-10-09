@extends('layouts.dashboard.app', ['title' => 'Update Flash Deal'])

@section('content')
    <div class="pagetitle">
        <h1>Ubah Flash Deal</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Marketing</li>
                <li class="breadcrumb-item"><a href="{{ url('dashboard/banner') }}">Flash Deal</a></li>
                <li class="breadcrumb-item active">Ubah</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form method="POST" action="{{ route('flash-deals.update', $flashDeal->id) }}" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Informasi Flash Deal</h5>
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <img src="{{ Storage::url($flashDeal->banner) }}" class="img-thumbnail img-fluid" style="height:100px;">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Banner</label>
                                <div class="col-sm-10">
                                    <input type="file" value="{{ old('file') }}" name="file" class="form-control @error('file') is-invalid @enderror">
                                    @error('file')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                             <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Tanggal Mulai</label>
                                <div class="col-sm-10">
                                    <input type="date" value="{{ old('start_date') ?? $flashDeal->start_date }}" name="start_date" class="form-control @error('start_date') is-invalid @enderror">
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
                                    <input type="date" value="{{ old('end_date') ?? $flashDeal->end_date }}" name="end_date" class="form-control @error('end_date') is-invalid @enderror">
                                    @error('end_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                             <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Pilih Produk</label>
                                <div class="col-sm-10">
                                    <select class="selects form-control product">
                                        <option></option>
                                        @foreach($product as $prod)
                                            <option value="{{ $prod->id }}">{{ $prod->product_name }}</option> 
                                        @endforeach
                                        <option>
                                    </select>
                                    @error('end_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Produk</label>
                                <div class="col-sm-10">
                                    <table class="table table-bordered">
                                        <thead>
                                            <th>Produk</th>
                                            <th>Diskon</th>
                                            <th>Tipe Diskon</th>
                                            <th>Aksi</th>
                                        </thead>
                                        <tbody>
                                            @foreach($flashDeal->flashDealProducts as $products)
                                                <tr id="{{ $products->product_id }}">
                                                    <td><input type="hidden" value="{{ $products->product_id }}" name="product[]" class="form-control">{{ $products->product->product_name }}</td>
                                                    <td><input type="text" name="discount[]" value="{{ $products->discount }}" class="form-control"></td>
                                                    <td>
                                                        <select class="selects form-control" name="discount_type[]">
                                                            <option></option>
                                                            <option {{ old('discount_type') == "Harga" || $products->discount_type == "Harga" ? 'selected' : '' }}>Harga</option>
                                                            <option {{ old('discount_type') == "Persentase" || $products->discount_type == "Persentase" ? 'selected' : '' }}>Persentase</option>
                                                        </select>
                                                    </td>
                                                    <td><a href="#" class="text-danger" onclick="deleted({{ $products->product_id }})">Hapus</a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
    <script>
        $(".product").change(function() {
            $.ajax({
                url : "{{ url('dashboard/product') }}/"+$(this).val(),
                type : "GET",
                dataType : "json",
                headers : {
                    'X-CSRF-TOKEN' : $("meta[name=csrf_token]").attr('content')
                },
                success:function(res) {
                    var td =    '<tr id="'+res.data.id+'">'+
                                    '<td><input type="hidden" value="'+res.data.id+'" name="product[]" class="form-control">'+res.data.product_name+'</td>'+
                                    '<td><input type="text" name="discount[]" class="form-control"></td>'+
                                    '<td>'+
                                        '<select class="selects form-control" name="discount_type[]">'+
                                            '<option></option>'+
                                            '<option>Harga</option>'+
                                            '<option>Persentase</option>'+
                                        '</select>'+
                                    '</td>'+
                                    '<td><a href="#" class="text-danger" onclick="deleted('+res.data.id+')">Hapus</a></td>'+
                                '</tr>';
                    $(".table tbody").append(td);
                }
            })
        });

        deleted = (id) => {
            $("tr#"+id).remove();
        }
    </script>
@endpush