@extends('layouts.dashboard.app', ['title' => 'Create Permission'])

@section('content')
    <div class="pagetitle">
        <h1>Create Permission</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Peran</li>
                <li class="breadcrumb-item"><a href="{{ url('dashboard/permission') }}">Permission</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <form method="POST" action="{{ route('permission.store') }}">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Role</h5>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Permission Name</label>
                                <div class="col-sm-10">
                                    <select class="form-control selects @error('role') is-invalid @enderror" name="role">
                                        <option></option>
                                        @foreach ($roles as $role)
                                            <option>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('role')
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
                        <div class="card-body">
                            <h5 class="card-title">Permissions</h5>
                            @error('permissions')
                                <p class="text-danger">{{ $message }} </p>
                            @enderror
                            <div class="row mb-3">
                                @foreach ($permissions as $permission)
                                    <div class="col-sm-4 mt-2">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" id="flexSwitchCheckDefault" value="{{ $permission['name'] }}">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">
                                                <h5><b>{{ ucfirst($permission['name']) }}</b></h5>
                                            </label>
                                        </div>
                                        @foreach ($permission['child'] as $child)
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" id="flexSwitchCheckDefault" value="{{ $child['name'] }}">
                                                <label class="form-check-label" for="flexSwitchCheckDefault">{{ $child['display'] }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
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
        $('.selects').select2({
            placeholder: 'Select an option',
            theme: "bootstrap"
        });

        $(".selects").on('change', function() {
            $.ajax({
                url: '{{ url('dashboard/permission/role') }}/' + $(this).val(),
                type: 'GET',
                data: {
                    _token: $("meta[name='csrf_token']").attr('content')
                },
                success: function(res) {
                    var checkbox = document.querySelectorAll("input[type='checkbox']");
                    checkbox.forEach((data, index) => {
                        checkbox[index].checked = false;
                        res.permissions.forEach((rows, index2) => {
                            console.log();
                            if (data.value == rows.name) {
                                checkbox[index].checked = true;
                            }
                        });
                    });
                }
            });
        });
    </script>
@endpush