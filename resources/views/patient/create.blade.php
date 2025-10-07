@extends('layouts.app')
@section('title', 'Add Patient')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <!-- Add Patient Form -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Add Patient</h5>
                                <div class="page-title-right">
                                    <a href="{{ route('patient.index') }}" class="btn btn-sm btn-primary shadow-sm">
                                        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="patientForm" action="{{ route('patient.store') }}" method="POST">
                                    @csrf

                                    <div class="row">
                                        <div class="mb-3 col-lg-4">
                                            <label>Case No <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Case No"
                                                maxlength="30" name="case_no" value="{{ $caseno }}" required>
                                        </div>
                                        <div class="mb-3 col-lg-4">
                                            <label>Mobile 1 <span class="text-danger">*</span></label>
                                            <input type="text"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                class="form-control" name="mobile1" placeholder="Enter Mobile 1"
                                                maxlength="10" minlength="10" autocomplete="off" required>
                                            @error('mobile1')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-lg-4">
                                            <label>Patient Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Name"
                                                maxlength="30" name="name" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-3 col-lg-4">
                                            <label>Mobile 2 <span class="text-danger">*</span></label>
                                            <input type="text"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                class="form-control" name="mobile2" placeholder="Enter Mobile 2"
                                                maxlength="10" minlength="10" required>
                                            @error('mobile2')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-lg-4">
                                            <label>DOB</label>
                                            <input type="date" class="form-control" name="dob" id="dob">
                                        </div>

                                        <div class="mb-3 col-lg-4">
                                            <label>Gender <span class="text-danger">*</span></label>
                                            <select class="form-control" name="gender" id="gender">
                                                <option value="">Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>

                                            </select>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="mb-3 col-lg-6">
                                            <label>Address</label>
                                            <textarea class="form-control" placeholder="Enter Address" maxlength="255" name="address"></textarea>
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label>Pincode</label>
                                            <input type="text" class="form-control" placeholder="Enter Pincode"
                                                minlength="6" maxlength="6" name="pincode">
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label>Reference By</label>
                                            <input type="text" class="form-control" placeholder="Enter Reference By"
                                                maxlength="30" name="reference_by">
                                        </div>


                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                            <button type="reset" class="btn btn-primary">Clear</button>
                                        </div>

                                </form>
                            </div> <!-- card-body -->
                        </div> <!-- card -->
                    </div> <!-- col-lg-12 -->
                </div> <!-- row -->

            </div> <!-- container-fluid -->
        </div> <!-- page-content -->
    </div> <!-- main-content -->

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#patientForm").on("submit", function(e) {
                let mobile = $("input[name='mobile']").val().trim();
                if (!/^\d{10}$/.test(mobile)) {
                    alert("Mobile number must be exactly 10 digits!");
                    e.preventDefault();
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('input[name="mobile1"]').on('blur', function() {
                var mobile = $(this).val();

                if (mobile.length === 10) {
                    $.ajax({
                        url: '{{ route('patient.fetchByMobile') }}',
                        type: 'POST',
                        data: {
                            mobile: mobile,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.exists) {
                                const p = response.patient;

                                $('input[name="case_no"]').val(p.case_no);
                                $('input[name="name"]').val(p.name);
                                $('input[name="mobile2"]').val(p.mobile2);
                                $('input[name="dob"]').val(p.dob);
                                $('select[name="gender"]').val(p.gender);
                                $('textarea[name="address"]').val(p.address);
                                $('input[name="pincode"]').val(p.pincode);
                                $('input[name="reference_by"]').val(p.reference_by);

                                // Optionally, show an alert or visual cue
                                //alert('Existing patient data loaded.');
                            }
                        }
                    });
                }
            });
        });
    </script>
    <script>
        document.getElementById("dob").addEventListener("input", function() {
            let input = this.value;
            let parts = input.split("-");
            if (parts[0] && parts[0].length > 4) {
                parts[0] = parts[0].slice(0, 4); // Restrict the year to 4 digits
                this.value = parts.join("-");
            }
        });
    </script>
@endsection
