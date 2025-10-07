@extends('layouts.app')
@section('title', 'Edit Maintenance Record')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            @include('common.alert')

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Edit Maintenance Record</h5>
                    <a href="{{ route('maintenance.index') }}" class="btn btn-sm btn-primary">Back</a>
                </div>

                <div class="card-body">
                    <form action="{{ route('maintenance.update', $maintenance->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label>Item Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Item Name" name="item_name" 
                                    value="{{ $maintenance->item_name }}" 
                                     maxlength="50" required>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label>Repair Person Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Repair Person Name" name="repair_person_name" 
                                    value="{{ $maintenance->repair_person_name }}" 
                                     maxlength="50" required>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label>Repair Given Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" placeholder="Enter Repair Given Date" name="repair_given_date" 
                                    value="{{ $maintenance->repair_given_date }}" 
                                    min="{{ date('Y-m-d', strtotime('-1 year')) }}" 
                                    max="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-md-4">
                                    <label>Details of Complain <span class="text-danger">*</span></label>
                                    <textarea class="form-control" placeholder="Enter Details of Complain" name="complain_details" 
                                         maxlength="255" required>{{ $maintenance->complain_details }}</textarea>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label>Quotation Amount <span class="text-danger">*</span></label>
                                <input type="text" class="form-control numeric-only" placeholder="Enter Quotation Amount" 
                                    name="quotation_amount" value="{{ $maintenance->quotation_amount }}" 
                                    maxlength="10" required>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label>Payment Paid Amount <span class="text-danger">*</span></label>
                                <input type="text" class="form-control numeric-only" placeholder="Enter Payment Paid Amount" 
                                    name="payment_paid_amount" value="{{ $maintenance->payment_paid_amount }}" 
                                    maxlength="10" required>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('maintenance.index') }}" class="btn btn-primary">Cancel</a>
                        </div>
                    </form>
                </div> 
            </div> 
        </div> 
    </div> 
</div> 

@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $(".numeric-only").on("input", function () {
            this.value = this.value.replace(/[^0-9.]/g, ''); // Allow only numbers and dots
        });
        $("form").on("submit", function (e) {
            let quotationAmount = parseFloat($("input[name='quotation_amount']").val()) || 0;
            let paymentPaidAmount = parseFloat($("input[name='payment_paid_amount']").val()) || 0;

            if (paymentPaidAmount > quotationAmount) {
                alert("Payment Paid Amount cannot be greater than Quotation Amount.");
                e.preventDefault(); // Prevent form submission
            }
        });
    });
</script>
@endsection