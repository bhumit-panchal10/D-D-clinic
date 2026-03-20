<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
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
        <div class="header">Payment Detail</div>
        <p><strong>Patient Name:</strong> {{ $payments->patient->name }}</p>
        <p><strong>Date:</strong> {{ date('d-m-Y',strtotime($payments->created_at)) }}</p>

        <table>
            <thead>
                <tr>
                    <th>Payment Date</th>
                    <th>Payment Mode</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ date('d-m-Y',strtotime($payments->payment_date)) }}</td>
                    <td>{{ $payments->mode }}</td>
                    <td>{{ number_format($payments->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
