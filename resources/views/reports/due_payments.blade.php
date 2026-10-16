@extends('layouts.app')

@section('title', 'Due Payments Report')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <h5 class="mb-3">Due Payments Report</h5>

                @include('common.alert')

                <!-- Filter -->
                <div class="card mb-3">
                    <div class="card-body">

                        <form method="GET" action="{{ route('reports.due_payments') }}" class="mb-0">

                            <div class="row">

                                <!-- From Date -->
                                <div class="col-md-3">
                                    <label for="from_date" class="form-label">
                                        From Date
                                    </label>

                                    <input type="date" name="from_date" id="from_date" class="form-control"
                                        value="{{ $fromDate }}">
                                </div>

                                <!-- To Date -->
                                <div class="col-md-3">
                                    <label for="to_date" class="form-label">
                                        To Date
                                    </label>

                                    <input type="date" name="to_date" id="to_date" class="form-control"
                                        value="{{ $toDate }}">
                                </div>

                                <!-- Search -->
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        Search
                                    </button>
                                </div>

                                <!-- Clear -->
                                <div class="col-md-2 d-flex align-items-end">
                                    <a href="{{ route('reports.due_payments') }}" class="btn btn-primary w-100">
                                        Clear
                                    </a>
                                </div>

                                <!-- Export -->
                                <div class="col-md-2 d-flex align-items-end">
                                    <a href="{{ route('export.due_payments', [
                                        'from_date' => request('from_date', $fromDate),
                                        'to_date' => request('to_date', $toDate),
                                    ]) }}"
                                        class="btn btn-primary w-100">
                                        Export to Excel
                                    </a>
                                </div>

                            </div>

                        </form>

                    </div>
                </div>


                <!-- Due Payment Table -->
                <div class="card">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Due Payment List
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-bordered table-striped">

                                <thead>
                                    <tr>
                                        <th>Sr. No</th>
                                        <th>Date</th>
                                        <th>Case No</th>
                                        <th>Patient Name</th>
                                        <th>Treatment</th>
                                        <th>Amount</th>
                                        <th>Payment Received</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @forelse ($notes as $key => $note)
                                        <tr>

                                            <!-- Sr No -->
                                            <td>
                                                {{ $notes->firstItem() + $key }}
                                            </td>

                                            <!-- Date -->
                                            <td>
                                                {{ date('d-m-Y', strtotime($note->date)) }}
                                            </td>

                                            <!-- Case No -->
                                            <td>
                                                {{ $note->patient->case_no ?? '-' }}
                                            </td>

                                            <!-- Patient -->
                                            <td>
                                                @if ($note->patient)
                                                    <a href="{{ route('notes.index', $note->patient->id) }}">
                                                        {{ $note->patient->name }}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <!-- Treatment -->
                                            <td>
                                                {{ $note->treatment_detail->treatment_name ?? '-' }}
                                            </td>

                                            <!-- Total Amount -->
                                            <td class="text-end">
                                                ₹{{ number_format($note->total_amount, 2) }}
                                            </td>

                                            <!-- Paid -->
                                            <td class="text-end">
                                                ₹{{ number_format($note->paid_amount, 2) }}
                                            </td>

                                            <!-- Due -->
                                            <td class="text-end">
                                                <strong>
                                                    ₹{{ number_format($note->due_amount, 2) }}
                                                </strong>
                                            </td>

                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="8" class="text-center">
                                                No due payments found.
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>


                        <!-- Totals -->
                        @if ($notes->count() > 0)
                            <div class="mt-3">

                                <h5 class="text-end">
                                    Total Amount :
                                    ₹{{ number_format($totalAmount, 2) }}
                                </h5>

                                <h5 class="text-end">
                                    Paid Amount :
                                    ₹{{ number_format($paidAmount, 2) }}
                                </h5>

                                <h5 class="text-end">
                                    Due Amount :
                                    ₹{{ number_format($dueAmount, 2) }}
                                </h5>

                            </div>
                        @endif


                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">

                            {{ $notes->appends(request()->query())->links('pagination::bootstrap-4') }}

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection
