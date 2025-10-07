@extends('layouts.app')
@section('title', 'Payment Report')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <h5 class="mb-3">Payment Report</h5>

                <form action="{{ route('payments.report') }}" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}">
                        </div>
                        <div class="col-md-3">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                           <a href="{{ route('payments.report') }}" class="btn btn-primary w-100">Clear</a>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <a href="{{ route('payments.export', ['from_date' => request('from_date', $fromDate), 'to_date' => request('to_date', $toDate)]) }}"
                                class="btn btn-primary w-100">
                                Export to Excel
                            </a>
                        </div>
                    </div>
                </form>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Patient Name</th>
                                    <th>Payment Date</th>
                                    <th>Mode</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $key => $payment)
                                    <tr>
                                        <td>{{ $payments->firstItem() + $key }}</td>
                                        <td>{{ $payment->patient->name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($payment->payment_date)) }}</td>
                                        <td>{{ $payment->mode }}</td>
                                        <td class="text-end">{{ number_format($payment->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <h5 class="mt-3 text-end">Total Amount : ₹{{ number_format($totalAmount, 2) }}</h5>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $payments->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection