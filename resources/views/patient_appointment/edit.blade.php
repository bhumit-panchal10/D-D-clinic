@extends('layouts.app')
@section('title', 'Edit Appointment')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center m-3">
                   <h5 class="mb-0">
                        Name: {{ $patient->name }} | Mobile No 1: {{ $patient->mobile1 }}
                        @if($patient->mobile2 != '')
                        | Mobile No 2: {{ $patient->mobile2 }}
                        @endif
                        | Case No: {{ $patient->case_no }}
                   </h5>            <a href="{{ route('patient.index') }}" class="btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                </a>
            </div>

            @include('common.alert')
            @include('patient.show', ['id' => $appointment->patient_id])  


            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Appointment</h5>
                </div>

                <div class="card-body">

                    <form action="{{ route('patient_appointment.update', $appointment->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="patient_id" value="{{ $appointment->patient_id }}">

                        <div class="row">
                            <!-- Doctor Name -->
                            <div class="col-md-4">
                                <label for="doctor_id" class="form-label">Doctor Name<span class="text-danger">*</span></label>
                                <select name="doctor_id" class="form-control" required>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" 
                                            {{ $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->doctor_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Appointment Date -->
                            <div class="col-md-4">
                                <label for="appointment_date" class="form-label">Appointment Date<span class="text-danger">*</span></label>
                                <input type="date" id="appointment_date" name="appointment_date" class="form-control" 
                                       value="{{ $appointment->appointment_date }}" required>
                            </div>

                            <!-- Appointment Time -->
                            <div class="col-md-4">
                                <label for="appointment_time" class="form-label">Appointment Time<span class="text-danger">*</span></label>
                                <input type="time" name="appointment_time" class="form-control" 
                                       value="{{ $appointment->appointment_time }}" required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('patient_appointment.index',$appointment->patient_id) }}" class="btn btn-primary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.getElementById("appointment_date").addEventListener("input", function () {
        let input = this.value;
        let parts = input.split("-");
        if (parts[0] && parts[0].length > 4) {
            parts[0] = parts[0].slice(0, 4); // Restrict the year to 4 digits
            this.value = parts.join("-");
        }
    });
</script>
@endsection
