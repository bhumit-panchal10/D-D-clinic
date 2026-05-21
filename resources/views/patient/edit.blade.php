@extends('layouts.app')
@section('title', 'Edit Patient')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <!-- Edit Patient Form -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Edit Patient</h5>
                                <div class="page-title-right">
                                    <a href="{{ route('patient.index') }}" class="btn btn-sm btn-primary shadow-sm">
                                        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="patientForm" action="{{ route('patient.update', $patient->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="mb-3 col-lg-3">
                                            <label>Case No <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Case No"
                                                maxlength="30" name="case_no" value="{{ $patient->case_no }}" required
                                                readonly>
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label>First Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Name"
                                                maxlength="30" name="name" value="{{ $patient->name }}" required>
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label>Middle Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Middle Name"
                                                maxlength="30" name="middle_name" value="{{ $patient->middle_name }}"
                                                required>
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label>Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Name"
                                                maxlength="30" name="last_name" value="{{ $patient->last_name }}" required>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="mb-3 col-lg-3">
                                            <label>Blood Group</label>
                                            <select name="blood_group" class="form-control" placeholder="Enter Blood Group">
                                                <option value="A+"{{ $patient->blood_group == 'A+' ? 'selected' : '' }}>
                                                    A+</option>
                                                <option value="A−"{{ $patient->blood_group == 'A−' ? 'selected' : '' }}>
                                                    A−</option>
                                                <option value="B+"{{ $patient->blood_group == 'B+' ? 'selected' : '' }}>
                                                    B+</option>
                                                <option value="B−"{{ $patient->blood_group == 'B−' ? 'selected' : '' }}>
                                                    B−</option>
                                                <option
                                                    value="AB+"{{ $patient->blood_group == 'AB+' ? 'selected' : '' }}>
                                                    AB+</option>
                                                <option
                                                    value="AB−"{{ $patient->blood_group == 'AB−' ? 'selected' : '' }}>
                                                    AB−</option>
                                                <option
                                                    value="O+"{{ $patient->blood_group == 'O+' ? 'selected' : '' }}>O+
                                                </option>
                                                <option
                                                    value="O−"{{ $patient->blood_group == 'O−' ? 'selected' : '' }}>O−
                                                </option>
                                                <option
                                                    value="N/A"{{ $patient->blood_group == 'N/A' ? 'selected' : '' }}>
                                                    N/A
                                                </option>
                                            </select>
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label>Occupation</label>
                                            <input type="text" class="form-control" placeholder="Enter Occupation"
                                                maxlength="50" name="Occupation" value="{{ $patient->Occupation }}">
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label>Company Name</label>
                                            <input type="text" class="form-control" placeholder="Enter Company Name"
                                                maxlength="30" name="company_name" value="{{ $patient->company_name }}">
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label>Mobile 1 <span class="text-danger">*</span></label>
                                            <input type="text"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                class="form-control" name="mobile1" placeholder="Enter Mobile Number"
                                                value="{{ $patient->mobile1 }}" maxlength="10" minlength="10" required>
                                            @error('mobile1')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label>Mobile 2 <span class="text-danger">*</span></label>
                                            <input type="text"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                                class="form-control" name="mobile2" placeholder="Enter Mobile Number"
                                                value="{{ $patient->mobile2 }}" maxlength="10" minlength="10" required>
                                            @error('mobile2')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label>DOB</label>
                                            <input type="date" class="form-control" name="dob"
                                                value="{{ $patient->dob }}">
                                        </div>

                                        <div class="mb-3 col-lg-3">
                                            <label>Age</label>
                                            <input type="text" class="form-control" name="Age"
                                                value="{{ $patient->Age }}"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label>Gender <span class="text-danger">*</span></label>
                                            <select class="form-control" name="gender" id="gender">
                                                <option value="">Select Gender</option>
                                                <option value="Male" @if ($patient->gender == 'Male') selected @endif>
                                                    Male</option>
                                                <option value="Female" @if ($patient->gender == 'Female') selected @endif>
                                                    Female</option>

                                            </select>
                                        </div>
                                        <div class="mb-3 col-lg-3">
                                            <label>Email</label>
                                            <input type="text" class="form-control" name="email"
                                                value="{{ $patient->email }}">
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="mb-3 col-lg-6">
                                            <label>Address</label>
                                            <textarea class="form-control" placeholder="Enter Address" maxlength="255" name="address">{{ $patient->address }}</textarea>
                                        </div>

                                        <div class="mb-3 col-lg-3">
                                            <label>Pincode</label>
                                            <input type="text" class="form-control" placeholder="Enter Pincode"
                                                minlength="6" maxlength="6" name="pincode"
                                                value="{{ $patient->pincode }}">
                                        </div>
                                        {{-- <div class="mb-3 col-lg-3">
                                            <label>Reference By</label>
                                            <input type="text" class="form-control" placeholder="Enter Reference By"
                                                maxlength="30" name="reference_by" value="{{ $patient->reference_by }}">
                                        </div> --}}
                                    </div>

                                    @php
                                        $medicalHistory = json_decode($patient->medical_history ?? '[]', true);
                                        $habit = json_decode($patient->habit ?? '[]', true);
                                        $referred = json_decode($patient->referred_by ?? '[]', true);
                                        $reminder = json_decode($patient->reminder ?? '[]', true);
                                    @endphp

                                    <hr>
                                    <h5>Medical History</h5>

                                    <div class="row">
                                        @php
                                            $medicalList = [
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

                                        @foreach ($medicalList as $key => $label)
                                            <div class="col-lg-3">
                                                <label>
                                                    <input type="checkbox" name="medical_history[]"
                                                        value="{{ $key }}"
                                                        {{ in_array($key, $medicalHistory) ? 'checked' : '' }}>
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        @endforeach
                                        <div class="col-lg-9">
                                            <label>Other Disease Comments</label>
                                            <textarea class="form-control" placeholder="Enter Comments" maxlength="255" name="other_disease_comments">{{ $patient->other_disease_comments }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-lg-4">
                                            <label>Medications</label>

                                            <textarea class="form-control" placeholder="Enter Medications" maxlength="255" name="medications">{{ $patient->medications }}</textarea>

                                        </div>

                                        <div class="mb-3 col-lg-4">
                                            <label>Previous Surgery</label>
                                            <textarea class="form-control" placeholder="Enter Previous Surgery" maxlength="255" name="previous_surgery">{{ $patient->previous_surgery }}</textarea>

                                        </div>

                                        <div class="mb-3 col-lg-4">
                                            <label>Drug / Dental Material Allergy</label>
                                            <textarea class="form-control" placeholder="Enter Allergy" maxlength="255" name="allergy">{{ $patient->allergy }}</textarea>

                                        </div>

                                        {{-- <div class="mb-3 col-lg-8">
                                            <label>Other Disease Comments</label>
                                            <textarea class="form-control" placeholder="Enter Comments" maxlength="255" name="other_disease_comments">{{ $patient->other_disease_comments }}</textarea>

                                        </div> --}}
                                        <div class="mb-3 col-lg-4">
                                            <label>Referred To Us By Name</label>
                                            <input class="form-control" placeholder="Enter Referred Name" maxlength="255"
                                                name="referred_name" value="{{ $patient->referred_name }}">

                                        </div>
                                    </div>

                                    <hr>

                                    <h5>Habit</h5>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <label>
                                                <input type="checkbox" name="habit[]" value="smoking"
                                                    {{ in_array('smoking', $habit) ? 'checked' : '' }}>
                                                Smoking / E-cigarette
                                            </label>
                                        </div>
                                        <div class="col-lg-4">
                                            <label>
                                                <input type="checkbox" name="habit[]" value="gutka"
                                                    {{ in_array('gutka', $habit) ? 'checked' : '' }}>
                                                Gutka / Betel Nut
                                            </label>
                                        </div>
                                        <div class="col-lg-4">
                                            <label>
                                                <input type="checkbox" name="habit[]" value="alcohol"
                                                    {{ in_array('alcohol', $habit) ? 'checked' : '' }}>
                                                Alcohol
                                            </label>
                                        </div>
                                    </div>

                                    <hr>

                                    <h5>Referred To Us By</h5>
                                    <div class="row mb-2">
                                        <div class="col-lg-2">
                                            <label>
                                                <input type="checkbox" name="referred_by[]" value="self"
                                                    {{ in_array('self', $referred) ? 'checked' : '' }}> Self
                                            </label>
                                        </div>

                                        <div class="col-lg-2">
                                            <input type="checkbox" name="referred_by[]" value="friend/relative"
                                                {{ in_array('friend/relative', $referred) ? 'checked' : '' }}>
                                            Friend/Relative

                                        </div>
                                        <div class="col-lg-2">
                                            <input type="text" class="form-control" name="relative_name"
                                                value="{{ $patient->relative_name }}">
                                        </div>

                                        {{-- <div class="col-lg-4">
                                            <input type="text" class="form-control" name="relative_name"
                                                placeholder="Enter Friend / Relative's name"
                                                value="{{ $patient->relative_name }}">
                                        </div> --}}
                                    </div>
                                    <div class="row">
                                        @foreach (['google', 'facebook', 'instagram', 'twitter', 'justdial'] as $ref)
                                            <div class="col-lg-2">
                                                <label>
                                                    <input type="checkbox" name="referred_by[]"
                                                        value="{{ $ref }}"
                                                        {{ in_array($ref, $referred) ? 'checked' : '' }}>
                                                    {{ ucfirst($ref) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <hr>

                                    <h5>Reminder</h5>
                                    <div class="row">
                                        @foreach (['6_months' => '6 Months', '1_year' => '1 Year', '2_years' => '2 Years', 'never' => 'Never'] as $key => $label)
                                            <div class="col-lg-2">
                                                <label>
                                                    <input type="checkbox" name="reminder[]" value="{{ $key }}"
                                                        {{ in_array($key, $reminder) ? 'checked' : '' }}>
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a href="{{ route('patient.index') }}" class="btn btn-primary">Cancel</a>
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
@endsection
