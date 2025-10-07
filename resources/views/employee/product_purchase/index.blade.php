@extends('layouts.app')
@section('title', 'Product Purchases')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            @include('common.alert')

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Product Purchases</h5>

                    <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('employee.product_purchase.index') }}" method="GET" class="d-flex align-items-center gap-2">
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="Search by Product or Vendor"
                                value="{{ request()->search }}">
                            <button type="submit" class="btn btn-sm btn-primary">Search</button>

                            @if(request()->has('search'))
                                <a href="{{ route('employee.product_purchase.index') }}" class="btn btn-sm btn-primary">Clear</a>
                            @endif
                        </form>

                        <a href="{{ route('employee.product_purchase.create') }}" class="btn btn-sm btn-primary">+ Add Purchase</a>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Product Name</th>
                                <th>Vendor Name</th>
                                <th>Quantity</th>
                                <th>Rate</th>
                                <th>Amount</th>
                                <th>Received Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchases as $index => $purchase)
                            <tr>
                                <td>{{ ($purchases->currentPage() - 1) * $purchases->perPage() + $index + 1 }}</td>
                                <td>{{ $purchase->product->product_name }}</td>
                                <td>{{ $purchase->vendor->company_name }}</td>
                                <td>{{ $purchase->quantity }}</td>
                                <td>{{ $purchase->rate }}</td>
                                <td>{{ $purchase->amount }}</td>
                                <td>{{ date('d-m-Y', strtotime($purchase->received_date)) }}</td>
                                <td>
                                <button class="btn btn-sm btn-primary delete-purchase" data-id="{{ $purchase->id }}"
                                    data-bs-toggle="modal" data-bs-target="#deleteRecordModal">
                                    Delete
                                </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $purchases->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<!-- Delete Modal Start -->
<div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                            colors="primary:#f7b84b,secondary:#f06548" style="width : 100px; height : 100px">
                        </lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you Sure?</h4>
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this product purchase?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-primary" id="confirmDelete">Yes, Delete It!</button>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="purchase_id" id="deleteid" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Modal End -->

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $(".delete-purchase").on("click", function () {
        let id = $(this).data("id");
        $("#deleteid").val(id);

        // Capture current search query parameters
        let searchParams = new URLSearchParams(window.location.search);
        let actionUrl = "{{ route('employee.product_purchase.destroy', ':id') }}".replace(':id', id);

        if (searchParams.toString()) {
            actionUrl += '?' + searchParams.toString(); // Append existing search params
        }

        $("#deleteForm").attr("action", actionUrl);
    });

    // Confirm Delete Button Click
    $("#confirmDelete").on("click", function () {
        $("#deleteForm").submit();
    });
});

</script>
@endsection
