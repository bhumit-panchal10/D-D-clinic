@extends('layouts.app')
@section('title', 'Add Appointment')
@section('content')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    {{-- <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet"> --}}

    <style>
        .fc-timeGridWeek-view .fc-timegrid-event {
            min-width: 100px !important;
            min-height: 20px !important;
        }



        .fc .fc-event {
            font-size: 11px;
            padding: 2px 4px;
            border-radius: 4px;
            overflow: hidden;
        }

        .fc-timegrid-event {
            padding: 2px 4px !important;
            font-size: 11px !important;
            line-height: 1.2 !important;
            min-height: 18px !important;
        }

        .fc-daygrid-event {
            padding: 2px 4px !important;
            /* ✅ Add this */
            font-size: 11px !important;
            /* ✅ Add this */
            line-height: 1.2 !important;
            /* ✅ Optional for more control */
            white-space: normal !important;
            text-overflow: ellipsis;
        }

        .fc-timegrid-event-title,
        .fc-event-title {
            white-space: normal !important;
        }
    </style>



    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @include('common.alert')
                {{-- @include('patient.show', ['id' => $id]) --}}

                <!-- FullCalendar for Appointment Display -->

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
                                        <a class="text-white" href="{{ route('appointment.create') }}">Reset</a>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li class="text-danger">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div id="calendar"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>



    <!-- Appointment Modal -->
    <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="appointmentForm" method="POST" action="{{ route('patient_appointment.appointmentsstore') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="appointmentModalLabel">Add Appointment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>


                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Patient</label>
                            <input type="text" class="form-control" id="patient_search"
                                placeholder="Search patient name">
                            <input type="hidden" id="patient_id" name="patient_id">


                        </div>


                        <div class="form-group">
                            <label>Doctor</label>
                            <select class="form-control" name="doctor_id">
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->doctor_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Contact No</label>
                            <input type="text" class="form-control" id="contact_no" name="contact_no" maxlength="10"
                                readonly>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" id="email" name="email" readonly>
                        </div>
                        <div class="form-group">
                            <label>Duration</label>
                            <input type="text" class="form-control" id="duration" name="duration">
                        </div>
                        <div class="form-group">
                            <label>Treatment</label>
                            <select class="form-control" name="treatment_id">
                                @foreach ($Treatments as $Treatment)
                                    <option value="{{ $Treatment->id }}">{{ $Treatment->treatment_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Schedule Date</label>
                            <input type="date" class="form-control" name="appointment_date" id="schedule_date">
                        </div>
                        <div class="form-group" id="follow_up_dateBox">
                            Time <span class="text-danger">*</span>
                            <input type="text" id="followup_datetime" name="appointment_time" class="form-control"
                                palaceholder="Select Time">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Appointment</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('scripts')
    <!-- jQuery FIRST -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 4 -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>

    <!-- jQuery UI for Autocomplete -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


    <script>
        $(document).ready(function() {
            // ✅ Autocomplete for Patient
            $('#patient_search').autocomplete({
                source: '{{ route('appointment.patients.search') }}',
                minLength: 2,
                appendTo: "#appointmentModal",
                select: function(event, ui) {
                    $('#patient_search').val(ui.item.value);
                    $('#patient_id').val(ui.item.id);

                    // Fetch additional patient details
                    $.ajax({
                        url: '/dental_clinic/admin/get-patient-details/' + ui.item.id,
                        method: 'GET',
                        success: function(data) {
                            
                            $('#contact_no').val(data.contact_no || '');
                            $('#email').val(data.email || '');
                        }
                    });
                    return false;
                }
            });

            // ✅ FullCalendar Setup
            var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'dayGridMonth',
                selectable: true,
                eventDisplay: 'block',
                slotEventOverlap: false,
                slotMinTime: "08:00:00",
                firstDay: 1,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: function(info, successCallback, failureCallback) {
                    $.ajax({
                        url: '{{ route('patient_appointment.getAppointments') }}',
                        method: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            const events = data.map(item => ({
                                title: item.title,
                                start: item.start,
                                allDay: false,
                                // backgroundColor: '#4caf50',
                                color: item.color
                                // color: '#4caf50'
                            }));
                            successCallback(events);
                        },
                        error: function() {
                            failureCallback();
                            alert('Failed to fetch appointments.');
                        }
                    });
                },
                dateClick: function(info) {

                    $('#schedule_date').val(info.dateStr);
                    $('#appointmentModal').modal('show');
                },
                // select: function(info) {
                //     // ✅ Works for week/day view

                //     const selectedDate = info.startStr.split('T')[0];
                //     console.log(selectedDate);
                //     $('#schedule_date').val(selectedDate);
                //     $('#appointmentModal').modal('show');
                // },
                eventDidMount: function(info) {
                    const titleElement = info.el.querySelector('.fc-event-title');
                    if (titleElement) {
                        // Wrap with <div> for safety and line break
                        const lines = info.event.title.split(' at ');
                        titleElement.innerHTML = `
                <div style="white-space: normal; text-align: center;">
                    <strong style="font-size: 12px;">${lines[0]}</strong><br>
                    <small>${lines[1] ?? ''}</small>
                </div>
            `;
                        info.el.setAttribute('title', info.event.title);
                    }
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
                                allDay: false,
                                color: appointment.color
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
        flatpickr("#followup_datetime", {
            enableTime: true,
            noCalendar: true, // Only time picker, no calendar
            dateFormat: "h:i K", // 12-hour format with AM/PM
            time_24hr: false, // Use AM/PM format
            minuteIncrement: 15, // Step of 15 minutes
            defaultHour: 9, // Open time picker at 9:00 AM
            defaultMinute: 0,
            minTime: "09:00", // Earliest selectable time
            maxTime: "21:00" // Latest selectable time (9:00 PM)
        });
    </script>
@endsection
