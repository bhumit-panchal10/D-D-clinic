@extends('layouts.app')

@section('title', 'Patient Backup Report')

@section('content')

    <style>
        .patient-backup-page {
            background: #f5f7fb;
            min-height: 100vh;
            padding-bottom: 30px;
        }

        /* ===============================
                                                    PAGE HEADER
                                                ================================ */

        .backup-main-title {
            color: #172554 !important;
            font-size: 23px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .backup-main-subtitle {
            color: #667085;
            font-size: 13px;
            margin: 0;
        }


        /* ===============================
                                                    FILTER
                                                ================================ */

        .backup-filter-box {
            background: #ffffff;
            border: 1px solid #e4e7ec;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 20px;

            box-shadow:
                0 2px 8px rgba(0, 0, 0, .03);
        }


        /* ===============================
                                                 PATIENT CARD
                                            ================================ */

        .patient-report-card {
            background: #ffffff;
            border: 1px solid #e4e7ec;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 18px;

            box-shadow:
                0 3px 12px rgba(16, 24, 40, .05);
        }


        /* Header */

        .patient-report-header {
            padding: 15px 18px;
            border-bottom: 1px solid #eaecf0;
        }

        .patient-report-name {
            margin: 0 0 5px 0;
            color: #101828 !important;
            font-size: 17px;
            font-weight: 700;
        }

        .patient-report-meta {
            color: #667085;
            font-size: 12px;
        }


        /* ===============================
                                             STATUS
                                          ================================ */

        .backup-status {
            padding: 5px 10px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
        }

        .backup-status-active {
            background: #fff6dc;
            color: #946200;
        }

        .backup-status-completed {
            background: #dcfce7;
            color: #166534;
        }


        /* ===============================
                                    SUMMARY
                                    ================================ */
        .patient-summary-wrapper {
            background: #fafbfc;
            padding: 14px 18px;
            border-bottom: 1px solid #eaecf0;
        }

        .backup-summary {
            padding: 12px 14px;
            border-radius: 8px;
            min-height: 72px;
        }

        .backup-summary small {
            display: block;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: .3px;
            margin-bottom: 5px;
        }

        .backup-summary strong {
            display: block;
            font-size: 17px;
        }

        .summary-treatment {
            background: #eaf5ff;
            color: #075985;
        }

        .summary-paid {
            background: #eaf8ee;
            color: #166534;
        }

        .summary-due {
            background: #fff0f0;
            color: #b42318;
        }

        .summary-record {
            background: #f1efff;
            color: #5142a6;
        }


        /* ===============================
                                  DETAILS
                                ================================ */

        .backup-section-title {

            color: #253c7c !important;

            font-size: 14px !important;

            font-weight: 700 !important;

            border-left: 4px solid #253c7c;

            padding-left: 9px;

            margin-bottom: 13px;

            line-height: 19px;
        }


        .patient-info-box {

            background: #fbfcfe;

            border: 1px solid #eaecf0;

            border-radius: 8px;

            padding: 13px;
        }


        .patient-info-item {

            color: #475467;

            font-size: 12px;

            padding: 5px 0;

            word-break: break-word;
        }


        .patient-info-item strong {

            color: #101828 !important;

            font-weight: 600;
        }

        /* ===============================
                                    TABLE
                                ================================ */

        .backup-table {

            border-color: #e4e7ec !important;

            margin-bottom: 0;
        }


        /*
                                                                                                        YOUR HEADING COLOR FIX
                                                                                                        */

        .backup-table thead th {

            background: #253c7c !important;

            color: #ffffff !important;

            border-color: #3d5087 !important;

            font-size: 11px !important;

            font-weight: 600 !important;

            padding: 8px !important;

            white-space: nowrap;

            vertical-align: middle;
        }


        .backup-table tbody td {

            color: #344054 !important;

            border-color: #eaecf0 !important;

            font-size: 11px;

            padding: 8px !important;

            vertical-align: middle;
        }


        .backup-table tbody tr:nth-child(even) {

            background: #fafbfc;
        }


        .backup-table tfoot th,
        .backup-table tfoot td {

            background: #f2f4f7 !important;

            color: #101828 !important;

            font-weight: 700 !important;

            padding: 8px !important;
        }


        .empty-record {

            color: #98a2b3 !important;

            padding: 20px !important;

            font-style: italic;
        }


        /* ===============================
                                                                                                           BUTTON
                                                                                                        ================================ */

        .backup-details-btn {

            background: #ffffff;

            color: #344054;

            border: 1px solid #d0d5dd;

            border-radius: 6px;

            padding: 6px 10px;

            font-size: 12px;
        }


        .backup-details-btn:hover {

            background: #253c7c;

            border-color: #253c7c;

            color: #ffffff;
        }


        .backup-pdf-btn {

            padding: 6px 11px;

            border-radius: 6px;

            font-size: 12px;
        }


        /* ===============================
                                                                                                           PAGINATION
                                                                                                        ================================ */

        .backup-pagination {

            margin-top: 20px;
        }


        @media(max-width: 767px) {

            .patient-report-header {

                padding: 12px;
            }

            .patient-summary-wrapper {

                padding: 12px;
            }

            .backup-summary {

                margin-bottom: 5px;
            }

            .patient-actions {

                margin-top: 10px;
            }

        }
    </style>



    <div class="main-content">

        <div class="page-content patient-backup-page">

            <div class="container-fluid">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                    <div>
                        <h4 class="backup-main-title">
                            Patient Backup Report
                        </h4>
                        <p class="backup-main-subtitle">
                            Complete Patient Treatment
                            & Payment Records
                        </p>
                    </div>
                </div>
                <div class="backup-filter-box">

                    <form method="GET" action="{{ route('patient.backup') }}">

                        <div class="row g-2 align-items-end">
                            <div class="col-lg-6 col-md-6">
                                <label class="form-label">
                                    Search Patient
                                </label>
                                <input type="text" name="search" class="form-control" value="{{ $search }}"
                                    placeholder="Name, Case No, Mobile, Email...">
                            </div>
                            <div class="col-lg-3 col-md-3">
                                <label class="form-label">
                                    Status
                                </label>
                                <select name="status" class="form-select">
                                    <option value="">
                                        All Patients
                                    </option>
                                    <option value="active" {{ $status == 'active' ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>
                                </select>
                            </div>



                            <div class="col-lg-3 col-md-3">

                                <button type="submit" class="btn btn-primary">

                                    <i class="ri-search-line"></i>

                                    Search

                                </button>


                                <a href="{{ route('patient.backup') }}" class="btn btn-light">

                                    Reset

                                </a>

                            </div>


                        </div>

                    </form>

                </div>

                {{-- =====================================
                 SAFE DATE FUNCTION
            ====================================== --}}

                @php

                    $displayDate = function ($date) {
                        if (empty($date) || $date == '0000-00-00' || $date == '0000-00-00 00:00:00') {
                            return '-';
                        }

                        try {
                            return \Carbon\Carbon::parse($date)->format('d-m-Y');
                        } catch (\Throwable $e) {
                            return '-';
                        }
                    };

                @endphp



                {{-- =====================================
                 PATIENTS
            ====================================== --}}

                @forelse($patients as $patient)


                    @php

                        $fullName = trim(
                            ($patient->name ?? '') .
                                ' ' .
                                ($patient->middle_name ?? '') .
                                ' ' .
                                ($patient->last_name ?? ''),
                        );
                    @endphp



                    <div class="patient-report-card">


                        {{-- ==============================
                         CARD HEADER
                    =============================== --}}

                        <div class="patient-report-header">

                            <div class="row align-items-center">


                                <div class="col-lg-7 col-md-7">

                                    <h5 class="patient-report-name">

                                        {{ $fullName ?: 'Unnamed Patient' }}

                                    </h5>


                                    <div class="patient-report-meta">

                                        <strong>
                                            Case No:
                                        </strong>

                                        {{ $patient->case_no ?? '-' }}


                                        &nbsp; | &nbsp;


                                        <strong>
                                            Mobile:
                                        </strong>

                                        {{ $patient->mobile1 ?? '-' }}

                                    </div>

                                </div>



                                <div class="col-lg-5 col-md-5 text-md-end patient-actions">


                                    {{-- STATUS --}}

                                    @if ($patient->is_completed == 1)
                                        <span class="backup-status backup-status-completed">

                                            ✓ Completed

                                        </span>
                                    @else
                                        <span class="backup-status backup-status-active">

                                            Active

                                        </span>
                                    @endif



                                    {{-- =================================
                                     SINGLE PATIENT PDF
                                ================================== --}}

                                    <a href="{{ route('patient.backup.single.pdf', $patient->id) }}" target="_blank"
                                        class="btn btn-danger backup-pdf-btn ms-2" title="Patient PDF">

                                        <i class="ri-file-pdf-2-line"></i>

                                        PDF

                                    </a>



                                    {{-- DETAILS --}}

                                    <button type="button" class="backup-details-btn ms-2" data-bs-toggle="collapse"
                                        data-bs-target="#patientDetail{{ $patient->id }}">

                                        <i class="ri-eye-line"></i>

                                        Details

                                    </button>


                                </div>


                            </div>

                        </div>



                        {{-- ==============================
                         SUMMARY
                    =============================== --}}

                        <div class="patient-summary-wrapper">

                            <div class="row g-2">


                                <div class="col-lg-3 col-md-6">

                                    <div class="backup-summary summary-treatment">

                                        <small>

                                            Total Treatment

                                        </small>

                                        <strong>

                                            ₹{{ number_format($patient->total_treatment_amount, 2) }}

                                        </strong>

                                    </div>

                                </div>



                                <div class="col-lg-3 col-md-6">

                                    <div class="backup-summary summary-paid">

                                        <small>

                                            Total Paid

                                        </small>

                                        <strong>

                                            ₹{{ number_format($patient->total_paid_amount, 2) }}

                                        </strong>

                                    </div>

                                </div>



                                <div class="col-lg-3 col-md-6">

                                    <div class="backup-summary summary-due">

                                        <small>

                                            Total Due

                                        </small>

                                        <strong>

                                            ₹{{ number_format($patient->total_due_amount, 2) }}

                                        </strong>

                                    </div>

                                </div>



                                <div class="col-lg-3 col-md-6">

                                    <div class="backup-summary summary-record">

                                        <small>

                                            Treatments / Payments

                                        </small>

                                        <strong>

                                            {{ $patient->treatment_count }}

                                            /

                                            {{ $patient->payment_count }}

                                        </strong>

                                    </div>

                                </div>


                            </div>

                        </div>



                        {{-- ==============================
                         COLLAPSE DETAILS
                    =============================== --}}

                        <div class="collapse" id="patientDetail{{ $patient->id }}">

                            <div class="card-body p-3">
                                {{-- <h6 class="backup-section-title">
                                    Patient Details
                                </h6> --}}
                                {{-- <div class="patient-info-box mb-4">

                                    <div class="row">


                                        <div class="col-lg-4 col-md-6 patient-info-item">

                                            <strong>Name:</strong>

                                            {{ $fullName ?: '-' }}

                                        </div>


                                        <div class="col-lg-4 col-md-6 patient-info-item">

                                            <strong>Mobile 1:</strong>

                                            {{ $patient->mobile1 ?? '-' }}

                                        </div>


                                        <div class="col-lg-4 col-md-6 patient-info-item">

                                            <strong>Mobile 2:</strong>

                                            {{ $patient->mobile2 ?? '-' }}

                                        </div>


                                        <div class="col-lg-4 col-md-6 patient-info-item">

                                            <strong>Email:</strong>

                                            {{ $patient->email ?? '-' }}

                                        </div>


                                        <div class="col-lg-4 col-md-6 patient-info-item">

                                            <strong>DOB:</strong>

                                            {{ $displayDate($patient->dob) }}

                                        </div>


                                        <div class="col-lg-4 col-md-6 patient-info-item">

                                            <strong>Age:</strong>

                                            {{ $patient->Age ?? '-' }}

                                        </div>


                                        <div class="col-lg-4 col-md-6 patient-info-item">

                                            <strong>Gender:</strong>

                                            {{ $patient->gender ?? '-' }}

                                        </div>


                                        <div class="col-lg-4 col-md-6 patient-info-item">

                                            <strong>Blood Group:</strong>

                                            {{ $patient->blood_group ?? '-' }}

                                        </div>


                                        <div class="col-lg-4 col-md-6 patient-info-item">

                                            <strong>Weight:</strong>

                                            {{ $patient->weight ?? '-' }}

                                        </div>


                                        <div class="col-lg-4 col-md-6 patient-info-item">

                                            <strong>Occupation:</strong>

                                            {{ $patient->Occupation ?? '-' }}

                                        </div>


                                        <div class="col-lg-4 col-md-6 patient-info-item">

                                            <strong>Company:</strong>

                                            {{ $patient->company_name ?? '-' }}

                                        </div>


                                        <div class="col-lg-4 col-md-6 patient-info-item">

                                            <strong>Relative Name:</strong>

                                            {{ $patient->relative_name ?? '-' }}

                                        </div>


                                        <div class="col-lg-4 col-md-6 patient-info-item">

                                            <strong>Emergency Contact:</strong>

                                            {{ $patient->emergency_contact_name ?? '-' }}

                                        </div>


                                        <div class="col-lg-4 col-md-6 patient-info-item">

                                            <strong>Reference By:</strong>

                                            {{ $patient->reference_by ?? '-' }}

                                        </div>


                                        <div class="col-lg-4 col-md-6 patient-info-item">

                                            <strong>Referred By:</strong>

                                            {{ $patient->referred_by ?? '-' }}

                                        </div>


                                        <div class="col-lg-4 col-md-6 patient-info-item">

                                            <strong>Referred Name:</strong>

                                            {{ $patient->referred_name ?? '-' }}

                                        </div>


                                        <div class="col-lg-4 col-md-6 patient-info-item">

                                            <strong>Pincode:</strong>

                                            {{ $patient->pincode ?? '-' }}

                                        </div>


                                        <div class="col-lg-8 col-md-12 patient-info-item">

                                            <strong>Address:</strong>

                                            {{ $patient->address ?? '-' }}

                                        </div>


                                        <div class="col-lg-6 patient-info-item">

                                            <strong>Medical History:</strong>

                                            {{ $patient->medical_history ?? '-' }}

                                        </div>


                                        <div class="col-lg-6 patient-info-item">

                                            <strong>Medications:</strong>

                                            {{ $patient->medications ?? '-' }}

                                        </div>


                                        <div class="col-lg-6 patient-info-item">

                                            <strong>Previous Surgery:</strong>

                                            {{ $patient->previous_surgery ?? '-' }}

                                        </div>


                                        <div class="col-lg-6 patient-info-item">

                                            <strong>Allergy:</strong>

                                            {{ $patient->allergy ?? '-' }}

                                        </div>


                                        <div class="col-lg-6 patient-info-item">

                                            <strong>Habit:</strong>

                                            {{ $patient->habit ?? '-' }}

                                        </div>


                                        <div class="col-lg-6 patient-info-item">

                                            <strong>Other Disease:</strong>

                                            {{ $patient->other_disease_comments ?? '-' }}

                                        </div>


                                        <div class="col-lg-6 patient-info-item">

                                            <strong>Preferred Contact:</strong>

                                            {{ $patient->preferred_time_to_contact_you ?? '-' }}

                                        </div>


                                        <div class="col-lg-6 patient-info-item">

                                            <strong>Reminder:</strong>

                                            {{ $patient->reminder ?? '-' }}

                                        </div>


                                    </div>

                                </div> --}}

                                <h6 class="backup-section-title">

                                    Treatment History

                                </h6>

                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered backup-table">
                                        <thead>
                                            <tr>

                                                <th>#</th>

                                                <th>Date</th>

                                                <th>Treatment</th>

                                                <th>Sub Treatment</th>

                                                <th>Tooth No.</th>

                                                <th>Comments</th>

                                                <th>Amount</th>

                                                <th>Discount</th>

                                                <th>Net Amount</th>

                                                <th>Status</th>

                                            </tr>

                                        </thead>



                                        <tbody>


                                            @forelse($patient->notes
                                                                                                                                            as $index => $note)
                                                <tr>

                                                    <td>

                                                        {{ $index + 1 }}

                                                    </td>


                                                    <td>

                                                        {{ $displayDate($note->date) }}

                                                    </td>


                                                    <td>

                                                        {{ $note->treatment_detail->treatment_name ?? '-' }}

                                                    </td>


                                                    <td>

                                                        {{ $note->subTreatment->name ?? '-' }}

                                                    </td>


                                                    <td>

                                                        {{ $note->tooth_no ?? '-' }}

                                                    </td>


                                                    <td>

                                                        {{ $note->comments ?? '-' }}

                                                    </td>


                                                    <td class="text-end">

                                                        ₹{{ number_format((float) ($note->amount ?? 0), 2) }}

                                                    </td>


                                                    <td class="text-end">

                                                        ₹{{ number_format((float) ($note->discount ?? 0), 2) }}

                                                    </td>


                                                    <td class="text-end">

                                                        <strong>

                                                            ₹{{ number_format((float) ($note->Net_amount ?? 0), 2) }}

                                                        </strong>

                                                    </td>


                                                    <td>

                                                        @if ($note->is_completed == 1)
                                                            <span class="badge bg-success">

                                                                Completed

                                                            </span>
                                                        @else
                                                            <span class="badge bg-warning">

                                                                Pending

                                                            </span>
                                                        @endif

                                                    </td>

                                                </tr>


                                            @empty


                                                <tr>

                                                    <td colspan="10" class="text-center empty-record">

                                                        No treatment found.

                                                    </td>

                                                </tr>
                                            @endforelse


                                        </tbody>



                                        <tfoot>

                                            <tr>

                                                <th colspan="8" class="text-end">

                                                    Total Treatment

                                                </th>


                                                <th colspan="2">

                                                    ₹{{ number_format($patient->total_treatment_amount, 2) }}

                                                </th>

                                            </tr>

                                        </tfoot>


                                    </table>


                                </div>



                                {{-- ==========================
                                 PAYMENTS
                            =========================== --}}

                                <h6 class="backup-section-title">

                                    Payment History

                                </h6>

                                <div class="table-responsive">

                                    <table class="table table-bordered backup-table">


                                        <thead>

                                            <tr>

                                                <th>#</th>

                                                <th>Payment Date</th>

                                                <th>Amount</th>

                                                <th>Mode</th>

                                                <th>Comments</th>

                                            </tr>

                                        </thead>
                                        <tbody>
                                            @forelse($patient->payments as $index => $payment)
                                                <tr>
                                                    <td>
                                                        {{ $index + 1 }}
                                                    </td>
                                                    <td>
                                                        {{ $displayDate($payment->payment_date) }}
                                                    </td>
                                                    <td class="text-end">
                                                        <strong>
                                                            ₹{{ number_format((float) ($payment->amount ?? 0), 2) }}
                                                        </strong>
                                                    </td>
                                                    <td>
                                                        {{ $payment->mode ?? '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $payment->comments ?? '-' }}
                                                    </td>
                                                </tr>

                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center empty-record">
                                                        No payment found.
                                                    </td>

                                                </tr>
                                            @endforelse

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2" class="text-end">
                                                    Total Paid
                                                </th>
                                                <th>

                                                    ₹{{ number_format($patient->total_paid_amount, 2) }}

                                                </th>

                                                <th colspan="2">

                                                    Due:

                                                    ₹{{ number_format($patient->total_due_amount, 2) }}

                                                </th>

                                            </tr>

                                        </tfoot>


                                    </table>


                                </div>


                            </div>

                        </div>


                    </div>


                @empty


                    <div class="alert alert-warning">

                        No patient records found.

                    </div>
                @endforelse



                {{-- =====================================
                 PAGINATION
            ====================================== --}}

                @if ($patients->hasPages())
                    <div class="backup-pagination">

                        <div class="d-flex flex-wrap justify-content-between align-items-center">


                            <div class="small text-muted">

                                Showing

                                <strong>
                                    {{ $patients->firstItem() }}
                                </strong>

                                to

                                <strong>
                                    {{ $patients->lastItem() }}
                                </strong>

                                of

                                <strong>
                                    {{ $patients->total() }}
                                </strong>

                                patients

                            </div>


                            <div>

                                {{ $patients->onEachSide(1)->links() }}

                            </div>


                        </div>

                    </div>
                @endif


            </div>

        </div>

    </div>

@endsection
