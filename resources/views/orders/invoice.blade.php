<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            padding: 20px;
        }

        .header {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        .total {
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">Invoice #{{ $order->invoice_no }}</div>
        <p><strong>Patient Name:</strong> {{ $order->patient->name }}</p>
        <p><strong>Date:</strong> {{ $order->created_at->format('d-m-Y') }}</p>

        <table>
            <thead>
                <tr>
                    <th>Treatment</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Amount</th>
                    {{-- <th>Discount</th> --}}
                    {{-- <th>Net Amount</th> --}}
                </tr>
            </thead>
            <tbody>
                @php
                    $totalAmount = 0;
                    $totalDiscount = 0;
                    $totalNetAmount = 0;
                @endphp

                @foreach ($order->orderDetails as $detail)
                    @php
                        $totalAmount += $detail->amount;
                        $totalDiscount += $detail->discount;
                        $totalNetAmount += $detail->net_amount;
                    @endphp
                    <tr>
                        <td>{{ $detail->patientTreatmentItem->treatment->treatment_name }}</td>
                        <td>{{ $detail->qty }}</td>
                        <td>{{ number_format($detail->rate, 2) }}</td>
                        <td>{{ number_format($detail->amount, 2) }}</td>
                        {{-- <td>{{ number_format($detail->discount, 2) }}</td> --}}
                        {{-- <td>{{ number_format($detail->net_amount, 2) }}</td> --}}
                    </tr>
                @endforeach

                <!-- Total Row -->
                <tr>
                    <td colspan="3" class="total">Total:</td>
                    <td><strong>{{ number_format($totalAmount, 2) }}</strong></td>
                    {{-- <td><strong>{{ number_format($totalDiscount, 2) }}</strong></td> --}}
                    {{-- <td><strong>{{ number_format($totalNetAmount, 2) }}</strong></td> --}}
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
