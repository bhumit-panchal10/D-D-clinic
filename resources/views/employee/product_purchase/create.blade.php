@extends('layouts.app')
@section('title', 'Add Product Purchase')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            @include('common.alert')

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Add Product Purchase</h5>
                    <a href="{{ route('employee.product_purchase.index') }}" class="btn btn-sm btn-primary">Back</a>
                </div>

                <div class="card-body">
                    <form action="{{ route('employee.product_purchase.store') }}" method="post">
                        @csrf

                        <div class="row">
                            <div class="mb-3 col-lg-4">
                                <label>Product Name <span class="text-danger">*</span></label>
                                <select class="form-control" name="product_id" id="product_id" required>
                                    <option value="" disabled selected>Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-lg-4">
                                <label>Vendor Name <span class="text-danger">*</span></label>
                                <select class="form-control" name="vendor_id" required>
                                    <option value="" disabled selected>Select Vendor</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            </div>


                        <div class="row">
                            <div class="mb-3 col-lg-2">
                                <label>Quantity <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter QTY" name="quantity" id="quantity" required minlength="1" maxlength="10">
                            </div>

                            <div class="mb-3 col-lg-2">
                                <label>Rate <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Rate" name="rate" id="rate" required minlength="1" maxlength="10">
                            </div>
                            

                            <div class="mb-3 col-lg-2">
                                <label>Amount <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Amount" id="amount" name="amount" readonly>
                            </div>

                            <div class="mb-3 col-lg-2">
                                <label>Received Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="received_date" max="{{ now()->toDateString() }}" required>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="reset" class="btn btn-primary">Clear</button>
                        </div>
                    </form>
                    <div class="mb-3">
                        <h5>Last 4 Purchases for Selected Product</h5>
                        <table class="table table-striped" id="purchase-history">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Vendor Name</th>
                                    <th>Quantity</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                    <th>Received Date</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {

    // Allow only numeric input in Quantity field
    $('#quantity').on('input', function () {
        let val = $(this).val();

        // Remove non-numeric characters
        val = val.replace(/[^0-9]/g, '');

        // Ensure the value does not exceed 10 characters
        if (val.length > 10) {
            val = val.slice(0, 10);
        }

        $(this).val(val);
    });

    // Allow only numeric input with decimals in Rate field
    $('#rate').on('input', function () {
        let val = $(this).val();

        // Remove non-numeric characters except for decimals
        val = val.replace(/[^0-9.]/g, '');

        // Ensure only one decimal point is present
        if ((val.match(/\./g) || []).length > 1) {
            val = val.substring(0, val.lastIndexOf('.'));
        }

        // Ensure it does not exceed 10 characters
        if (val.length > 10) {
            val = val.slice(0, 10);
        }

        $(this).val(val);
    });

    // Calculate Amount
    $('#quantity, #rate').on('input', function () {
        const qty = parseFloat($('#quantity').val()) || 0;
        const rate = parseFloat($('#rate').val()) || 0;
        const amount = qty * rate;
        $('#amount').val(amount.toFixed(2));
    });

    // Fetch Last 4 Purchases
    $('#product_id').change(function () {
        const productId = $(this).val();
        if (productId) {
            $.ajax({
                url: "{{ route('employee.product_purchase.getLastPurchases') }}",
                type: 'GET',
                data: { product_id: productId },
                success: function (response) {

                    let rows = '';

                    if (response.length > 0) {
                        response.forEach(function (purchase) {
                            rows += `<tr>
                                <td>${purchase.product.product_name}</td>
                                <td>${purchase.vendor.company_name}</td>
                                <td>${purchase.quantity}</td>
                                <td>${purchase.rate}</td>
                                <td>${purchase.amount}</td>
                                <td>${purchase.received_date}</td>
                            </tr>`;
                        });
                    } else {
                        rows = `<tr>
                            <td colspan="6" class="text-center">No recent purchases found.</td>
                        </tr>`;
                    }

                    $('#purchase-history tbody').html(rows);
                }
            });
        } else {
            $('#purchase-history tbody').html(`<tr>
                <td colspan="6" class="text-center">Please select a product.</td>
            </tr>`);
        }
    });
});
</script>
@endsection
