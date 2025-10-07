@extends('layouts.app')
@section('title', 'Patient Appointments')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                @include('common.alert')

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">All Patient Appointments</h5>
                        <form method="GET" action="{{ route('employee.patient_appointment.index') }}" class="d-flex">
                            <input type="date" name="appointment_date" class="form-control me-2"
                                value="{{ request('appointment_date') }}">
                            <button type="submit" class="btn btn-primary mx-2">Search</button>
                            <a href="{{ route('employee.patient_appointment.index') }}" class="btn btn-primary">Clear</a>
                        </form>
                    </div>

                    <div class="tab-content text-muted">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sr no.</th>
                                                <th>Patient Name</th>
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
                                                    <td>{{ $appointment->patient->name }}</td>
                                                    <td>{{ $appointment->doctor->doctor_name }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($appointment->appointment_date)) }}</td>
                                                    <td>{{ date('g:i A', strtotime($appointment->appointment_time)) }}</td>
                                                    <td>{{ $appointment->status == 1 ? 'Yes' : 'No' }}</td>
                                                    <td>
                                                        @if($appointment->is_disrupted == 0) 
                                                            @if($appointment->status == 0)
                                                                <!-- Confirm Button -->
                                                                <form action="{{ route('employee.patient_appointment.confirm', $appointment->id) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit" class="btn btn-sm btn-primary"
                                                                        onclick="return confirm('Are you sure you want to confirm this appointment?')">
                                                                        Confirm
                                                                    </button>
                                                                </form>

                                                                <!-- Reschedule Button (Only before confirmation) -->
                                                                <button class="btn btn-sm btn-primary"
                                                                    onclick="showRescheduleModal({{ $appointment->id }})">
                                                                    Reschedule
                                                                </button>
                                                            @else
                                                                <!-- Show Confirmed Badge -->
                                                                <span class="badge bg-primary">Confirmed</span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center mt-3">
                                        {{ $appointments->appends(request()->query())->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reschedule Modal -->
            <div id="rescheduleModal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content p-3">
                        <form action="{{ route('employee.patient_appointment.reschedule', 0) }}" method="POST"
                            id="rescheduleForm">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Reschedule Appointment</h5>
                            </div>

                            <div class="modal-body">
                                <label for="rescheduled_date">New Date</label>
                                <input type="date" name="rescheduled_date" class="form-control" required>

                                <label for="rescheduled_time" class="mt-2">New Time</label>
                                <input type="time" name="rescheduled_time" class="form-control" required>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-primary custom-dismiss-btn">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Reschedule Modal Logic
        function showRescheduleModal(appointmentId) {
            const form = document.getElementById('rescheduleForm');
            form.action = `/employee/patient_appointment/reschedule/${appointmentId}`;
            $('#rescheduleModal').modal('show');
        }

        // Modal Dismiss Logic
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.custom-dismiss-btn').forEach(button => {
                button.addEventListener('click', function () {
                    $('#rescheduleModal').modal('hide');
                });
            });
        });
    </script>
@endsection
