@extends('layouts.app')
@section('title', 'Lab Work Report')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="d-flex justify-content-between">
                    <h5 class="mb-3">Lab Work Report</h5>
                    
                         <div class="col-md-2 d-flex align-items-end">
                                    <a href="{{ route('lab_work_report.export', ['from_date' => request('from_date', $fromDate), 'to_date' => request('to_date', $toDate), 'lab_id' => request('lab_id', $labId ?? '')]) }}"
                                        class="btn btn-primary w-100">
                                        Export to Excel
                                    </a>
        
                        </div>
                </div>

                <form action="{{ route('reports.lab_work_Report') }}" method="POST" class="mb-3">
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
                            <label for="to_date" class="form-label">Lab</label>
                            <select class="form-control" name="lab_id" id="lab_id">
                                <option value="">Select Lab</option>
                                @foreach ($labs as $lab)
                                    <option value="{{ $lab->id }}"
                                        {{ old('lab_id', $labId ?? '') == $lab->id ? 'selected' : '' }}>
                                        {{ $lab->lab_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                           <a href="{{ route('reports.lab_work_Report') }}" class="btn btn-primary w-100">Clear</a>
                        </div>

                       
                    </div>
                </form>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr. No</th>
                                    <th>Lab Name</th>
                                    <th>Patient Name</th>
                                    <th>Comment</th>
                                    <th>Entry Date</th>
                                    <th>Pickup Date</th>
                                    <th>Received Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($datas as $key => $payment)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $payment->lab_name }}</td>
                                        <td>{{ $payment->name }}</td>
                                        <td>{{ $payment->comment ?? '-' }}</td>
                                        <td>{{ date('d-m-Y', strtotime($payment->entry_date)) }}</td>
                                        <td>{{ $payment->collection_date ? \Carbon\Carbon::parse($payment->collection_date)->format('d-m-Y') : '-' }}
                                        </td>
                                        <td>{{ $payment->received_date ? \Carbon\Carbon::parse($payment->received_date)->format('d-m-Y') : '-' }}
                                        </td>
                                    </tr>
                                    @php
                                        $i++;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
