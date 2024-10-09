@extends('layouts.dashboard.app', ['title' => 'Tambah Organisasi'])

@section('content')
    <div class="pagetitle">
        <h1>Tambah Organisasi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Pengguna</li>
                <li class="breadcrumb-item"><a href="{{ url('dashboard/organization') }}">Organisasi</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form method="POST" action="{{ route('organization.store') }}">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Create Organization Form</h5>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Nama Organisasi</label>
                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('org_name') }}" name="org_name" class="form-control @error('org_name') is-invalid @enderror" placeholder="Organization name...">
                                    @error('org_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Level</label>
                                <div class="col-sm-10">
                                    <select class="form-control selects level @error('org_type') is-invalid @enderror" name="org_type">
                                        <option></option>
                                        <option {{ old('org_type') == 'BOD' ? 'selected' : null }}>BOD</option>
                                        <option {{ old('org_type') == 'Division' ? 'selected' : null }}>Division</option>
                                        <option {{ old('org_type') == 'Department' ? 'selected' : null }}>Department
                                        </option>
                                    </select>
                                    @error('org_type')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3 parent-row" style="display:none">
                                <label for="inputText" class="col-sm-2 col-form-label">Kepala</label>
                                <div class="col-sm-10">
                                    <select class="form-control selects w-100" name="parent_org_id" class="form-control @error('parent') is-invalid @enderror">
                                    </select>
                                    @error('parent_org_id')
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
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
    </section>
@endsection

@push('scripts')
    <script>

        $(".level").on('change', function() {
            if ($(this).val() != "BOD") {
                $(".parent-row").css("display", "");
                $.ajax({
                    url: `{{ url('dashboard/organization/type/${$(this).val()}') }}`,
                    dataType: 'json',
                    type: 'GET',
                    data: {
                        _token: $("meta[name='csrf_token']").attr('content')
                    },
                    success: function(res) {
                        res = res.data;
                        var option = "";
                        for (i = 0; i < res.length; i++) {
                            option +=
                                `<option value="${res[i].id}">${res[i].name} - ${res[i].type}</option>`;
                        }
                        console.log(option);
                        $("select[name='parent_org_id']").html(option);
                    }
                })
            } else {
                $(".parent-row").css("display", "none");
            }
        });

        $("#combo + span").addClass("is-invalid");

        $(".type").on('change', function() {

        })
    </script>
@endpush