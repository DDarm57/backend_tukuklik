@extends('layouts.dashboard.app', ['title' => 'Tambah Penjual'])

@section('content')
    <div class="pagetitle">
        <h1>Tambah Penjual</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Penjual</li>
                <li class="breadcrumb-item"><a href="{{ url('dashboard/merchant') }}">List Penjual</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form id="form" method="POST" action="{{ route('merchant.store') }}">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Informasi Penjual</h5>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Nama Penjual</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('name') }}" name="name" class="form-control @error('name') is-invalid @enderror">
                                    <span id="message_name" class="invalid-feedback" style="display:none">
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">PIC</label>
                                <div class="col-sm-10">
                                    <select class="selects" name="user_pic" class="form-control">
                                        <option></option>
                                        @foreach($user as $pic)
                                            <option value="{{ $pic->id }}">{{ $pic->name }}</option>
                                        @endforeach
                                    </select>
                                    <span id="message_pic_name" class="invalid-feedback" style="display:none">
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">No. Telp</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('phone') }}" name="phone" class="form-control @error('phone') is-invalid @enderror">
                                    <span id="message_phone" class="invalid-feedback" style="display:none">
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">NPWP</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('npwp') }}" name="npwp" class="form-control @error('npwp') is-invalid @enderror">
                                    <span id="message_npwp" class="invalid-feedback" style="display:none">
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Photo/Logo</label>
                                <div class="col-sm-10">
                                    <input type="file" value="{{ old('photo') }}" name="photo" class="form-control @error('photo') is-invalid @enderror">
                                    <span id="message_photo" class="invalid-feedback" style="display:none">
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">PKP?</label>
                                <div class="col-sm-10">
                                    <select class="selects" name="is_pkp" class="form-control">
                                        <option></option>
                                        <option value="Y">Ya</option>
                                        <option value="T">Tidak</option>
                                    </select>
                                    <span id="message_is_pkp" class="invalid-feedback" style="display:none">
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Aktif?</label>
                                <div class="col-sm-10">
                                    <select class="selects  @error('status') is-invalid @enderror" name="status" class="form-control">
                                        <option></option>
                                        <option value="1" {{ old('status') == "1" ? 'selected' : '' }}>Ya</option>
                                        <option value="0" {{ old('status') == "0" ? 'selected' : '' }}>Tidak</option>
                                    </select>
                                    <span id="message_status" class="invalid-feedback" style="display:none">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Informasi Alamat</h5>
                            <div class="row">
                                <div class="col-lg-12 mb-2">
                                    <label>Provinsi</label>
                                    <select class="selects form-control" id="province" name="province">
                                        <option></option>
                                        @foreach($province as $prov)
                                            <option value="{{ $prov->prov_id }}">{{ $prov->prov_name }}</option>
                                        @endforeach
                                    </select>
                                    <span id="message_province" class="invalid-feedback" style="display:none">
                                    </span>
                                </div>
                                <div class="col-lg-12 mb-2">
                                    <label>Kota / Kabupaten</label>
                                    <select class="selects form-control" id="city" name="city">
                                        <option></option>
                                    </select>
                                    <span id="message_city" class="invalid-feedback" style="display:none">
                                    </span>
                                </div>
                                <div class="col-lg-12 mb-2">
                                    <label>Kecamatan</label>
                                    <select class="selects form-control" id="district" name="district">
                                        <option></option>
                                    </select>
                                    <span id="message_district" class="invalid-feedback" style="display:none">
                                    </span>
                                </div>
                                <div class="col-lg-12 mb-2">
                                    <label>Kelurahan</label>
                                    <select class="selects form-control" id="subdistrict" name="subdistrict">
                                        <option></option>
                                    </select>
                                    <span id="message_subdistrict" class="invalid-feedback" style="display:none">
                                    </span>
                                </div> 
                                <div class="col-lg-12 mb-2">
                                    <label>Alamat Lengkap</label>
                                    <textarea class="form-control" name="address"></textarea>
                                    <span id="message_address" class="invalid-feedback" style="display:none">
                                    </span>
                                </div>
                                <div class="col-lg-12 mb-2">
                                    <label>Kode Pos</label>
                                    <input class="form-control" name="postcode" placeholder="Ex: 17612">
                                    <span id="message_postcode" class="invalid-feedback" style="display:none">
                                    </span>
                                </div>
                                <div class="col-lg-12 mb-2">
                                    <label>Nama Alamat</label>
                                    <input class="form-control" name="address_name" placeholder="Ex: Alamat Kantor, Alamat Cabang">
                                    <span id="message_address_name" class="invalid-feedback" style="display:none">
                                    </span>
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
                                    <a onclick="save()" class="btn btn-outline-danger btn-md w-100 submit"><i class="bi bi-save"></i> Simpan</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection

@push('scripts')
    @include('dashboard.javascript.js-merchant');
@endpush