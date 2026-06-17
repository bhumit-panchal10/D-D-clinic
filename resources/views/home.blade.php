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
                                    <div class="col-md-4">
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
                                    </div>

                                    <div class="row mt-4">
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
                                    </div>

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
                                                                <th>Amount</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @forelse($todayPatients as $key => $patient)
                                                                <tr>
                                                                    <td>{{ $key + 1 }}</td>

                                                                    <td>{{ $patient->case_no }}</td>

                                                                    <td>{{ $patient->name }}</td>

                                                                    <td>
                                                                        @foreach ($patient->notes as $note)
                                                                            {{ $note->treatment->treatment_name ?? '-' }}<br>
                                                                        @endforeach
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="5" class="text-center">
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
