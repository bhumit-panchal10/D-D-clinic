@extends('layouts.app')
@section('title', 'Patient Report')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <h5 class="mb-3">Patient Report</h5>

                <form action="{{ route('patients.report') }}" method="GET" class="mb-3">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" id="from_date" name="from_date" class="form-control"
                                value="{{ request('from_date', $fromDate) }}">
                        </div>
                        <div class="col-md-3">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" id="to_date" name="to_date" class="form-control"
                                value="{{ request('to_date', $toDate) }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div>
                         <div class="col-md-1 d-flex align-items-end">
                           <a href="{{ route('patients.report') }}" class="btn btn-primary w-100">Clear</a>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <a href="{{ route('patients.export', ['from_date' => request('from_date', $fromDate), 'to_date' => request('to_date', $toDate)]) }}"
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
                                    <th>Mobile</th>
                                    <th>DOB</th>
                                    <th>Reference By</th>
                                    <th>Govt. Employee</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($patients as $key => $patient)
                                    <tr>
                                        <td>{{ $patients->firstItem() + $key }}</td>
                                        <td>{{ $patient->name }}</td>
                                        <td>{{ $patient->mobile1 }}</td>
                                        <td>{{ date('d-m-Y', strtotime($patient->dob ?? '-')) }}</td>
                                        <td>{{ $patient->reference_by ?? '-' }}</td>
                                        <td>{{ $patient->is_government_employee == 'yes' ? 'Yes' : 'No' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $patients->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection