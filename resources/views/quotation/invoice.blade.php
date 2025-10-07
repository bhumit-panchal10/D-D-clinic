<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotaion</title>
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
        <div class="header">Quotation #{{ $qutation->quotation_no }}</div>
        <p><strong>Patient Name:</strong> {{ $qutation->patient->name }}</p>
        <p><strong>Date:</strong> {{ $qutation->created_at->format('d-m-Y') }}</p>

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

                @foreach ($qutation->qutationDetails as $detail)
                    @php
                            $amount = floatval(str_replace(',', '', $detail->amount));
                            $discount = floatval($detail->discount);
                            $netAmount = floatval($detail->net_amount);
                    
                           
                            $totalAmount += $amount;
                            $totalDiscount += $discount;
                            $totalNetAmount += $netAmount;
                    @endphp
                    <tr>
                        <td>{{ $detail->patientTreatment->treatment->treatment_name }}</td>
                        <td>{{ $detail->qty }}</td>
                        <td>{{ number_format($detail->rate, 2) }}</td>
                        <td>{{ number_format($amount, 2) }}</td>
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
