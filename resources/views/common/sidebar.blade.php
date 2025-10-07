@php
    //dd(Auth::user())
    $users = Auth::user();
@endphp
<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box px-0">
        <!-- Dark Logo-->
        <a href="{{ route('home') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo5.png') }}" alt="" height="50">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/images/logo5.png') }}" alt="" height="120" width="100%">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('home') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo5.png') }}" alt="" height="50">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/images/logo5.png') }}" alt="" height="120" width="100%">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu"></span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link @if (request()->routeIs('home')) {{ 'active' }} @endif"
                        href="{{ route('home') }}">
                        <i class="mdi mdi-speedometer"></i>
                        <span data-key="t-dashboards">Dashboards</span>
                    </a>
                </li>

                <hr class="menu-divider">

                @if (Auth::user()->role_id == 1)
                    <li class="nav-item">
                        <a class="nav-link menu-link @if (request()->routeIs('users.index')) {{ 'active' }} @endif || @if (request()->routeIs('users.create')) {{ 'active' }} @endif"
                            href="#master" data-bs-toggle="collapse" role="button" aria-expanded="false"
                            aria-controls="master">
                            <i class="fa-solid fa-cogs"></i> <!-- Icon for Master Entry -->
                            <span data-key="t-apps">Master Entry</span>
                        </a>
                        <div class="collapse menu-dropdown" id="master">
                            <ul class="nav nav-sm flex-column">

                                <li class="nav-item">
                                    <a href="{{ route('Diagnosis.index') }}" class="nav-link"
                                        data-key="t-chat">Diagnosis
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('treatment.index') }}" class="nav-link"
                                        data-key="t-chat">Treatment
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('subtreatment.index') }}" class="nav-link" data-key="t-chat">Sub
                                        Treatment
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('lab.index') }}" class="nav-link" data-key="t-calendar">Lab
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('dosage.index') }}" class="nav-link" data-key="t-calendar">Dosage
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('medicine.index') }}" class="nav-link"
                                        data-key="t-calendar">Medicine
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('concern_form_master.index') }}" class="nav-link"
                                        data-key="t-calendar">Consent Form
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('Caseno.index') }}" class="nav-link" data-key="t-calendar">Case
                                        No
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>

                    <hr class="menu-divider">

                    <li class="nav-item">
                        <a class="nav-link menu-link @if (request()->routeIs('user.*')) active @endif"
                            href="{{ route('users.index') }}">
                            <i class="fas fa-user"></i> <!-- User Icon -->
                            <span data-key="t-user">Manage User</span>
                        </a>
                    </li>

                    <hr class="menu-divider">

                    <li class="nav-item">
                        <a class="nav-link menu-link @if (request()->routeIs('doctors.index')) {{ 'active' }} @endif"
                            href="{{ route('doctors.index') }}">
                            <i class="fas fa-user-md"></i> <!-- Doctor Icon -->
                            <span data-key="t-doctor">Doctor</span>
                        </a>
                    </li>

                    <hr class="menu-divider">

                    <li class="nav-item">
                        <a class="nav-link menu-link @if (request()->routeIs('patient.index')) {{ 'active' }} @endif"
                            href="{{ route('patient.index') }}">
                            <i class="fas fa-user-injured"></i>
                            <span data-key="t-dashboards">Patient</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link @if (request()->routeIs('appointment.create')) {{ 'active' }} @endif"
                            href="{{ route('appointment.create') }}">
                            <i class="fas fa-clock"></i>
                            <span data-key="t-dashboards">Appointment</span>
                        </a>
                    </li>

                    <hr class="menu-divider">

                    <li class="nav-item">
                        <a class="nav-link menu-link @if (request()->routeIs('vendor.*')) active @endif"
                            href="{{ route('vendor.index') }}">
                            <i class="fas fa-industry"></i> <!-- Vendor Icon -->
                            <span data-key="t-vendor">Vendor</span>
                        </a>
                    </li>
                    <hr class="menu-divider">

                    <li class="nav-item">
                        <a class="nav-link menu-link @if (request()->routeIs('product.*')) active @endif"
                            href="{{ route('product.index') }}">
                            <i class="fas fa-box"></i> <!-- Product Icon -->
                            <span data-key="t-product">Product</span>
                        </a>
                    </li>
                    <hr class="menu-divider">

                    <li class="nav-item">
                        <a class="nav-link menu-link @if (request()->routeIs('product_purchase.*')) active @endif"
                            href="{{ route('product_purchase.index') }}">
                            <i class="fas fa-shopping-cart"></i> <!-- Product Purchase Icon -->
                            <span data-key="t-product-purchase">Product Purchase</span>
                        </a>
                    </li>

                    <hr class="menu-divider">

                    <li class="nav-item">
                        <a class="nav-link menu-link @if (request()->routeIs('maintenance.*')) active @endif"
                            href="{{ route('maintenance.index') }}">
                            <i class="fas fa-tools"></i> <!-- Maintenance Icon -->
                            <span data-key="t-maintenance">Maintenance Register</span>
                        </a>
                    </li>

                    <hr class="menu-divider">

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#reports" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="reports">
                            <i class="fa-solid fa-file-alt"></i> <!-- Icon for Reports -->
                            <span>Report Master</span>
                        </a>
                        <div class="collapse menu-dropdown" id="reports">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('payments.report') }}" class="nav-link">Payment Reports</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('patients.report') }}" class="nav-link">Patient Reports</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('reports.due_payments') }}" class="nav-link">Due Payment
                                        Reports</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('reports.pay_to_dr') }}" class="nav-link">Pay To Dr</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('reports.lab_work_Report') }}" class="nav-link">
                                        Lab Work
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link menu-link @if (request()->routeIs('employee.*')) active @endif"
                            href="{{ route('employee.patient_appointment.index') }}">
                            <i class="fas fa-calendar-check"></i> <!-- Appointment Icon -->
                            <span data-key="t-maintenance">Appointments</span>
                        </a>
                    </li>

                    <hr class="menu-divider">

                    <li class="nav-item">
                        <a class="nav-link menu-link @if (request()->routeIs('employee.*')) active @endif"
                            href="{{ route('employee.labworks.index') }}">
                            <i class="fas fa-flask"></i> <!-- Labwork Icon -->
                            <span data-key="t-maintenance">Labworks</span>
                        </a>
                    </li>

                    <hr class="menu-divider">

                    <li class="nav-item">
                        <a class="nav-link menu-link @if (request()->routeIs('employee.*')) active @endif"
                            href="{{ route('employee.product_purchase.index') }}">
                            <i class="fas fa-shopping-cart"></i> <!-- Product Purchase Icon -->
                            <span data-key="t-product-purchase">Product Purchase</span>
                        </a>
                    </li>

                    <hr class="menu-divider">

                    <li class="nav-item">
                        <a class="nav-link menu-link @if (request()->routeIs('employee.*')) active @endif"
                            href="{{ route('employee.maintenance.index') }}">
                            <i class="fas fa-tools"></i> <!-- Maintenance Icon -->
                            <span data-key="t-maintenance">Maintenance Register</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
