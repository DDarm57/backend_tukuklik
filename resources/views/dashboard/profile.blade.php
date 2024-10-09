@extends('layouts.dashboard.app', ['title' => 'Profile'])

@section('content')
 <div class="pagetitle">
    <h1>Profile</h1>
    <nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item">Users</li>
        <li class="breadcrumb-item active">Profile</li>
    </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section profile">
    <div class="row">
    <div class="col-xl-4">

        <div class="card">
        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

            <img src="{{ Helpers::photoProfile(Auth::user()->photo) }}" alt="Profile" class="image--cover">
            <h2>{{ $user->name }}</h2>
            <h3>{{ $user->getRoleNames()[0] }}</h3>
        </div>
        </div>

    </div>

    <div class="col-xl-8">

        <div class="card">
        <div class="card-body pt-3">
            <!-- Bordered Tabs -->
            <ul class="nav nav-tabs nav-tabs-bordered">

            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Ringkasan</button>
            </li>

            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Ubah Profil</button>
            </li>

            {{-- <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Settings</button>
            </li> --}}

            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Ubah Password</button>
            </li>

            </ul>
            <div class="tab-content pt-2">

            <div class="tab-pane fade show active profile-overview" id="profile-overview">

                <h5 class="card-title">Profil Detail</h5>

                <div class="row">
                <div class="col-lg-3 col-md-4 label ">Nama Lengkap</div>
                <div class="col-lg-9 col-md-8">{{ $user->name }}</div>
                </div>

                <div class="row">
                <div class="col-lg-3 col-md-4 label">No. Handphone</div>
                <div class="col-lg-9 col-md-8">{{ $user->phone_number ?? 'Belum Diatur' }}</div>
                </div>

                <div class="row">
                <div class="col-lg-3 col-md-4 label">Email</div>
                <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                </div>

                <div class="row">
                <div class="col-lg-3 col-md-4 label">Organisasi</div>
                <div class="col-lg-9 col-md-8">{{ $user->organization->org_name ?? 'Belum Diatur' }}</div>
                </div>

                <div class="row">
                <div class="col-lg-3 col-md-4 label">Tgl Registrasi</div>
                <div class="col-lg-9 col-md-8">{{ date('d F Y H:i:s',strtotime($user->created_at)) }}</div>
                </div>

            </div>

            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                <!-- Profile Edit Form -->
                <form method="POST" action="{{ route('profile.update', $user->id) }}" enctype="multipart/form-data">
                <div class="row mb-3">
                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Photo</label>
                    <div class="col-md-8 col-lg-9">
                    <img src="{{ Helpers::photoProfile(Auth::user()->photo) }}" alt="Profile" class="image--cover">
                    <input type="file" name="photo" class="form-control">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Nama Lengkap</label>
                    <div class="col-md-8 col-lg-9">
                    <input name="name" type="text" class="form-control" value="{{ $user->name }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email</label>
                    <div class="col-md-8 col-lg-9">
                    <input name="email" type="text" class="form-control" value="{{ $user->email }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Nomor Handphone</label>
                    <div class="col-md-8 col-lg-9">
                    <input name="phone_number" type="text" class="form-control" value="{{ $user->phone_number }}">
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-outline-danger">Simpan</button>
                </div>
                @csrf
                @method('PUT')
                </form>

            </div>

            <div class="tab-pane fade pt-3" id="profile-settings">

                <!-- Settings Form -->
                <form>

                <div class="row mb-3">
                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email Notifications</label>
                    <div class="col-md-8 col-lg-9">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="changesMade" checked>
                        <label class="form-check-label" for="changesMade">
                        Changes made to your account
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="newProducts" checked>
                        <label class="form-check-label" for="newProducts">
                        Information on new products and services
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="proOffers">
                        <label class="form-check-label" for="proOffers">
                        Marketing and promo offers
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="securityNotify" checked disabled>
                        <label class="form-check-label" for="securityNotify">
                        Security alerts
                        </label>
                    </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
                </form><!-- End settings Form -->

            </div>

            <div class="tab-pane fade pt-3" id="profile-change-password">
                <!-- Change Password Form -->
                <form>

                <div class="row mb-3">
                    <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Password Lama</label>
                    <div class="col-md-8 col-lg-9">
                    <input name="password" type="password" class="form-control" id="currentPassword">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Password Baru</label>
                    <div class="col-md-8 col-lg-9">
                    <input name="newpassword" type="password" class="form-control" id="newPassword">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Konfirmasi Password Baru</label>
                    <div class="col-md-8 col-lg-9">
                    <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-outline-danger">Ubah Password</button>
                </div>
                </form><!-- End Change Password Form -->

            </div>

            </div><!-- End Bordered Tabs -->

        </div>
        </div>

    </div>
    </div>
</section>
@endsection