@extends('layouts.app')
@section('title', 'Edit Vendor')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                @include('common.alert')

                

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Edit Vendor</h5>
                        <a href="{{ route('vendor.index') }}" class="btn btn-sm btn-primary">Back</a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('vendor.update', ['id' => $vendor->id, 'page' => request()->input('page', 1)]) }}" method="post">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="page" value="{{ request()->input('page', 1) }}">


                            <div class="row">
                                <div class="mb-3 col-lg-4">
                                    <label>Company Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter Company Name"
                                        name="company_name" value="{{ old('company_name', $vendor->company_name) }}"
                                        required minlength="3" maxlength="100">
                                </div>

                                <div class="mb-3 col-lg-4">
                                    <label>Contact Person Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter Contact Person Name"
                                        name="contact_person_name"
                                        value="{{ old('contact_person_name', $vendor->contact_person_name) }}" required
                                        minlength="3" maxlength="100">
                                </div>

                                <div class="mb-3 col-lg-4">
                                    <label>Mobile <span class="text-danger">*</span></label>
                                    <input type="text"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);"
                                        class="form-control" placeholder="Enter Mobile" name="mobile"
                                        value="{{ old('mobile', $vendor->mobile) }}" required minlength="10" maxlength="10">
                                </div>

                                <div class="mb-3 col-lg-4">
                                    <label>Email</label>
                                    <input type="email" class="form-control" placeholder="Enter Email" name="email"
                                        value="{{ old('email', $vendor->email) }}" minlength="5" maxlength="100">
                                </div>

                                <div class="mb-3 col-lg-4">
                                    <label>Address</label>
                                    <textarea class="form-control" placeholder="Enter Address" name="address"
                                        maxlength="255">{{ old('address', $vendor->address) }}</textarea>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('vendor.index') }}" class="btn btn-primary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection