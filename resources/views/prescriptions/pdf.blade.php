<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Prescription</title>
    <style>
        .prescription-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 12px;
        }

        .prescription-table th,
        .prescription-table td {
            border: 1px solid #777;
            padding: 6px 7px;
            vertical-align: top;
        }

        .prescription-table th {
            background: #f2f2f2;
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
        }

        .medicine-content {
            font-size: 10px;
            color: #666;
            margin-top: 2px;
        }

        .rx-title {
            font-family: DejaVu Sans, sans-serif;
            font-size: 20px;
            margin-top: 10px;
            margin-bottom: 5px;
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
                <strong>{{ $prescription->patient->name ?? '' }}
                    {{ $prescription->patient->middle_name ?? '' }}
                    {{ $prescription->patient->last_name ?? '' }}</strong><br>
                &nbsp;&nbsp;{{ $age ? $age . ' yrs' : '' }}
                / {{ $genderInitial }}
            </td>
        </tr>
    </table>

    <!-- Rx and Medicines -->
    <div class="rx-title">Rx</div>

    <table class="prescription-table">
        <thead>
            <tr>
                <th class="medicine-col" style="text-align:left;">
                    Medicine
                </th>

                <th class="frequency-col">
                    Frequency
                </th>

                <th class="duration-col">
                    Duration
                </th>

                <th class="qty-col">
                    Qty
                </th>
            </tr>
        </thead>

        <tbody>

            @foreach ($prescription->prescriptionDetails as $index => $detail)
                <tr>

                    {{-- Medicine --}}
                    <td class="medicine-col">

                        <div class="medicine-name">
                            {{ $index + 1 }}.
                            {{ $detail->medicine->medicine_name ?? '' }}
                        </div>

                        {{-- Medicine Master Comment --}}
                        @if (!empty($detail->medicine->comment))
                            <div class="medicine-comment">
                                {{ $detail->medicine->comment }}
                            </div>
                        @endif

                        {{-- Medicine Master Content --}}
                        @if (!empty($detail->medicine->content))
                            <div class="medicine-content">
                                {{ $detail->medicine->content }}
                            </div>
                        @endif

                    </td>


                    {{-- Frequency / Dosage --}}
                    <td class="frequency-col">

                        {{ $detail->dosage->dosage ?? '' }}

                    </td>


                    {{-- Duration --}}
                    <td class="duration-col">

                        @if ($detail->days)
                            {{ $detail->days }} days
                        @endif

                    </td>


                    {{-- Quantity --}}
                    <td class="qty-col">

                        {{ $detail->medicine_qty ?? '' }}

                    </td>

                </tr>
            @endforeach

        </tbody>
    </table>




    {{-- <table>
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
    </table> --}}


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
