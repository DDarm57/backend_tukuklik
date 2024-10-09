@extends('layouts.dashboard.app', ['title' => 'Ubah Staff'])

@section('content')
    <div class="pagetitle">
        <h1>Ubah Staff</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Pengguna</li>
                <li class="breadcrumb-item"><a href="{{ url('dashboard/staff') }}">Staff</a></li>
                <li class="breadcrumb-item active">Ubah</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form method="POST" action="{{ route('staff.update', $staff->id) }}" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Informasi Staff</h5>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Nama Staff</label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('name') ?? $staff->name }}" name="name" class="form-control @error('name') is-invalid @enderror " placeholder="Staff name...">
                                            @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('email') ?? $staff->email }}" name="email" class="form-control @error('email') is-invalid @enderror " placeholder="Email...">
                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">No. Telp</label>
                                        <div class="col-sm-10">
                                            <input type="text" value="{{ old('phone_number') ?? $staff->phone_number }}" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror " placeholder="Phone Number...">
                                            @error('phone_number')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Tgl. Lahir</label>
                                        <div class="col-sm-10">
                                            <input type="date" value="{{ old('date_of_birth') ?? $staff->date_of_birth }}" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror " placeholder="Date Of Birth...">
                                            @error('date_of_birth')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Organisasi</label>
                                        <div class="col-sm-10">
                                            <select class="form-control selects @error('organization_id') is-invalid @enderror" name="organization_id">
                                                <option></option>
                                                @foreach ($organization as $org)
                                                    <option value="{{ $org->id }}" {{ old('organization_id') == $org->id ? 'selected' : ($staff->organization_id == $org->id ? 'selected' : '') }}>{{ $org->org_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('organization_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Role</label>
                                        <div class="col-sm-10">
                                            <select class="form-control selects @error('role') is-invalid @enderror" name="role">
                                                <option></option>
                                                @foreach ($role as $r)
                                                    <option value="{{ $r->name }}" {{ old('role') == $r->name ? 'selected' : ($staff->roles->pluck('name')[0] == $r->name ? 'selected' : '') }}>{{ $r->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('role')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputText" class="col-sm-2 col-form-label">Aktif?</label>
                                        <div class="col-sm-10">
                                            <select class="form-control selects @error('is_actived') is-invalid @enderror"
                                                name="is_actived">
                                                <option></option>
                                                <option value="Y" {{ old('is_actived') == 'Y' ? 'selected' : ($staff->is_actived == 'Y' ? 'selected' : '') }}>Ya</option>
                                                <option value="T" {{ old('is_actived') == 'T' ? 'selected' : ($staff->is_actived == 'T' ? 'selected' : '') }}>Tidak</option>
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
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Password</h5>
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-2 col-form-label">Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password...">
                                                @error('password')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputText" class="col-sm-2 col-form-label">Konfirmasi Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" name="password_confirmation" class="form-control @error('confirm_password') is-invalid @enderror" placeholder="Confirm Password...">
                                                @error('password')
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
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Photo Profile</h5>
                            <div class="row justify-content-center">
                                <img style="width:200px;" src="{{ url('images/default-profile.png') }}" alt="Profile" class="rounded-circle">
                            </div>
                            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror mt-2">
                            @error('photo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body p-3">
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