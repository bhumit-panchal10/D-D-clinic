@extends('layouts.app')
@section('title', 'Maintenance Records')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                @include('common.alert')

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Maintenance Register</h5>
                        <a href="{{ route('employee.maintenance.create') }}" class="btn btn-sm btn-primary">+ Add
                            Maintenance Record</a>
                    </div>

                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Item Name</th>
                                    <!-- <th>Complain Details</th> -->
                                    <th>Repair Person</th>
                                    <th>Repair Date</th>
                                    <th>Quotation Amount</th>
                                    <th>Pending Payment</th>
                                    <th>Received Date</th>
                                    <th>Payment Paid Amount</th>
                                    <th>Received Comment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($maintenances as $key => $maintenance)
                                    <tr>
                                        <td>{{ $maintenances->firstItem() + $key }}</td>
                                        <td>{{ $maintenance->item_name }}</td>
                                        <!-- <td>{{ $maintenance->complain_details }}</td> -->
                                        <td>{{ $maintenance->repair_person_name }}</td>
                                        <td>{{ date('d-m-Y', strtoTime($maintenance->repair_given_date)) }}</td>
                                        <td>{{ $maintenance->quotation_amount }}</td>
                                        <td>{{ $maintenance->quotation_amount - $maintenance->payment_paid_amount }}</td>
                                        <td class="received-date-{{ $maintenance->id }}">
                                            {{ $maintenance->repair_received_date ? date('d-m-Y', strtotime($maintenance->repair_received_date)) : '-' }}
                                        </td>
                                        <td class="payment-paid-{{ $maintenance->id }}">
                                            {{ $maintenance->payment_paid_amount }}
                                        </td>
                                        <td class="received-comment-{{ $maintenance->id }}">
                                            {{ $maintenance->received_comment ?? '-' }}
                                        </td>

                                        <td id="action-buttons-{{ $maintenance->id }}">
                                            @if(!$maintenance->repair_received_date)
                                                <a href="{{ route('employee.maintenance.edit', $maintenance->id) }}"
                                                    class="btn btn-sm btn-primary edit-btn-{{ $maintenance->id }}">
                                                    Edit
                                                </a>

                                                <button class="btn btn-sm btn-primary delete-maintenance"
                                                    data-id="{{ $maintenance->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#deleteRecordModal">
                                                    Delete
                                                </button>

                                                <button class="btn btn-sm btn-primary mark-received"
                                                    data-id="{{ $maintenance->id }}" data-item-name="{{ $maintenance->item_name }}"
                                                    data-quotation-amount="{{ $maintenance->quotation_amount }}"
                                                    data-payment-paid-amount="{{ $maintenance->payment_paid_amount }}"
                                                    data-pending-amount="{{ $maintenance->quotation_amount - $maintenance->payment_paid_amount }}">Mark
                                                    as Received
                                                </button>
                                            @else
                                                <span class="badge bg-primary">Received</span>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $maintenances->links('pagination::bootstrap-4') }}
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
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this maintenance register?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-primary" id="confirmDelete">Yes, Delete It!</button>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="maintenance_id" id="deleteid" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Modal End -->

    <!-- Mark as Received Modal -->
    <div class="modal fade" id="markReceivedModal" tabindex="-1" aria-labelledby="markReceivedModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="markReceivedModalLabel">Mark as Received</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form method="post" action="{{ route('employee.maintenance.markAsReceived') }}">
                        @csrf
                        <input type="hidden" name="id" id="maintenance_id">

                        <div class="mb-3">
                            <label>Item Name</label>
                            <input type="text" class="form-control" id="item_name" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Quotation Amount</label>
                            <input type="text" class="form-control" id="quotation_amount" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Payment Paid Amount</label>
                            <input type="text" class="form-control" id="payment_paid_amount" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Pending Amount</label>
                            <input type="text" class="form-control" id="pending_amount" readonly>
                        </div>

                        <div class="mb-3">
                            <label>Pay Due Amount</label><span class="text-danger">*</span>
                            <input type="text" class="form-control numeric-only" name="pay_due_amount" id="pay_due_amount"
                                maxlength="10" required>
                        </div>

                        <div class="mb-3">
                            <label>Received Comment</label>
                            <textarea class="form-control" name="received_comment" id="received_comment" rows="3"
                                maxlength="255"></textarea>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {

            // Numeric-only restriction
            $(".numeric-only").on("input", function () {
                this.value = this.value.replace(/[^0-9.]/g, ''); // Allow only numbers and dots
            });

            $(".delete-maintenance").on("click", function () {
                let id = $(this).data("id");
                $("#deleteid").val(id);
            });

            // Confirm Delete Button Click
            $("#confirmDelete").on("click", function () {
                let id = $("#deleteid").val();
                let actionUrl = "{{ route('employee.maintenance.destroy', ':id') }}".replace(':id', id);
                $("#deleteForm").attr("action", actionUrl);
                $("#deleteForm").submit();
            });

            // Show "Mark as Received" modal and populate data
            $(".mark-received").on("click", function () {
                let maintenanceId = $(this).data("id");
                let itemName = $(this).data("item-name");
                let quotationAmount = parseFloat($(this).data("quotation-amount")) || 0;
                let paymentPaidAmount = parseFloat($(this).data("payment-paid-amount")) || 0;
                let pendingAmount = quotationAmount - paymentPaidAmount;

                $('#maintenance_id').val(maintenanceId);
                $('#item_name').val(itemName);
                $('#quotation_amount').val(quotationAmount);
                $('#payment_paid_amount').val(paymentPaidAmount);
                $('#pending_amount').val(pendingAmount);

                $('#markReceivedModal').modal('show');
            });

            // Validate Pay Due Amount before form submission
            $('#pay_due_amount').on("input", function () {
                let pendingAmount = parseFloat($('#pending_amount').val()) || 0;
                let payDueAmount = parseFloat($(this).val()) || 0;

                if (payDueAmount > pendingAmount) {
                    alert("Pay Due Amount cannot be greater than Pending Amount.");
                    $(this).val(pendingAmount); // Reset to max allowed value
                }
            });
        });

    </script>
@endsection