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
                                        <div class="mb-3 col-lg-3">
                                            <label>Case No <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Case No"
                                                maxlength="30" name="case_no" value="{{ $caseno }}" required>
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label>First</label>
                                            <input type="text" class="form-control" placeholder="Enter Name"
                                                maxlength="30" name="name">
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label>Middle</label>
                                            <input type="text" class="form-control" placeholder="Enter Name"
                                                maxlength="30" name="middle_name">
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label>Last</label>
                                            <input type="text" class="form-control" placeholder="Enter Name"
                                                maxlength="30" name="last_name">
                                        </div>


                                    </div>

                                    <div class="row">
                                        <div class="mb-3 col-lg-3">
                                            <label>Blood Group</label>
                                            <select name="blood_group" class="form-control" placeholder="Enter Blood Group">
                                                <option value="A+">A+</option>
                                                <option value="A−">A−</option>
                                                <option value="B+">B+</option>
                                                <option value="B−">B−</option>
                                                <option value="AB+">AB+</option>
                                                <option value="AB−">AB−</option>
                                                <option value="O+">O+</option>
                                                <option value="O−">O−</option>
                                                <option value="N/A">N/A</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label>Occupation</label>
                                            <input type="text" class="form-control" placeholder="Enter Occupation"
                                                maxlength="50" name="Occupation">
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label>Company Name</label>
                                            <input type="text" class="form-control" placeholder="Enter Company Name"
                                                maxlength="30" name="company_name">
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label>Mobile 1</label>
                                            <input type="text"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                class="form-control" name="mobile1" placeholder="Enter Mobile 1"
                                                maxlength="10" minlength="10" autocomplete="off">
                                            @error('mobile1')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="mb-3 col-lg-3">
                                            <label>Mobile 2</label>
                                            <input type="text"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                class="form-control" name="mobile2" placeholder="Enter Mobile 2"
                                                maxlength="10" minlength="10">
                                            @error('mobile2')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-lg-3">
                                            <label>DOB</label>
                                            <input type="date" class="form-control" name="dob" id="dob">
                                        </div>

                                        <div class="mb-3 col-lg-3">
                                            <label>Age</label>
                                            <input type="text" class="form-control" placeholder="Enter Age"
                                                name="Age" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                        </div>

                                        <div class="mb-3 col-lg-3">
                                            <label>Gender</label>
                                            <select class="form-control" name="gender" id="gender">
                                                <option value="">Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>

                                            </select>
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label>Email</label>
                                            <input type="email" class="form-control" name="email" id="email">
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="mb-4 col-lg-6">
                                            <label>Address</label>
                                            <textarea class="form-control" placeholder="Enter Address" maxlength="255" name="address"></textarea>
                                        </div>
                                        <div class="mb-4 col-lg-3">
                                            <label>Pincode</label>
                                            <input type="text" class="form-control" placeholder="Enter Pincode"
                                                minlength="6" maxlength="6" name="pincode">
                                        </div>
                                        {{-- <div class="mb-4 col-lg-3">
                                            <label>Reference By</label>
                                            <input type="text" class="form-control" placeholder="Enter Reference By"
                                                maxlength="30" name="reference_by">
                                        </div> --}}

                                        <hr>
                                        <h5>Medical History</h5>

                                        <div class="row">
                                            @php
                                                $medicalHistory = [
                                                    'diabetes' => 'Diabetes',
                                                    'hepatitis' => 'Hepatitis',
                                                    'bleeding_disorder' => 'Bleeding Disorder',
                                                    'hiv' => 'HIV/AIDS',
                                                    'pregnancy' => 'Pregnancy',
                                                    'bp' => 'Blood Pressure',
                                                    'tb' => 'Tuberculosis (TB)',
                                                    'asthma' => 'Asthma',
                                                    'epilepsy' => 'Epilepsy',
                                                    'lactation' => 'Lactation',
                                                    'heart' => 'Heart Disease',
                                                    'kidney' => 'Kidney Disease',
                                                    'liver' => 'Liver Disease',
                                                    'thyroid' => 'Thyroid Disease',
                                                    'other_disease' => 'Other Disease',
                                                ];
                                            @endphp

                                            @foreach ($medicalHistory as $key => $label)
                                                <div class="col-lg-3">
                                                    <label>
                                                        <input type="checkbox" name="medical_history[]"
                                                            value="{{ $key }}">
                                                        {{ $label }}
                                                    </label>
                                                </div>
                                            @endforeach
                                            <div class="col-lg-9">
                                                <label>Other Disease Comments</label>
                                                <textarea class="form-control" placeholder="Enter Comments" maxlength="255" name="other_disease_comments"></textarea>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="mb-3 col-lg-4">
                                                <label>Medications</label>
                                                <textarea class="form-control" placeholder="Enter medications" maxlength="255" name="medications"></textarea>

                                            </div>

                                            <div class="mb-3 col-lg-4">
                                                <label>Previous Surgery</label>
                                                <textarea class="form-control" placeholder="Enter Previous Surgery" maxlength="255" name="previous_surgery"></textarea>


                                            </div>

                                            <div class="mb-3 col-lg-4">
                                                <label>Drug / Dental Material Allergy</label>
                                                <textarea class="form-control" placeholder="Enter Material Allergy" maxlength="255" name="allergy"></textarea>

                                            </div>

                                            <div class="mb-3 col-lg-4">
                                                <label>Referred To Us By Name</label>
                                                <input class="form-control" placeholder="Enter Referred Name"
                                                    maxlength="255" name="referred_name">

                                            </div>
                                        </div>

                                        <hr>

                                        <h5>Habit</h5>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label><input type="checkbox" name="habit[]" value="smoking"> Smoking /
                                                    E-cigarette</label>
                                            </div>
                                            <div class="col-lg-4">
                                                <label><input type="checkbox" name="habit[]" value="gutka"> Padiki /
                                                    Gutka / Betel Nut</label>
                                            </div>
                                            <div class="col-lg-4">
                                                <label><input type="checkbox" name="habit[]" value="alcohol">
                                                    Alcohol</label>
                                            </div>
                                        </div>

                                        <hr>

                                        <h5>Referred To Us By</h5>
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-lg-2">
                                                <label>
                                                    <input type="checkbox" name="referred_by[]" value="self"> Self
                                                </label>
                                            </div>

                                            <div class="col-lg-2">
                                                <input type="checkbox" name="referred_by[]" value="friend/relative">
                                                Friend/Relative

                                            </div>
                                            <div class="col-lg-2">
                                                <input type="text" class="form-control" name="relative_name">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-2"><label><input type="checkbox" name="referred_by[]"
                                                        value="google"> Google</label></div>
                                            <div class="col-lg-2"><label><input type="checkbox" name="referred_by[]"
                                                        value="facebook"> Facebook</label></div>
                                            <div class="col-lg-2"><label><input type="checkbox" name="referred_by[]"
                                                        value="instagram"> Instagram</label></div>
                                            <div class="col-lg-2"><label><input type="checkbox" name="referred_by[]"
                                                        value="twitter"> Twitter</label></div>
                                            <div class="col-lg-2"><label><input type="checkbox" name="referred_by[]"
                                                        value="justdial"> Just Dial</label></div>
                                        </div>

                                        <hr>

                                        <h5>Reminder</h5>
                                        <div class="row">
                                            <div class="col-lg-2"><label><input type="checkbox" name="reminder[]"
                                                        value="6_months"> 6 Months</label></div>
                                            <div class="col-lg-2"><label><input type="checkbox" name="reminder[]"
                                                        value="1_year"> 1 Year</label></div>
                                            <div class="col-lg-2"><label><input type="checkbox" name="reminder[]"
                                                        value="2_years"> 2 Years</label></div>
                                            <div class="col-lg-2"><label><input type="checkbox" name="reminder[]"
                                                        value="never"> Never</label></div>
                                        </div>


                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                            <button type="reset" class="btn btn-primary">Clear</button>
                                        </div>
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
