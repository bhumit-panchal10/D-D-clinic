@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Edit User</h4>
                            <div class="page-title-right">
                                <a href="{{ route('users.index') }}"
                                    class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="live-preview">
                                    <form method="POST" action="{{ route('users.update', ['user' => $user->id]) }}">
                                        @csrf
                                        @method('post')
                                        <div class="row gy-4">
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Name<span style="color:red;">*</span>
                                                    <input type="text" class="form-control" maxlength="30" name="name"
                                                        value="{{ old('name') ? old('name') : $user->name }}" required>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Phone<span style="color:red;">*</span>
                                                    <input type="text" class="form-control"
                                                        onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"
                                                        name="mobile_number" minlength="10" maxlength="10"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                        value="{{ old('mobile_number') ? old('mobile_number') : $user->mobile_number }}"
                                                        required>

                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Email
                                                    <input type="email" maxlength="30"
                                                        class="form-control  @error('email') is-invalid @enderror"
                                                        name="email"
                                                        value="{{ old('email') ? old('email') : $user->email }}">
                                                    @error('email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Address
                                                    <input type="text" maxlength="255" class="form-control"
                                                        name="address"
                                                        value="{{ old('address') ? old('address') : $user->address }}">
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
                                                                {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                                                {{ $role->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Status
                                                    <select class="form-control" name="status">
                                                        <option selected disabled value="">Select Status</option>
                                                        <option value="1"
                                                            {{ old('role_id') ? (old('role_id') == 1 ? 'selected' : '') : ($user->status == 1 ? 'selected' : '') }}>
                                                            Active</option>
                                                        <option value="0"
                                                            {{ old('role_id') ? (old('role_id') == 0 ? 'selected' : '') : ($user->status == 0 ? 'selected' : '') }}>
                                                            Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <div class="card-footer mt-5" style="float : right;">
                                            <button type="submit"
                                                class="btn btn-primary btn-user float-right mb-3">Update</button>
                                            <a class="btn btn-primary float-right mr-3 mb-3"
                                                href="{{ route('users.index') }}">Cancel</a>
                                        </div>
                                        <!--end row-->
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->

            </div>
        </div>
    </div>


@endsection


@section('scripts')


@endsection
