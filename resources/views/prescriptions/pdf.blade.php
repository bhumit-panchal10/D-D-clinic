<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Prescription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            font-size: 14px;
        }

        table {
            width: 100%;
        }

        .header-title {
            font-size: 20px;
            font-weight: bold;
            color: #0c3b6a;
        }

        .textcolor {
            color: #0c3b6a;
        }

        .medicine-table {
            margin-left: auto;
            margin-right: auto;
            width: 60%;
            /* Optional: you can adjust this */
        }

        .clinic-subtitle {
            font-style: italic;
            color: #007a7c;
        }

        .doctor-info {
            font-size: 12px;
        }

        .rx {
            font-weight: bold;
            font-size: 18px;
            padding-top: 10px;
        }

        .patient-info {
            padding-top: 20px;
        }

        .medicine-table td {
            padding: 5px 0;
            vertical-align: top;
        }

        .footer {
            font-size: 12px;
            text-align: center;
            padding-top: 40px;
        }

        .signature {
            text-align: right;
            padding-top: 50px;
        }
    </style>
</head>
@php
    use Carbon\Carbon;

    $dob = $prescription->patient->dob;
    $age = $dob ? Carbon::parse($dob)->age : '';
    $gender = $prescription->patient->gender;
    $genderInitial = $gender ? strtoupper(substr($gender, 0, 1)) : '';
@endphp

<body>

    <!-- Header -->
      <div style="height: 250px; overflow:hidden;">
        <!--<table width="100%" cellspacing="0" cellpadding="5" style=" displayfont-family: Arial, sans-serif;">-->
        <!--    <tr align="center">-->

        <!--        <td align="center" colspan="3">-->
        <!--            <img src="file:///var/www/html/dental_clinic/public/assets/images/logo3.png" alt="Logo"-->
        <!--                width="70">-->

        <!--            <span style="font-size: 20px; font-weight: bold; color: #0c3b6a;">DENTAL CLINIC &amp; IMPLANT-->
        <!--                CENTER</span><br>-->
        <!--            <hr style="border: 1px solid #000000;">-->
        <!--            <span style="font-style: italic; font-size: 14px; color: #0c6e6e;">-->
        <!--                A Biomimetic and Minimally Invasive Dental Clinic-->
        <!--            </span>-->
        <!--        </td>-->

        <!--    </tr>-->

        <!--    <tr>-->
        <!--        <td colspan="3">-->
        <!--            <hr style="border: 1px solid #000000;">-->
        <!--        </td>-->
        <!--    </tr>-->

        <!--    <tr valign="top">-->
        <!--        <td style="font-size: 13px; color: #000;" width="35%">-->
        <!--            <strong>Dr. Dwij Kothari (M.D.S.)</strong><br>-->
        <!--            Prosthodontist &amp; Implantologist<br>-->
        <!--            Smile Design (Veneer) Specialist<br>-->
        <!--            Ex-Reader (Goenka Dental College)<br>-->
        <!--            Regn No.: A-6214-->
        <!--        </td>-->
        <!--        <td width="30%"></td>-->
        <!--        <td width="35%" style="font-size: 13px; color: #000; text-align: left; line-height: 1.4;">-->
        <!--            <strong>Dr. Deepa Vaid (M.D.S.)</strong><br>-->
        <!--            Micro-Endodontist (RCT Specialist)<br>-->
        <!--            Biomimetic Restorative Dentist<br>-->
        <!--            Reader (C.D.S.R.C. Bopal)<br>-->
        <!--            Regn No.: A-6215<br><br>-->
        <!--            <span style="white-space: nowrap;">Date:-->
        <!--                <strong>{{ $prescription->created_at->format('d-m-Y') }}</strong></span>-->
        <!--        </td>-->

        <!--    </tr>-->


        <!--</table>-->
    </div>



    <!-- Patient Info -->
    <table class="patient-info">
        <tr>
            <td colspan="2">
                <strong>{{ $prescription->patient->name }}</strong> &nbsp;&nbsp;{{ $age ? $age . ' yrs' : '' }}
                / {{ $genderInitial }}
            </td>
        </tr>
    </table>

    <!-- Rx and Medicines -->
    <table>
        <tr>
            <td colspan="2" class="rx">Rx</td>
        </tr>

        @php $afterSurgeryStarted = false; @endphp

        @foreach ($prescription->prescriptionDetails as $index => $detail)
            @if ($detail->comments === 'after surgery' && !$afterSurgeryStarted)
                <tr>
                    <td colspan="2" style="padding-top: 10px;"><strong>After Surgery:</strong></td>
                </tr>
                @php $afterSurgeryStarted = true; @endphp
            @endif

            @php
                $dosage = $detail->dosage->dosage ?? '0-0-0'; // e.g., "1-0-1"
                $dosageParts = explode('-', $dosage);
                $totalPerDay = collect($dosageParts)->map(fn($val) => (int) $val)->sum();
                $qty = $detail->days * $totalPerDay;
            @endphp

            <tr>
                <td>{{ $index + 1 }}.</td>
                <td>
                    Tab. {{ $detail->medicine->medicine_name ?? '' }}<br>
                    {{ $detail->dosage->dosage ?? '' }}&nbsp;
                    @if ($detail->days)
                        × {{ $detail->days }} days
                    @endif
                    ___________ {{ $qty }}
                    <br>
                      {{ $detail->comments ? $detail->comments : '' }}
                    <br>
                    <br>

                </td>
            </tr>
        @endforeach
    </table>


    <!-- Signature -->
    <!--<table class="signature">-->
    <!--    <tr>-->
    <!--        <td><br><br>__________________________<br>Signature</td>-->
    <!--    </tr>-->
    <!--</table>-->

    <!-- Footer -->
    <!--<table class="footer">-->
    <!--    <tr>-->
    <!--        <td>-->
    <!--            1<sup>st</sup> floor, Tavish Avenue, Opp. Aaryan Eminent, Near Ganesh Meridian,<br>-->
    <!--            Kargil Cross Road, Off S.G. Highway, Chanakyapuri Road, Ahmedabad - 61<br>-->
    <!--            E-mail: danddentalclinic@gmail.com | Web: www.dnddentistry.com<br>-->
    <!--            <strong>Contact:</strong> 97246 57455 (9am to 9pm) | <strong>Emergency:</strong> 98258 26746<br>-->
    <!--            <strong>Timing:</strong> Morning: 10:00am to 1:00pm &nbsp; | &nbsp; Evening: 4:00pm to 8:00pm-->
    <!--        </td>-->
    <!--    </tr>-->
    <!--</table>-->

</body>

</html>
