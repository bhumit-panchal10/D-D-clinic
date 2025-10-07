@extends('layouts.app')
@section('title', 'Add Vendor')
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
                    <h5 class="card-title mb-0">Add Vendor</h5>
                    <a href="{{ route('vendor.index') }}" class="btn btn-sm btn-primary">Back</a>
                </div>

                <div class="card-body">
                    <form action="{{ route('vendor.store') }}" method="post">
                        @csrf

                        <div class="row">
                            <div class="mb-3 col-lg-4">
                                <label>Company Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Company Name" name="company_name" required minlength="3" maxlength="100">
                            </div>

                            <div class="mb-3 col-lg-4">
                                <label>Contact Person Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Contact Person Name" name="contact_person_name" required minlength="3" maxlength="100">
                            </div>

                            <div class="mb-3 col-lg-4">
                                <label>Mobile <span class="text-danger">*</span></label>
                                <input type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" class="form-control" placeholder="Enter Mobile" name="mobile" required minlength="10" maxlength="10">
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-lg-4">
                                <label>Email</label>
                                <input type="email" class="form-control" placeholder="Enter Email" name="email" minlength="5" maxlength="100">
                            </div>

                            <div class="mb-3 col-lg-4">
                                <label>Address</label>
                                <textarea class="form-control" placeholder="Enter Address" name="address" maxlength="255"></textarea>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="reset" class="btn btn-primary">Clear</button>
                        </div>
                    </form>
                </div> 
            </div> 
        </div> 
    </div> 
</div> 

@endsection
