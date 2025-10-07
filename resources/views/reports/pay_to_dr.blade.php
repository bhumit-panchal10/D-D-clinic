@extends('layouts.app')
@section('title', 'Pay To Dr Report')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="d-flex justify-content-between">

                <h5 class="mb-3">Pay To Dr Report</h5>
                
                 <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('pay_to_dr.export', ['from_date' => request('from_date', $fromDate), 'to_date' => request('to_date', $toDate)]) }}"
                        class="btn btn-primary w-100">
                        Export to Excel
                    </a>
                </div>
                </div>

                <form action="{{ route('reports.pay_to_dr') }}" method="POST" class="mb-3">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" name="from_date" id="from_date" class="form-control"
                                value="{{ $fromDate }}">
                        </div>
                        <div class="col-md-3">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" name="to_date" id="to_date" class="form-control"
                                value="{{ $toDate }}">
                        </div>
                        <div class="col-md-3">
                            <label for="to_date" class="form-label">Doctor</label>
                            <select class="form-control" name="doctor_id" id="doctor_id">
                                <option value="">Select Doctor</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ old('doctor_id', $doctorId ?? '') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->doctor_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div>
                         <div class="col-md-1 d-flex align-items-end">
                           <a href="{{ route('reports.pay_to_dr') }}" class="btn btn-primary w-100">Clear</a>
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
                                    <th>Doctor Name</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Payment Mode</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($datas as $key => $payment)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $payment->name }}</td>
                                        <td>{{ $payment->doctor_name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($payment->created_at)) }}</td>
                                        <td>{{ $payment->amount }}</td>
                                        <td>{{ $payment->mode == 0 ? 'Cash' : 'Online' }}</td>
                                    </tr>
                                    @php
                                        $i++;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>

                        <h5 class="mt-3 text-end">Total Amount : ₹{{ number_format($totalAmount, 2) }}</h5>

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
