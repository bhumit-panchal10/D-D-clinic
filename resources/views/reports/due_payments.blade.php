@extends('layouts.app')
@section('title', 'Due Payments Report')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <h5 class="m-3">Due Payments Report</h5>
                @include('common.alert')

                <!-- Filter Form -->
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('reports.due_payments') }}" class="mb-3">
                            <div class="row align-items-end">
                                <!-- From Date -->
                                <div class="col-md-3">
                                    <label for="from_date" class="form-label">From Date</label>
                                    <input type="date" id="from_date" name="from_date" class="form-control"
                                        value="{{ request('from_date', $fromDate) }}">
                                </div>

                                <!-- To Date -->
                                <div class="col-md-3">
                                    <label for="to_date" class="form-label">To Date</label>
                                    <input type="date" id="to_date" name="to_date" class="form-control"
                                        value="{{ request('to_date', $toDate) }}">
                                </div>

                                <!-- Search Button -->
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">Search</button>
                                </div>
                                
                                <div class="col-md-1 d-flex align-items-end">
                                   <a href="{{ route('reports.due_payments') }}" class="btn btn-primary w-100">Clear</a>
                                </div>

                                <!-- Export to Excel Button -->
                                <div class="col-md-2 d-flex align-items-end">
                                    <a href="{{ route('export.due_payments', ['from_date' => request('from_date', $fromDate), 'to_date' => request('to_date', $toDate)]) }}"
                                        class="btn btn-primary w-100">
                                        Export to Excel
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Report Table -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Due Payment List</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Patient Name</th>
                                    <th>Invoice No</th>
                                    <th>Amount</th>
                                    <th>Discount</th>
                                    <th>Net Amount</th>
                                    <th>Paid Payment</th>
                                    <th>Due Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $index => $order)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $order['patient_name'] }}</td>
                                        <td>{{ $order['invoice_no'] }}</td>
                                        <td class="text-end">{{ number_format($order['amount'], 2) }}</td>
                                        <td class="text-end">{{ number_format($order['discount'], 2) }}</td>
                                        <td class="text-end">{{ number_format($order['net_amount'], 2) }}</td>
                                        <td class="text-end">{{ number_format($order['paid_amount'], 2) }}</td>
                                        <td class="text-end">{{ number_format($order['due_amount'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No due payments found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-center">Total</th>
                                    <th class="text-end">{{ number_format($totals['total_amount'], 2) }}</th>
                                    <th class="text-end">{{ number_format($totals['total_discount'], 2) }}</th>
                                    <th class="text-end">{{ number_format($totals['total_net'], 2) }}</th>
                                    <th class="text-end">{{ number_format($totals['total_paid'], 2) }}</th>
                                    <th class="text-end">{{ number_format($totals['total_due'], 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection