@extends('layouts.app')
@section('title', 'Add Appointment')
@section('content')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    {{-- <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet"> --}}

    <style>
        /* Break time 1 PM to 4 PM full background */
        .fc .fc-bg-event.break-time-block {
            background-color: #d3d3d3 !important;
            opacity: 1 !important;
            border: none !important;
            border-radius: 0 !important;
            margin: 0 !important;
        }

        /* .fc .fc-timegrid-slot {*/
        /*    height: 35px !important;*/
        /*}*/

        /* Remove rounded box effect from background event */
        .fc .fc-timegrid-bg-harness .fc-bg-event {
            inset: 0 !important;
            border-radius: 0 !important;
        }

        /* .fc-timeGridWeek-view .fc-timegrid-event {
                                                                                                                                                                                                        min-width: 100px !important;
                                                                                                                                                                                                        min-height: 20px !important;
                                                                                                                                                                                                    } */

        /* Same-time appointments overlap fix */
        .fc-timeGridWeek-view .fc-timegrid-event,
        .fc-timeGridDay-view .fc-timegrid-event {
            min-width: 0 !important;
            max-width: 100% !important;
            min-height: 20px !important;
            overflow: hidden !important;
        }

        /* FullCalendar ko automatic width calculate karne dein */
        .fc-timegrid-event-harness {
            min-width: 90px !important;
        }

        .fc-timegrid-event .fc-event-main {
            width: 100% !important;
            overflow: hidden !important;
        }

        /* Long appointment text control */
        .fc-timegrid-event-title,
        .fc-timegrid-event .fc-event-title {
            display: block !important;
            width: 100% !important;
            white-space: normal !important;
            overflow: hidden !important;
            overflow-wrap: anywhere !important;
            word-break: break-word !important;
            line-height: 1.15 !important;
            font-size: 10px !important;
            text-align: center;
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
            min-width: 90px !important;
        }

        .fc-non-business {
            background-color: #d3d3d3 !important;
        }

        .fc .fc-timegrid-col:hover,
        .fc .fc-timegrid-slot:hover,
        .fc .fc-highlight {
            background: transparent !important;
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

        /* Same-time appointments side-by-side overlap fix */
        .fc-timeGridWeek-view .fc-timegrid-event,
        .fc-timeGridDay-view .fc-timegrid-event {
            min-width: 0 !important;
            max-width: 100% !important;
            overflow: hidden !important;
        }

        .fc .fc-timegrid-event-harness {
            min-width: 0 !important;
        }

        .fc .fc-timegrid-event .fc-event-main {
            width: 100% !important;
            overflow: hidden !important;
        }

        .fc .fc-timegrid-event-title {
            width: 100% !important;
            white-space: normal !important;
            word-break: break-word !important;
            overflow: hidden !important;
            font-size: 10px !important;
            line-height: 1.15 !important;
        }
    </style>

    <style>
        /* Remove all-day row */
        .fc .fc-timegrid-allday,
        .fc .fc-timegrid-divider {
            display: none !important;
        }

        /* Dark vertical borders */
        .fc-theme-standard td,
        .fc-theme-standard th,
        .fc .fc-scrollgrid,
        .fc .fc-scrollgrid td,
        .fc .fc-scrollgrid th {
            border-color: #b8b8b8 !important;
        }

        /* Half-hour horizontal line light */
        .fc .fc-timegrid-slot-minor td,
        .fc .fc-timegrid-slot-minor th {
            border-top: 1px solid #ececec !important;
        }

        /* One-hour horizontal line dark */
        .fc .fc-timegrid-slot-major td,
        .fc .fc-timegrid-slot-major th {
            border-top: 1px solid #9c9c9c !important;
        }

        /* Keep background visible */
        .fc .fc-bg-event {
            border-radius: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            opacity: 1 !important;
            border: none !important;
        }

        /* Gray background 1 PM to 4 PM */
        .fc .fc-bg-event.gray-time-block {
            background: #e3e3e3 !important;
            border: none !important;
        }

        /* Time label style */
        .fc .fc-timegrid-slot-label-cushion {
            font-size: 13px;
            color: #555;
        }

        /* Day header style */
        .fc .fc-col-header-cell-cushion {
            text-decoration: none !important;
            color: #333 !important;
            font-weight: 600;
        }

        /* Optional cleaner toolbar buttons */
        .fc .fc-button {
            box-shadow: none !important;
        }
    </style>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @include('common.alert')
                {{-- @include('patient.show', ['id' => $id]) --}}

                <!-- FullCalendar for Appointment Display -->

                {{-- <div class="card">
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
                </div> --}}
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
                            <label>
                                Duration in Minutes
                                <span class="text-danger">*</span>
                            </label>

                            <input type="number" class="form-control" id="duration" name="duration" min="15"
                                max="1440" step="15" value="30" required placeholder="Example: 120">
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

    <!-- Reschedule Modal -->
    {{-- <div class="modal fade" id="rescheduleModal">
        <div class="modal-dialog">
            <form method="POST" id="editAppointmentForm" action="{{ route('appointment.appointmentsUpdate') }}">
                @csrf
                @method('PUT')

                <div class="modal-content">

                    <div class="modal-header">
                        <h5>Reschedule Appointment</h5>

                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="appointment_id" id="edit_appointment_id">

                        <div class="mb-3">
                            <label>Patient</label>

                            <input type="text" id="edit_patient_name" class="form-control" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Doctor</label>

                            <select class="form-control" id="edit_doctor_id" name="doctor_id">

                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">
                                        {{ $doctor->doctor_name }}
                                    </option>
                                @endforeach

                            </select>

                        </div>

                        <div class="mb-3">
                            <label>Contact No</label>

                            <input class="form-control" id="edit_contact_no" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>

                            <input class="form-control" id="edit_email" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Duration</label>

                            <input class="form-control" id="edit_duration" name="duration">
                        </div>

                        <div class="mb-3">

                            <label>Treatment</label>

                            <select class="form-control" id="edit_treatment_id" name="treatment_id">

                                @foreach ($Treatments as $Treatment)
                                    <option value="{{ $Treatment->id }}">
                                        {{ $Treatment->treatment_name }}
                                    </option>
                                @endforeach

                            </select>

                        </div>

                        <div class="mb-3">

                            <label>Date</label>

                            <input type="date" id="edit_schedule_date" name="appointment_date" class="form-control">

                        </div>

                        <div class="mb-3">

                            <label>Time</label>

                            <input type="text" id="edit_followup_datetime" name="appointment_time"
                                class="form-control">

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button class="btn btn-primary">
                            Update Appointment
                        </button>
                        <button type="button" class="btn btn-danger" id="deleteAppointmentBtn">
                            Delete Appointment
                        </button>

                    </div>

                </div>

            </form>
            <form id="deleteAppointmentForm" method="POST">
                @csrf
                @method('DELETE')
            </form>

        </div>
    </div> --}}

    <!-- Reschedule Modal -->
    <div class="modal fade" id="rescheduleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">

            <form method="POST" id="editAppointmentForm" action="{{ route('appointment.appointmentsUpdate') }}">
                @csrf
                @method('PUT')

                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Reschedule Appointment</h5>

                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="appointment_id" id="edit_appointment_id">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Patient :</label>
                                    {{-- <p id="edit_patient_name"></p> --}}
                                    <a href="#" id="edit_patient_name">View Patient</a>
                                    {{-- <input type="text" id="edit_patient_name" class="form-control" readonly> --}}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Doctor</label>

                                    <select class="form-control" id="edit_doctor_id" name="doctor_id" required>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}">
                                                {{ $doctor->doctor_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Contact No</label>

                                    <input type="text" class="form-control" id="edit_contact_no" name="contact_no"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Email</label>

                                    <input type="email" class="form-control" id="edit_email" name="email" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Duration</label>

                                    <input type="number" class="form-control" id="edit_duration" name="duration"
                                        min="15" step="15" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Treatment</label>

                                    <select class="form-control" id="edit_treatment_id" name="treatment_id">
                                        @foreach ($Treatments as $Treatment)
                                            <option value="{{ $Treatment->id }}">
                                                {{ $Treatment->treatment_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Date</label>

                                    <input type="date" id="edit_schedule_date" name="appointment_date"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Time</label>

                                    <input type="text" id="edit_followup_datetime" name="appointment_time"
                                        class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">

                        <button type="submit" class="btn btn-primary">
                            Update Appointment
                        </button>

                        <button type="button" class="btn btn-danger" id="deleteAppointmentBtn">
                            Delete Appointment
                        </button>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>

                    </div>

                </div>
            </form>

            <form id="deleteAppointmentForm" method="POST">
                @csrf
                @method('DELETE')
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
            $('#patient_search').autocomplete({
                source: '{{ route('appointment.patients.search') }}',
                minLength: 2,
                appendTo: "#appointmentModal",
                select: function(event, ui) {
                    $('#patient_search').val(ui.item.value);
                    $('#patient_id').val(ui.item.id);

                    $.ajax({
                        url: '/admin/get-patient-details/' + ui.item.id,
                        method: 'GET',
                        success: function(data) {
                            console.log(data);
                            $('#contact_no').val(data.contact_no || '');
                            $('#email').val(data.email || '');
                        }
                    });
                    return false;
                }
            });

            var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'timeGridWeek',
                selectable: true,
                eventDisplay: 'block',

                slotEventOverlap: false,
                //eventMaxStack: 3,

                eventOrderStrict: true,
                eventOrder: 'start,-duration,title',
                selectMirror: false,

                slotMinTime: "09:00:00",
                slotMaxTime: "22:00:00",
                // Every row will represent 30 minutes
                slotDuration: "00:30:00",
                slotLabelInterval: "01:00:00",

                // Event ko supplied end time use karne dega
                forceEventDuration: true,
                defaultTimedEventDuration: "00:30:00",

                height: 'auto',
                contentHeight: 'auto',
                expandRows: false,

                allDaySlot: false,
                firstDay: 1,

                businessHours: [{
                        daysOfWeek: [0, 1, 2, 3, 4, 5, 6],
                        startTime: '09:00',
                        endTime: '13:00'
                    },
                    {
                        daysOfWeek: [0, 1, 2, 3, 4, 5, 6],
                        startTime: '16:00',
                        endTime: '21:00'
                    }
                ],

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },

                select: function(info) {
                    let start = new Date(info.start);

                    let date = start.toISOString().split('T')[0];

                    let hours = start.getHours().toString().padStart(2, '0');
                    let minutes = start.getMinutes().toString().padStart(2, '0');

                    let ampm = hours >= 12 ? 'PM' : 'AM';
                    hours = hours % 12;
                    hours = hours ? hours : 12;

                    let formattedTime = hours + ':' + minutes + ' ' + ampm;

                    $('#schedule_date').val(date);
                    $('#followup_datetime').val(formattedTime);

                    $('#appointmentModal').modal('show');
                },

                // eventClick: function(info) {

                //     console.log(info.event);

                //     // Here you will fill the edit modal

                //     $('#rescheduleModal').modal('show');
                // },
                eventClick: function(info) {
                    var event = info.event;
                    var props = event.extendedProps;

                    $('#edit_appointment_id').val(event.id);
                    $('#edit_appointment_id').val(event.id);

                    var url = "{{ url('/admin/notes') }}/" + props.patient_id;

                    $('#edit_patient_name')
                        .text(props.patient_name) // Display patient name
                        .attr('href', url); // Set link


                    $('#edit_doctor_id').val(props.doctor_id);
                    $('#edit_contact_no').val(props.mobile_no);
                    $('#edit_email').val(props.email);
                    $('#edit_duration').val(props.duration);
                    $('#edit_treatment_id').val(props.treatment_id);

                    var start = event.start;
                    var dateStr = start.getFullYear() + '-' +
                        String(start.getMonth() + 1).padStart(2, '0') + '-' +
                        String(start.getDate()).padStart(2, '0');
                    $('#edit_schedule_date').val(dateStr);

                    var hours = start.getHours();
                    var minutes = start.getMinutes().toString().padStart(2, '0');
                    var ampm = hours >= 12 ? 'PM' : 'AM';
                    hours = hours % 12;
                    hours = hours ? hours : 12;
                    $('#edit_followup_datetime').val(hours + ':' + minutes + ' ' + ampm);

                    $('#rescheduleModal').modal('show');
                },

                eventDidMount: function(info) {
                    const titleElement = info.el.querySelector('.fc-event-title');
                    if (titleElement) {
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

            $('#deleteAppointmentBtn').click(function() {

                if (!confirm('Are you sure you want to delete this appointment?')) {
                    return;
                }

                let id = $('#edit_appointment_id').val();

                let url = "{{ route('appointment.appointmentsDelete', ':id') }}";
                url = url.replace(':id', id);

                $('#deleteAppointmentForm')
                    .attr('action', url)
                    .submit();
            });

            function fetchAppointments() {
                var doctorId = $('#doctor_id_filter').val();


                $.ajax({
                    url: '{{ route('patient_appointment.getAppointments') }}',
                    method: 'GET',
                    data: {
                        doctor_id: doctorId
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log("Fetched Appointments:", data);

                        if (!Array.isArray(data)) {
                            alert("Invalid data received from server.");
                            return;
                        }

                        // var events = data.map(function(appointment) {
                        //     return {
                        //         title: appointment
                        //             .title,
                        //         start: appointment.start,
                        //         allDay: false,
                        //         color: appointment.color
                        //     };
                        // });
                        var events = data.map(function(appointment) {
                            return {
                                id: appointment.id,
                                title: appointment.title,

                                start: appointment.start,
                                end: appointment.end, // Important for event height

                                allDay: false,
                                display: 'block',

                                backgroundColor: appointment.color,
                                borderColor: appointment.color,

                                extendedProps: {
                                    patient_id: appointment.patient_id,
                                    patient_name: appointment.patient_name,
                                    case_no: appointment.case_no,
                                    doctor_id: appointment.doctor_id,
                                    treatment_id: appointment.treatment_id,
                                    mobile_no: appointment.mobile_no,
                                    email: appointment.email,
                                    duration: appointment.duration
                                }
                            };
                        });
                        // events.push({
                        //     startTime: '13:00:00',
                        //     endTime: '16:00:00',
                        //     daysOfWeek: [0, 1, 2, 3, 4, 5, 6],
                        //     display: 'background',
                        //     backgroundColor: '#d3d3d3'
                        // });

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
