@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col">
                        <div class="h-100">
                            <div class="row mb-3 pb-1">
                                <div class="col-12">
                                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                                        <div class="flex-grow-1">
                                            <h4 class="fs-16 mb-1">Dashboard</h4>
                                        </div>

                                    </div><!-- end card header -->
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->

                            @if (Auth::user()->role_id == 1)
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card text-white wrapper">
                                            <div class="card-body">
                                                <h5 class="card-title text-white">Today's Appointments</h5>
                                                <p class="card-text">
                                                    <strong>{{ $todayAppointmentsCount }}</strong>
                                                </p>
                                                <a href="{{ route('patient_appointment.today') }}"
                                                    class="btn btn-primary btn-sm">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Pending Collection Box -->
                                    <div class="col-md-4">
                                        <div class="card text-white wrapper">
                                            <div class="card-body">
                                                <h5 class="card-title text-white">Pending Collection Labwork</h5>
                                                <p class="card-text">
                                                    <strong>{{ $pendingCollectedCount }}</strong>
                                                </p>
                                                <a href="{{ route('labworks.full_list', ['filter' => 'pending_collection']) }}"
                                                    class="btn btn-primary btn-sm">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pending Received Box -->
                                    <div class="col-md-4">
                                        <div class="card text-white wrapper">
                                            <div class="card-body">
                                                <h5 class="card-title text-white">Pending Received Labwork</h5>
                                                <p class="card-text">
                                                    <strong>{{ $pendingReceivedCount }}</strong>
                                                </p>
                                                <a href="{{ route('labworks.full_list', ['filter' => 'pending_received']) }}"
                                                    class="btn btn-primary btn-sm">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mark As Received Pending -->
                                    {{-- <div class="col-md-4">
                                        <div class="card text-white wrapper">
                                            <div class="card-body">
                                                <h5 class="card-title text-white">Mark As Received Pending</h5>
                                                <p class="card-text">
                                                    <strong>{{ $MarkAsReceivedPending }}</strong>
                                                </p>
                                                <a href="{{ route('maintenance.index') }}" class="btn btn-primary btn-sm">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div> --}}

                                    {{-- <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Due Patient List</h5>
                                                </div>

                                                <div class="card-body table-responsive">
                                                    <table class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Case No</th>
                                                                <th>Patient Name</th>
                                                                <th>Due Amount</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @forelse($todayDuePatients as $due)
                                                                <tr>
                                                                    <td>{{ $due['case_no'] }}</td>

                                                                    <td>{{ $due['patient_name'] }}</td>

                                                                    <td>
                                                                        ₹ {{ number_format($due['due_amount'], 2) }}
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="3" class="text-center">
                                                                        No Due Found
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Today's Appointment List</h5>
                                                </div>

                                                <div class="card-body table-responsive">
                                                    <table class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Sr No</th>
                                                                <th>Patient Name</th>
                                                                <th>Mobile</th>
                                                                <th>Doctor</th>
                                                                <th>Time</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @forelse($todayAppointments as $key => $appointment)
                                                                <tr>
                                                                    <td>{{ $key + 1 }}</td>

                                                                    <td>
                                                                        {{ $appointment->patient->name ?? '-' }}
                                                                    </td>

                                                                    <td>
                                                                        {{ $appointment->mobile_no ?? '-' }}
                                                                    </td>

                                                                    <td>
                                                                        {{ $appointment->doctor->name ?? '-' }}
                                                                    </td>

                                                                    <td>
                                                                        {{ $appointment->rescheduled_time
                                                                            ? date('h:i A', strtotime($appointment->rescheduled_time))
                                                                            : date('h:i A', strtotime($appointment->appointment_time)) }}
                                                                    </td>

                                                                    <td>
                                                                        {{ ucfirst($appointment->status ?? '-') }}
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="6" class="text-center">
                                                                        No Appointment Found
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Today's Patient List</h5>
                                                </div>

                                                <div class="card-body table-responsive">
                                                    <table class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Sr No</th>
                                                                <th>Case No</th>
                                                                <th>Patient Name</th>
                                                                <th>Treatment</th>
                                                                <th>Due Amount</th>
                                                                <th>Payment Received</th>
                                                                <th>Instruction</th>
                                                                <th>Final Appointment</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @forelse($todayPatients as $key => $patient)
                                                                <tr>
                                                                    @php
                                                                        $latestNote = $patient->notes
                                                                            ->sortByDesc('date')
                                                                            ->first();
                                                                        $latestPayment = $patient->payments
                                                                            ->sortByDesc('payment_date')
                                                                            ->first();
                                                                    @endphp

                                                                    <td>{{ $key + 1 }}</td>

                                                                    <td>{{ $patient->case_no }}</td>

                                                                    <td><a href="{{ route('notes.index', $patient->id) }}">
                                                                            {{ trim($patient->name . ' ' . ($patient->middle_name ?? '') . ' ' . ($patient->last_name ?? '')) }}
                                                                        </a>
                                                                    </td>

                                                                    <td>
                                                                        {{ $latestNote && $latestNote->treatment ? $latestNote->treatment->treatment_name : '-' }}
                                                                    </td>
                                                                    <td><a
                                                                            href="{{ route('payments.index', $patient->id) }}">{{ number_format($patient->due_amount, 0) }}</a>
                                                                    </td>
                                                                    <td>{{ number_format($patient->paid_amount, 0) }}</td>


                                                                    <td>
                                                                        <a href="{{ route('appointment.create') }}">
                                                                            {{ $latestNote->next_appointment_date ?? '-' }}
                                                                        </a>
                                                                    </td>
                                                                    @php
                                                                        $latestAppointment = $patient->appointments
                                                                            ->sortByDesc('appointment_date')
                                                                            ->first();
                                                                    @endphp

                                                                    <td>
                                                                        <a href="{{ route('appointment.create') }}">
                                                                            {{ $latestAppointment->appointment_date ?? '-' }}
                                                                        </a>
                                                                    </td>

                                                                    <td>

                                                                        <form
                                                                            action="{{ route('patient.complete', $patient->id) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="note_id"
                                                                                value="{{ optional($latestNote)->id }}">
                                                                            <input type="hidden" name="payment_id"
                                                                                value="{{ optional($latestPayment)->id }}">

                                                                            <button type="submit"
                                                                                class="btn btn-success btn-sm">
                                                                                Complete
                                                                            </button>
                                                                        </form>

                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="9" class="text-center">
                                                                        No Record Found
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-header" style="padding-bottom:0px !important">
                                                    <form method="GET" action="{{ url()->current() }}" class="mb-3">
                                                        <div class="row align-items-end">
                                                            <div class="col-md-3">
                                                                <h5>Completed Patients List</h5>
                                                            </div>
                                                            <div class="col-md-4 gap-2 d-flex align-items-center">
                                                                <label for="date">Search Date:</label>

                                                                <input type="date" name="date" id="date"
                                                                    class="form-control" value="{{ $searchDate }}"
                                                                    style="width:60% !important">
                                                            </div>

                                                            <div class="col-md-2">
                                                                <button type="submit" class="btn btn-primary">
                                                                    Search
                                                                </button>

                                                                <a href="{{ url()->current() }}" class="btn btn-secondary">
                                                                    Reset
                                                                </a>
                                                            </div>

                                                        </div>
                                                    </form>
                                                </div>

                                                <div class="card-body table-responsive">

                                                    <table class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Sr No</th>
                                                                <th>Case No</th>
                                                                <th>Patient Name</th>
                                                                <th>Treatment</th>
                                                                <!--<th>Amount</th>-->
                                                                <th>Balance</th>
                                                                <th>Payment Received</th>
                                                                <th>Payment Mode</th>
                                                                <th>Final Appointment</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @forelse($completedPatients as $key => $patient)
                                                                <tr>

                                                                    <td>{{ $key + 1 }}</td>

                                                                    <td>{{ $patient->case_no }}</td>

                                                                    <td><a href="{{ route('notes.index', $patient->id) }}">
                                                                            {{ trim($patient->name . ' ' . ($patient->middle_name ?? '') . ' ' . ($patient->last_name ?? '')) }}
                                                                        </a>
                                                                    </td>

                                                                    <td>
                                                                        @php
                                                                            $latestNote = $patient->notes
                                                                                ->sortByDesc('date')
                                                                                ->first();
                                                                        @endphp

                                                                        {{ optional(optional($latestNote)->treatment)->treatment_name ?? '-' }}
                                                                    </td>
                                                                    <td><a
                                                                            href="{{ route('payments.index', $patient->id) }}">{{ number_format($patient->due_amount, 0) }}</a>

                                                                    </td>
                                                                    <td>{{ number_format($patient->paid_amount, 0) }}</td>

                                                                    <td>{{ optional($patient->payments->first())->mode ?? '-' }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $patient->appointments->first()
                                                                            ? date('d-m-Y', strtotime($patient->appointments->first()->appointment_date))
                                                                            : '-' }}
                                                                    </td>
                                                                    <!--<td>-->
                                                                    <!--    <a href="{{ route('appointment.create') }}">-->
                                                                    <!--        @foreach ($patient->notes as $note)
    -->
                                                                    <!--            {{ $note->next_appointment_date ?? '-' }}<br>-->
                                                                    <!--
    @endforeach-->
                                                                    <!--    </a>-->
                                                                    <!--</td>-->
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="8" class="text-center">
                                                                        No Record Found
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                    <div class="mt-3">
                                                        {{ $completedPatients->links() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card text-white wrapper">
                                    <div class="card-body">
                                        <h5 class="card-title text-white">Today's Appointments</h5>
                                        <p class="card-text">
                                            <strong>{{ $todayAppointmentsCount }}</strong>
                                        </p>
                                        <a href="{{ route('patient_appointment.today') }}" class="btn btn-primary btn-sm">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Pending Collection Box -->
                            <div class="col-md-4">
                                <div class="card text-white wrapper">
                                    <div class="card-body">
                                        <h5 class="card-title text-white">Pending Collection Labwork</h5>
                                        <p class="card-text">
                                            <strong>{{ $pendingCollectedCount }}</strong>
                                        </p>
                                        <a href="{{ route('labworks.full_list', ['filter' => 'pending_collection']) }}"
                                            class="btn btn-primary btn-sm">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Pending Received Box -->
                            <div class="col-md-4">
                                <div class="card text-white wrapper">
                                    <div class="card-body">
                                        <h5 class="card-title text-white">Pending Received Labwork</h5>
                                        <p class="card-text">
                                            <strong>{{ $pendingReceivedCount }}</strong>
                                        </p>
                                        <a href="{{ route('labworks.full_list', ['filter' => 'pending_received']) }}"
                                            class="btn btn-primary btn-sm">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Mark As Received Pending -->
                            <div class="col-md-4">
                                <div class="card text-white wrapper">
                                    <div class="card-body">
                                        <h5 class="card-title text-white">Mark As Received Pending</h5>
                                        <p class="card-text">
                                            <strong>{{ $MarkAsReceivedPending }}</strong>
                                        </p>
                                        <a href="{{ route('employee.maintenance.index') }}"
                                            class="btn btn-primary btn-sm">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <script>
                        document.write(new Date().getFullYear())
                    </script> © {{ env('APP_NAME') }}
                </div>
            </div>
        </div>
    </footer>
    </div>
    <!-- end main content-->


@endsection
