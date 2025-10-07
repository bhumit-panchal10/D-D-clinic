@extends('layouts.app')

@section('title', 'Add User')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Add User</h4>
                            <div class="page-title-right">
                                <a href="{{ route('users.index') }}"
                                    class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="live-preview">
                                    <form method="POST" action="{{ route('users.store') }}" id="regForm">
                                        @csrf
                                        <div class="row gy-4">
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Name<span style="color:red;">*</span>
                                                    <input type="text" class="form-control" maxlength="30" id=""
                                                        placeholder="Enter Name" name="name" autocomplete="off"
                                                        value="{{ old('name') }}" required>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Phone<span style="color:red;">*</span>
                                                    <input type="text" class="form-control"
                                                        onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"
                                                        autocomplete="off" name="mobile_number"
                                                        value="{{ old('mobile_number') }}" minlength="10" maxlength="10"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                        placeholder="Enter Phone" required>

                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Email
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        id="" placeholder="Enter Email" name="email"
                                                        maxlength="30" autocomplete="off" value="{{ old('email') }}">
                                                    @error('email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Address
                                                    <input type="text" class="form-control" id=""
                                                        placeholder="Enter Address" name="address" maxlength="255"
                                                        autocomplete="off" value="{{ old('address') }}">
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    User Type
                                                    <select class="form-control" name="role_id" onchange="managerbind();"
                                                        id="managersssss">
                                                        <option selected disabled value="">Select User Type</option>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->id }}"
                                                                {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                                {{ $role->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Password<span style="color:red;">*</span>
                                                    <input type="password" class="form-control" maxlength="20"
                                                        name="password" autocomplete="off" value="{{ old('password') }}"
                                                        id="" placeholder="Enter Password" required>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Status
                                                    <select class="form-control" name="status">
                                                        <option selected disabled value="">Select Status</option>
                                                        <option value="1" selected>Active</option>
                                                        <option value="0">Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <div class="card-footer mt-5" style="float : right;">
                                            <button type="submit"
                                                class="btn btn-primary btn-user float-right mb-3">Save</button>
                                            <button type="reset"
                                                class="btn btn-primary float-right mr-3 mb-3">Clear</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

@endsection
