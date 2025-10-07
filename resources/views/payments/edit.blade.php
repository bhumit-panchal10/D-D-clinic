@extends('layouts.app')
@section('title', 'Edit Payment')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center m-3">
                   <h5 class="mb-0">
                        Name: {{ $patient->name }} | Mobile No 1: {{ $patient->mobile1 }}
                        @if($patient->mobile2 != '')
                        | Mobile No 2: {{ $patient->mobile2 }}
                        @endif
                        | Case No: {{ $patient->case_no }}
                   </h5>            <a href="{{ route('patient.index') }}" class="btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                </a>
            </div>


            {{-- Alert Messages --}}
            @include('common.alert')
            @include('patient.show', ['id' => $payment->patient_id])

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Payments</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('payments.update', $payment->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="patient_id" value="{{ $payment->patient_id }}">
                        <input type="hidden" name="order_id" value="{{ $payment->order_id }}">

                        <div class="row">
                        <div class="col-md-4">
                            <label for="payment_date" class="form-label">Payment Date<span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ $payment->payment_date }}" required>
                        </div>

                        <div class="col-md-4">
                            <label for="amount" class="form-label">Amount<span class="text-danger">*</span></label>
                            <input type="text" name="amount" id="amount" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" maxlength="10" value="{{ $payment->amount }}" required>
                        </div>

                        
                        <div class="col-md-4">
                            <label for="mode" class="form-label">Payment Mode<span class="text-danger">*</span></label>
                            <select name="mode" id="mode" class="form-select" required>
                                <option value="Cash" {{ $payment->mode == 'Cash' ? 'selected' : '' }}>Cash</option>
                                <option value="Online" {{ $payment->mode == 'Online' ? 'selected' : '' }}>Online</option>
                            </select>
                        </div>

                        <div class="row mt-3">
                        <div class="col-md-4">
                            <label for="comments" class="form-label">Comments</label>
                            <textarea name="comments" id="comments" class="form-control">{{ $payment->comments }}</textarea>
                        </div>
                        </div>


                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Update Payment</button>
                            <a href="{{ route('payments.index', $payment->patient_id) }}" class="btn btn-primary mx-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>


<script>
    document.getElementById("payment_date").addEventListener("input", function () {
        let input = this.value;
        let parts = input.split("-");
        if (parts[0] && parts[0].length > 4) {
            parts[0] = parts[0].slice(0, 4); // Restrict the year to 4 digits
            this.value = parts.join("-");
        }
    });
</script>

@endsection
