<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .container {
            width: 100%;
            padding: 10px;
        }

        .top-section {
            display: flex;
            justify-content: space-between;
        }

        .clinic-name {
            font-size: 16px;
            font-weight: bold;
        }

        .right {
            text-align: right;
        }

        .info {
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th {
            text-align: center;
            font-weight: bold;
        }

        td {
            padding: 5px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
        }

        .clinic-footer {
            margin-top: 40px;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- Top Section -->
        <div class="top-section">
            <div>
                <strong>{{ $order->patient->name }}</strong><br>
                {{ \Carbon\Carbon::parse($order->patient->dob)->age }} Years, {{ $order->patient->gender }}
            </div>

            <div class="right">
                Date: {{ date('d-M-Y', strtotime($order->date)) }}<br>
                Bill No: {{ $order->invoice_no }}
            </div>
        </div>

        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Treatment</th>
                    <th>Tooth no</th>
                    <th>Qty</th>
                    <th>Cost</th>
                    <th>Amount</th>
                    <th>Paid</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp

                @foreach ($order->orderDetails as $key => $detail)
                    @php $total += $detail->amount; @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $detail->treatment->treatment_name }}</td>
                        <td>{{ $detail->tooth_no }}</td>
                        <td class="text-center">{{ $detail->qty }}</td>
                        <td class="text-right">{{ number_format($detail->rate, 2) }}</td>
                        <td class="text-right">{{ number_format($detail->amount, 2) }}</td>
                        <td class="text-right">{{ number_format($detail->amount, 2) }}</td>
                    </tr>
                @endforeach

                <!-- Total -->
                <tr>
                    <td colspan="5" class="text-right"><strong>Total Amount :</strong></td>
                    <td class="text-right"><strong> {{ number_format($total, 2) }}</strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <!-- Footer -->
        {{-- <div class="footer">
            <br><br>
            <strong>Authorized Sign</strong>
        </div>

        <div class="clinic-footer">
            <strong>D & D Dental Clinic</strong><br>
            1st Floor, Tavish Avenue,<br>
            Chandkheda Road, Ahmedabad<br>
            Mo: 97248 81458<br>
        </div> --}}

    </div>
</body>

</html>
