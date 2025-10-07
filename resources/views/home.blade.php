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
