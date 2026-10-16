<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>Prescription</title>


    <style>

        /* =========================================================
           PAGE
        ========================================================= */

        @page {
            margin: 250px 35px 120px 35px;
        }


        * {
            box-sizing: border-box;
        }


        body {
            font-family: DejaVu Sans, sans-serif;

            margin: 0;
            padding: 0;

            color: #000;

            font-size: 12px;
        }



        /* =========================================================
           FIXED HEADER
        ========================================================= */

        .page-header {
            position: fixed;
            top: -225px;
            left: 0;
            right: 0;
            height: 215px;
        }


        .header-logo-box {
            text-align: center;

            margin: 0;
            padding: 0;
        }


        .header-logo-box img {
            width: 350px;
            height: auto;
        }


        .header-line {
            border: 0;
            border-top: 1px solid #49617d;

            margin: 5px 0 8px;
        }


        .doctor-table {
            width: 100%;

            border-collapse: collapse;

            table-layout: fixed;
        }


        .doctor-table td {
            border: 0;

            padding: 0;

            vertical-align: top;

            font-size: 10px;

            line-height: 1.4;

            color: #354158;
        }


        .doctor-left {
            width: 48%;
        }


        .doctor-middle {
            width: 4%;
        }

        .doctor-right {
            width: 48%;
            text-align: left !important;
            padding-left: 100px !important;
            vertical-align: top;
        }


        .doctor-name {
            font-weight: bold;

            font-size: 10.5px;
        }


        .header-date {
            margin-top: 7px;
            text-align: left !important;
        }



        /* =========================================================
           FIXED FOOTER
        ========================================================= */

        .page-footer {
            position: fixed;

            bottom: -100px;
            left: 0;
            right: 0;

            height: 120px;

            text-align: center;

            color: #45546b;
        }


        .footer-address {
            font-size: 9px;

            line-height: 1.4;
        }


        .footer-line {
            border: 0;

            border-top: 1px solid #49617d;

            margin: 4px 0;
        }


        .footer-contact {
            font-size: 9px;

            line-height: 1.4;
        }


        .footer-timing {
            font-size: 9px;

            line-height: 1.4;
        }


        .footer-strip {
            margin-top: 4px;
            margin-bottom:40px !important;
            padding: 10px 0 !important ;

            background: #022f65;

            color: #ffffff;      

            font-size: 10px;

            font-weight: bold;

            text-align: center;
        }



        /* =========================================================
           PATIENT INFORMATION
        ========================================================= */

        .patient-info {
            width: 100%;

            border-collapse: collapse;

            margin: 0 0 8px 0;

            font-size: 12px;

            page-break-inside: avoid !important;
        }


        .patient-info tr {
            page-break-inside: avoid !important;
        }


        .patient-info td {
            padding: 4px 5px;

            vertical-align: top;

            page-break-inside: avoid !important;
        }


        .patient-name {
            font-size: 12px;
            margin-top:1px;
            font-weight: bold;

            line-height: 16px;
        }


        .patient-details {
            font-size: 12px;

            line-height: 16px;
        }



        /* =========================================================
           COMPLETE PRESCRIPTION OUTER BOX
        ========================================================= */

        .prescription-main-box {
            width: 100%;

            border: 1px solid #777;

            margin: 0;

            padding: 0;

            page-break-before: auto;

            page-break-after: auto;

            page-break-inside: auto;
        }



        /* =========================================================
           MEDICINE TABLE
        ========================================================= */

        .prescription-table {
            width: 100%;

            border-collapse: collapse;

            margin: 0;

            padding: 0;

            font-size: 12px;
        }


        .prescription-table th,
        .prescription-table td {
            border: 1px solid #777;

            padding: 6px 7px;

            vertical-align: top;
        }


        /*
         * Outer left/right border prescription-main-box se aayega.
         */

        .prescription-table th:first-child,
        .prescription-table td:first-child {
            border-left: 0;
        }


        .prescription-table th:last-child,
        .prescription-table td:last-child {
            border-right: 0;
        }


        .prescription-table thead tr:first-child th {
            border-top: 0;
        }



        /* =========================================================
           TABLE HEADINGS
        ========================================================= */

        .prescription-table th {
            background: #022f65;
            color:#fff;
            font-weight: bold;

            text-align: center;
        }


        .prescription-table .medicine-col {
            width: 55%;
            
        }


        .prescription-table .frequency-col {
            width: 18%;
            

            text-align: center;
        }


        .prescription-table .duration-col {
            width: 14%;
            

            text-align: center;
        }


        .prescription-table .qty-col {
            width: 13%;
            

            text-align: center;
        }



        /* =========================================================
           MEDICINE CONTENT
        ========================================================= */

        .medicine-name {
            font-size: 12px;

            font-weight: bold;

            text-transform: uppercase;

            margin-bottom: 3px;
        }


        .medicine-comment {
            font-size: 10px;

            color: #444;

            margin-top: 2px;

            line-height: 14px;
        }


        .medicine-content {
            font-size: 10px;

            color: #666;

            margin-top: 2px;

            line-height: 14px;
        }



        /* =========================================================
           BLANK SPACE

           Medicines ke baad reference design jaisa blank area.
           Height yahan se control kar sakte ho.
        ========================================================= */

        .prescription-blank-space {
            width: 100%;

            /*height: 200px;*/

            margin: 0;

            padding: 0;
        }



        /* =========================================================
           BOTTOM TABLE
        ========================================================= */

        .prescription-bottom {
            width: 100%;

            border-collapse: collapse;

            border-spacing: 0;

            margin: 0;

            padding: 0;

            font-size: 11px;

            page-break-inside: avoid;
        }


        .prescription-bottom td {
            border: 1px solid #777;

            padding: 6px 8px;

            vertical-align: middle;
        }


        .prescription-bottom td:first-child {
            border-left: 0;
        }


        .prescription-bottom td:last-child {
            border-right: 0;
        }



        /* =========================================================
           SPECIAL INSTRUCTIONS
        ========================================================= */

        .section-label {
            width: 28%;

            height: 36px;

            background: #f2f2f2;

            text-align: center;

            vertical-align: middle !important;

            font-size: 11px;

            font-weight: normal;
        }


        .section-value {
            width: 72%;

            height: 36px;

            text-align: left;

            vertical-align: middle !important;

            font-size: 11px;
        }



        /* =========================================================
           DISPENSING WRAPPER
        ========================================================= */

        .dispensing-wrapper {
            padding: 0 !important;

            border-left: 0 !important;

            border-right: 0 !important;

            border-bottom: 0 !important;
        }



        /* =========================================================
           DISPENSING INNER TABLE
        ========================================================= */

        .dispensing-inner {
            width: 100%;

            border-collapse: collapse;

            border-spacing: 0;

            margin: 0;

            padding: 0;
        }


        .dispensing-inner td {
            border: 1px solid #777;
        }


        .dispensing-inner td:first-child {
            border-left: 0;
        }


        .dispensing-inner td:last-child {
            border-right: 0;
        }


        .dispensing-inner tr:last-child td {
            border-bottom: 0;
        }



        /* =========================================================
           DISPENSING DETAILS
        ========================================================= */

        .dispensing-box {
            width: 70%;

            height: 55px;

            vertical-align: top !important;

            padding: 7px 8px !important;

            font-size: 10px;

            line-height: 14px;
        }



        /* =========================================================
           SIGN & SEAL
        ========================================================= */

        .sign-box {
            width: 30%;

            height: 55px;

            text-align: center;

            vertical-align: bottom !important;

            padding: 7px 8px !important;

            font-size: 10px;
        }



        /* =========================================================
           DO NOT REFILL
        ========================================================= */

        .refill-warning {
            background: #000;

            color: #fff;

            text-align: center;

            vertical-align: middle !important;

            font-size: 10px;

            font-weight: normal;

            height: 20px;

            padding: 3px 5px !important;
        }


        .refill-empty {
            background: #f2f2f2;

            height: 20px;

            padding: 3px !important;
        }
        
        /* =========================================================
   SIGNATURE SPACE AFTER PRESCRIPTION TABLE
========================================================= */

.prescription-signature-area {
    width: 100%;
    height: 150px;

    border-collapse: collapse;
    border-spacing: 0;

    margin: 0;
    padding: 0;
}

.prescription-signature-area td {
    border: 0;
    padding: 0;
}


/* Left side blank */
.signature-blank-space {
    width: 70%;
    height: 200px;
}


/* Right side signature */
.signature-section {
    width: 30%;
    height: 200px;

    vertical-align: bottom !important;
    text-align: center;

    padding: 0 20px 18px 20px !important;
}


/* Signature horizontal line */
.signature-bottom-line {
    width: 100%;

    border-top: 1px solid #000;

    margin: 0 0 5px 0;
}


/* Sign & Seal text */
.signature-text {
    font-size: 10px;

    text-align: center;

    color: #000;
}

    </style>

</head>



@php

    use Carbon\Carbon;


    /*
    |--------------------------------------------------------------------------
    | Patient Data
    |--------------------------------------------------------------------------
    */

    $dob = $prescription->patient->dob ?? null;


    $age = $dob
        ? Carbon::parse($dob)->age
        : '';


    $gender =
        $prescription->patient->gender
        ?? '';


    $genderInitial =
        $gender
        ? strtoupper(substr($gender, 0, 1))
        : '';



    /*
    |--------------------------------------------------------------------------
    | D&D Logo
    |--------------------------------------------------------------------------
    */

    $logoPath =
        '/home1/getdemo/public_html/dental_clinic/assets/images/logo3.png';


    $logoData = '';


    if (file_exists($logoPath)) {

        $logoData =
            'data:image/png;base64,' .
            base64_encode(
                file_get_contents($logoPath)
            );

    }

@endphp



<body>


    <!-- =========================================================
         HEADER
    ========================================================== -->

    <div class="page-header">


        <!-- Logo -->

        <div class="header-logo-box">

            @if ($logoData)

                <img
                    src="{{ $logoData }}"
                    alt="D&D Dental Clinic"
                >

            @endif

        </div>


        <hr class="header-line">



        <!-- Doctor Details -->

        <table class="doctor-table">

            <tbody>

                <tr>
                    <!-- LEFT DOCTOR -->
                    <td class="doctor-left">
                        <span class="doctor-name">
                            Dr. Dwij Kothari
                        </span>
                        (M.D.S.
                        <br>
                        Prosthodontist &amp; Implantologist
                        <br>
                        Smile Design (Veneer) specialist
                        <br>
                        Ex-Reader (Goenka dental college)
                        <br>
                        Regn No. : A-6214
                    </td>
                    <!-- SPACE -->
                    <td class="doctor-middle">
                        &nbsp;
                    </td>

                    <!-- RIGHT DOCTOR -->

                    <td class="doctor-right">
                        <span class="doctor-name">
                            Dr. Deepa Vaid
                        </span>
                        (M.D.S.)
                        <br>
                        Micro-Endodontist (RCT specialist)
                        <br>
                        Biomimetic Restorative dentist
                        <br>
                        Reader (C.D.S.R.C. Bopal)
                        <br>
                        Regn No. : A-6215
                        <div class="header-date">
                            Date :
                            <strong>
                                {{ $prescription->created_at
                                    ? $prescription->created_at->format('d-m-Y')
                                    : '' }}
                            </strong>
                        </div>

                    </td>

                </tr>

            </tbody>

        </table>


    </div>



    <!-- =========================================================
         FOOTER
    ========================================================== -->

    <div class="page-footer">


        <!-- ADDRESS -->

        <div class="footer-address">

            1<sup>st</sup> floor,
            Tavish Avenue,
            Opp. Aaryan Eminent,
            Near Ganesh Meridian,

            <br>


            Kargil Cross Road,
            Off S. G. Highway,
            Chanakyapuri Road,
            Ahmedabad - 61

            <br>


            E-mail : dnddentistry@gmail.com


            &nbsp; • &nbsp;


            Web : www.dnddentistry.com

        </div>



        <hr class="footer-line">



        <!-- CONTACT -->

        <div class="footer-contact">

            <strong>

                Contact :

            </strong>


            97246 67455

            (9am to 9pm)


            &nbsp; / &nbsp;


            <strong>

                Emergency contact only :

            </strong>


            98258 26746

        </div>



        <!-- TIMING -->

        <div class="footer-timing">

            <strong>

                Timing :

            </strong>


            Morning :

            10-00 am to 1-00 pm


            &nbsp; • &nbsp;


            Evening :

            4-00 pm to 8-00 pm

        </div>



        <!-- BLUE STRIP -->

        <div class="footer-strip">

            We treat with Ethics,
            Compassion,
            Empathy &amp; Transparency

        </div>


    </div>



    <!-- =========================================================
         PATIENT INFORMATION
    ========================================================== -->

    <table class="patient-info">

        <tbody>

            <tr>

                <td>


                    <!-- PATIENT NAME -->

                    <div class="patient-name">

                        {{ $prescription->patient->name ?? '' }}

                        {{ $prescription->patient->middle_name ?? '' }}

                        {{ $prescription->patient->last_name ?? '' }}

                    </div>



                    <!-- AGE / GENDER -->

                    <div class="patient-details">


                        @if ($age)

                            {{ $age }} yrs

                        @endif



                        @if ($age && $genderInitial)

                            /

                        @endif



                        @if ($genderInitial)

                            {{ $genderInitial }}

                        @endif


                    </div>


                </td>

            </tr>

        </tbody>

    </table>




    <!-- =========================================================
         COMPLETE PRESCRIPTION BOX
    ========================================================== -->

    <div class="prescription-main-box">



        <!-- =====================================================
             MEDICINE TABLE
        ====================================================== -->

        <table class="prescription-table">


            <thead>

                <tr>


                    <!-- RX / MEDICINE -->

                    <th
                        class="medicine-col"
                        style="
                            text-align: left;
                            font-size: 14px;
                            font-weight: bold;
                        "
                    >

                        Rx

                    </th>



                    <!-- FREQUENCY -->

                    <th class="frequency-col">

                        Frequency

                        <br>

                        (MN-AF-EN-NT)

                    </th>



                    <!-- DURATION -->

                    <th class="duration-col">

                        Duration

                    </th>



                    <!-- QUANTITY -->

                    <th class="qty-col">

                        Qty

                    </th>


                </tr>

            </thead>




            <tbody>


                @foreach ($prescription->prescriptionDetails as $index => $detail)


                    <tr>
                        <!-- =========================================
                             MEDICINE
                        ========================================== -->

                        <td class="medicine-col">


                            <div class="medicine-name">

                                {{ $index + 1 }}.

                                {{ $detail->medicine->medicine_name ?? '' }}

                            </div>



                            <!-- Medicine Comment -->

                            @if (!empty($detail->medicine->comment))

                                <div class="medicine-comment">

                                    {{ $detail->medicine->comment }}

                                </div>

                            @endif




                            <!-- Medicine Content -->

                            @if (!empty($detail->medicine->content))

                                <div class="medicine-content">

                                    {{ $detail->medicine->content }}

                                </div>

                            @endif


                        </td>



                        <!-- =========================================
                             FREQUENCY
                        ========================================== -->

                        <td class="frequency-col">


                            {{ $detail->dosage->dosage ?? '' }}



                            @if (!empty($detail->medicine->dosage_comment))


                                <hr
                                    style="
                                        margin: 2px 0;
                                        border: 0;
                                        border-top: 1px solid #000;
                                    "
                                >


                                {{ $detail->medicine->dosage_comment }}


                            @endif


                        </td>



                        <!-- =========================================
                             DURATION
                        ========================================== -->

                        <td class="duration-col">


                            @if (!empty($detail->days))

                                {{ $detail->days }} days

                            @endif


                        </td>



                        <!-- =========================================
                             QUANTITY
                        ========================================== -->

                        <td class="qty-col">


                            @if (!empty($detail->medicine_qty))


                                {{ $detail->medicine_qty }}


                                <br>


                                tablet(s)


                            @endif


                        </td>


                    </tr>


                @endforeach


            </tbody>


        </table>
        
        
       
    </div>
    @if(isset($prescription->strSpecialInstruction) && $prescription->strSpecialInstruction != "")
            <div> <i><strong>*Special Instruction: </strong></i> {{ $prescription->strSpecialInstruction }} </div>
        @endif
<!-- =====================================================
     200PX BLANK SPACE + SIGNATURE
====================================================== -->

<table class="prescription-signature-area">

    <tr>

        <td class="signature-blank-space">
            &nbsp;
        </td>

        <td class="signature-section">

            <div class="signature-bottom-line"></div>

            <div class="signature-text">
                (Sign &amp; Seal)
            </div>

        </td>

    </tr>

</table>

</body>

</html>