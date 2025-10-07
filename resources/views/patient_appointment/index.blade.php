@extends('layouts.app')
@section('title', 'Patient Appointments')
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
                @include('patient.show', ['id' => $id])

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Patient Appointments</h5>
                        <a href="{{ route('patient_appointment.create',$id) }}" class="btn btn-sm btn-primary">+ Add Appointment</a>
                    </div>

                    <div class="tab-content text-muted">
                            <div class="row">
                                <div class="col-lg-12">
                                        <div class="card-body">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Sr no.</th>
                                                        <th>Doctor Name</th>
                                                        <th>Appointment Date</th>
                                                        <th>Appointment Time</th>
                                                        <th>Appointment Confirm?</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($appointments as $key => $appointment)
                                                        <tr class="{{ $appointment->is_disrupted ? 'bg-light text-muted' : '' }}">
                                                        <td>{{ $appointments->firstItem() + $key }}</td>
                                                        <td>{{ $appointment->doctor->doctor_name }}</td>
                                                            <td>{{ date('d-m-Y', strtotime($appointment->appointment_date)) }}</td>
                                                            <td>{{ date('g:i A', strtotime($appointment->appointment_time)) }}</td>
                                                            <td>{{ $appointment->status == 1 ? 'Yes' : 'No' }}</td>
                                                            <td>
                                                                @if(!$appointment->is_disrupted)
                                                                    <a href="{{ route('patient_appointment.edit', $appointment->id) }}" class="btn btn-sm btn-primary">Edit</a>

                                                                     <!-- Delete Button (Trigger Modal) -->
                                                                    <button type="button" class="btn btn-sm btn-primary delete-appointment" data-id="{{ $appointment->id }}" data-bs-toggle="modal" data-bs-target="#deleteRecordModal">Delete</button>
                                                                @else
                                                                    <span class="text-muted"></span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="d-flex justify-content-center mt-3">
                                                {{ $appointments->links('pagination::bootstrap-4') }}
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Delete Modal Start -->
<div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                        colors="primary:#f7b84b,secondary:#f06548" style="width : 100px; height : 100px">
                    </lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4>Are you Sure?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to remove this appointment?</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn btn-primary" id="confirmDelete">Yes, Delete It!</button>
                    <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="appointment_id" id="deleteid" value="">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Delete Modal End -->


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Set appointment ID in modal hidden input when delete button is clicked
        document.querySelectorAll('.delete-appointment').forEach(button => {
            button.addEventListener('click', function () {
                let appointmentId = this.getAttribute('data-id');
                document.getElementById('deleteid').value = appointmentId;
            });
        });

        // Submit form when "Yes, Delete It!" button is clicked
        document.getElementById('confirmDelete').addEventListener('click', function () {
            let appointmentId = document.getElementById('deleteid').value;
            let actionUrl = "{{ route('patient_appointment.destroy', ':id') }}".replace(':id', appointmentId);
            document.getElementById('deleteForm').setAttribute('action', actionUrl);
            document.getElementById('deleteForm').submit();
        });
    });
</script>

@endsection
