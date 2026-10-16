<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <title>
        Patient Backup Report
    </title>


    <style>
        @page {

            margin:
                25px 25px 30px 25px;
        }


        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            font-family:
                DejaVu Sans,
                sans-serif;

            font-size: 9px;

            color: #252b36;

            line-height: 1.45;
        }


        /* =============================
           MAIN HEADER
        ============================== */

        .main-header {

            background: #253c7c;

            color: #ffffff;

            padding: 14px;

            margin-bottom: 12px;
        }


        .main-title {

            font-size: 16px;

            font-weight: bold;

            margin-bottom: 4px;
        }


        .main-subtitle {

            font-size: 8px;
        }


        /* =============================
           PATIENT HEADER
        ============================== */

        .patient-heading {

            width: 100%;

            border-collapse: collapse;

            margin-bottom: 12px;
        }


        .patient-heading td {

            padding: 9px;

            border:
                1px solid #e4e7ec;

            vertical-align: middle;
        }


        .patient-name {

            color: #253c7c;

            font-size: 14px;

            font-weight: bold;

            margin-bottom: 4px;
        }


        .status-cell {

            text-align: right;

            font-weight: bold;
        }


        /* =============================
           SECTION
        ============================== */

        .section-title {

            background: #eef2ff;

            color: #253c7c;

            border-left:
                4px solid #253c7c;

            font-size: 11px;

            font-weight: bold;

            padding: 6px 8px;

            margin-top: 13px;

            margin-bottom: 6px;
        }


        /* =============================
           DETAILS
        ============================== */

        .details-table {

            width: 100%;

            border-collapse: collapse;
        }


        .details-table td {

            width: 33.33%;

            padding: 5px;

            border:
                1px solid #e4e7ec;

            vertical-align: top;
        }


        .label {

            font-weight: bold;

            color: #111827;
        }


        /* =============================
           RECORD TABLE
        ============================== */

        .record-table {

            width: 100%;

            border-collapse: collapse;

            margin-bottom: 10px;
        }


        .record-table th {

            background: #253c7c;

            color: #ffffff;

            border:
                1px solid #40558d;

            padding: 5px 3px;

            font-size: 7px;

            font-weight: bold;
        }


        .record-table td {

            border:
                1px solid #dfe3e8;

            padding: 5px 3px;

            font-size: 7.5px;

            vertical-align: top;
        }


        .record-table tfoot td {

            background: #f5f7fa;

            font-weight: bold;

            padding: 6px;
        }


        .text-right {

            text-align: right;
        }


        .text-center {

            text-align: center;
        }


        /* =============================
           FINANCIAL SUMMARY
        ============================== */

        .summary-table {

            width: 100%;

            border-collapse: collapse;

            margin-top: 12px;
        }


        .summary-table td {

            width: 33.33%;

            border:
                1px solid #dfe3e8;

            padding: 10px;
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


        .summary-label {

            display: block;

            font-size: 8px;

            font-weight: bold;

            margin-bottom: 3px;
        }


        .summary-amount {

            font-size: 13px;

            font-weight: bold;
        }


        .no-record {

            text-align: center;

            color: #888888;

            font-style: italic;
        }


        /* =============================
           FOOTER
        ============================== */

        .footer {

            margin-top: 18px;

            border-top:
                1px solid #dddddd;

            padding-top: 7px;

            font-size: 7px;

            color: #777777;

            text-align: center;
        }
    </style>

</head>



<body>


    @php

        /*
    |--------------------------------------------------------------------------
    | NAME
    |--------------------------------------------------------------------------
    */

        $fullName = trim(
            ($patient->name ?? '') . ' ' . ($patient->middle_name ?? '') . ' ' . ($patient->last_name ?? ''),
        );

        /*
    |--------------------------------------------------------------------------
    | DATE
    |--------------------------------------------------------------------------
    */

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



    {{-- ==========================================
     HEADER
=========================================== --}}

    <div class="main-header">


        <div class="main-title">

            Patient Backup Report

        </div>


        <div class="main-subtitle">

            Generated:

            {{ now()->format('d-m-Y h:i A') }}

        </div>


    </div>



    {{-- ==========================================
     PATIENT HEADER
=========================================== --}}

    <table class="patient-heading">

        <tr>


            <td style="width:75%;">


                <div class="patient-name">

                    {{ $fullName ?: 'Unnamed Patient' }}

                </div>


                Case No:

                <strong>

                    {{ $patient->case_no ?? '-' }}

                </strong>


                &nbsp; | &nbsp;


                Mobile:

                <strong>

                    {{ $patient->mobile1 ?? '-' }}

                </strong>


            </td>



            <td class="status-cell">


                Status:


                @if ($patient->is_completed == 1)
                    Completed
                @else
                    Active
                @endif


            </td>


        </tr>

    </table>
    {{-- ==========================================
     TREATMENT
=========================================== --}}

    <div class="section-title">

        Treatment History

    </div>


    <table class="record-table">


        <thead>

            <tr>

                <th>#</th>

                <th>Date</th>

                <th>Treatment</th>

                <th>Sub Treatment</th>

                <th>Tooth</th>

                <th>Comments</th>

                <th>Amount</th>

                <th>Discount</th>

                <th>Net</th>

                <th>Status</th>

            </tr>


        </thead>



        <tbody>


            @forelse($patient->notes
        as $index => $note)
                <tr>


                    <td class="text-center">

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


                    <td class="text-right">

                        {{ number_format((float) ($note->amount ?? 0), 2) }}

                    </td>


                    <td class="text-right">

                        {{ number_format((float) ($note->discount ?? 0), 2) }}

                    </td>


                    <td class="text-right">

                        {{ number_format((float) ($note->Net_amount ?? 0), 2) }}

                    </td>


                    <td>

                        {{ $note->is_completed == 1 ? 'Completed' : 'Pending' }}

                    </td>


                </tr>


            @empty


                <tr>


                    <td colspan="10" class="no-record">

                        No treatment records found.

                    </td>


                </tr>
            @endforelse


        </tbody>



        <tfoot>


            <tr>


                <td colspan="8" class="text-right">

                    Total Treatment

                </td>


                <td colspan="2" class="text-right">

                    Rs.

                    {{ number_format($patient->total_treatment_amount, 2) }}

                </td>


            </tr>


        </tfoot>


    </table>



    {{-- ==========================================
     PAYMENT
=========================================== --}}

    <div class="section-title">

        Payment History

    </div>


    <table class="record-table">


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


            @forelse($patient->payments
        as $index => $payment)
                <tr>


                    <td class="text-center">

                        {{ $index + 1 }}

                    </td>


                    <td>

                        {{ $displayDate($payment->payment_date) }}

                    </td>


                    <td class="text-right">

                        Rs.

                        {{ number_format((float) ($payment->amount ?? 0), 2) }}

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


                    <td colspan="5" class="no-record">

                        No payment records found.

                    </td>


                </tr>
            @endforelse


        </tbody>


    </table>



    {{-- ==========================================
     TOTAL SUMMARY
=========================================== --}}

    <div class="section-title">

        Financial Summary

    </div>


    <table class="summary-table">


        <tr>


            <td class="summary-treatment">


                <span class="summary-label">

                    TOTAL TREATMENT

                </span>


                <span class="summary-amount">

                    Rs.

                    {{ number_format($patient->total_treatment_amount, 2) }}

                </span>


            </td>



            <td class="summary-paid">


                <span class="summary-label">

                    TOTAL PAID

                </span>


                <span class="summary-amount">

                    Rs.

                    {{ number_format($patient->total_paid_amount, 2) }}

                </span>


            </td>



            <td class="summary-due">


                <span class="summary-label">

                    TOTAL DUE

                </span>


                <span class="summary-amount">

                    Rs.

                    {{ number_format($patient->total_due_amount, 2) }}

                </span>


            </td>


        </tr>


    </table>



    {{-- ==========================================
     FOOTER
=========================================== --}}

    <div class="footer">


        Patient Backup Report

        &nbsp; | &nbsp;


        Case No:

        {{ $patient->case_no ?? '-' }}


        &nbsp; | &nbsp;


        Generated:

        {{ now()->format('d-m-Y h:i A') }}


    </div>


</body>

</html>
