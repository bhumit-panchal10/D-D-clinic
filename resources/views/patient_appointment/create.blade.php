@extends('layouts.app')
@section('title', 'Add Appointment')
@section('content')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
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
                   </h5>                    <a href="{{ route('patient.index') }}" class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                    </a>
                </div>

                @include('common.alert')
                @include('patient.show', ['id' => $id])

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Add Appointment</h5>
                    </div>

                    <div class="card-body">

                        <form action="{{ route('patient_appointment.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="patient_id" value="{{ $id }}">

                            <div class="row">
                                <!-- Doctor Name -->
                                <div class="col-md-4">
                                    <label for="doctor_id" class="form-label">Doctor Name<span
                                            class="text-danger">*</span></label>
                                    <select name="doctor_id" class="form-control" required>
                                        <option value="" disabled selected>Select Doctor</option>
                                        @foreach ($doctors->sortBy('doctor_name') as $doctor)
                                            <option value="{{ $doctor->id }}">{{ $doctor->doctor_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Appointment Date -->
                                <div class="col-md-4">
                                    <label for="appointment_date" class="form-label">Appointment Date<span
                                            class="text-danger">*</span></label>
                                    <input type="date" id="appointment_date" name="appointment_date" class="form-control"
                                        required>
                                </div>

                                <!-- Appointment Time -->
                                <div class="col-md-4">
                                    <label for="appointment_time" class="form-label">Appointment Time<span
                                            class="text-danger">*</span></label>
                                    <input type="time" name="appointment_time" class="form-control" required>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-primary">Clear</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Appointment Search & Calendar -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Search Appointments</h5>
                    </div>

                    <div class="card-body">
                        <form id="filter-form">
                            @csrf
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <label for="doctor_id_filter" class="form-label">Doctor Name</label>
                                    <select name="doctor_id" id="doctor_id_filter" class="form-control" required>
                                        <option value="" disabled selected>Select Doctor</option>
                                        @foreach ($doctors->sortBy('doctor_name') as $doctor)
                                            <option value="{{ $doctor->id }}">{{ $doctor->doctor_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 d-flex gap-2">
                                    <button type="button" class="btn btn-primary" id="searchAppointments">
                                        Search
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="resetFilters">
                                        <a class="text-white"
                                            href="{{ route('patient_appointment.create', $id) }}">Reset</a>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- FullCalendar for Appointment Display -->
                <div class="card">
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery, FullCalendar, and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [],
                eventContent: function(arg) {
                    return {
                        html: `<div style="white-space: normal; text-align: center;">
                            ${arg.event.title.replace(' at', '<br>at')}
                       </div>`
                    };
                }
            });

            calendar.render();

            // Function to fetch appointments
            function fetchAppointments() {
                var doctorId = $('#doctor_id_filter').val();


                $.ajax({
                    url: '{{ route('patient_appointment.getAppointments') }}',
                    method: 'GET',
                    data: {
                        doctor_id: doctorId
                    },
                    dataType: 'json', // Ensure correct data format
                    success: function(data) {
                        console.log("Fetched Appointments:", data); // Debugging - Check data in console

                        if (!Array.isArray(data)) {
                            alert("Invalid data received from server.");
                            return;
                        }

                        var events = data.map(function(appointment) {
                            return {
                                title: appointment
                                    .title, // Using the formatted title from the controller
                                start: appointment.start,
                                allDay: false
                            };
                        });

                        calendar.removeAllEvents(); // Clear previous events
                        calendar.addEventSource(events); // Add new events
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error); // Debugging - Log errors
                        alert("Failed to fetch appointments. Please try again.");
                    }
                });
            }

            // Load all appointments by default
            fetchAppointments();


            // Search Button Click Event
            $('#searchAppointments').on('click', function() {
                fetchAppointments();
            });

            // Reset Filters Button Click Event
            $('#resetFilters').on('click', function() {
                $('#doctor_id_filter').val('');
                calendar.removeAllEvents(); // Clear calendar
            });
        });
    </script>

    <script>
        document.getElementById("appointment_date").addEventListener("input", function() {
            let input = this.value;
            let parts = input.split("-");
            if (parts[0] && parts[0].length > 4) {
                parts[0] = parts[0].slice(0, 4); // Restrict the year to 4 digits
                this.value = parts.join("-");
            }
        });
    </script>
@endsection
