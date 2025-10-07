@extends('layouts.app')
@section('title', 'Invoices')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="d-flex justify-content-between align-items-center m-3">
                    <h5 class="mb-0">
                        Name: {{ $patient->name }} | Mobile No 1: {{ $patient->mobile1 }}
                        @if ($patient->mobile2 != '')
                            | Mobile No 2: {{ $patient->mobile2 }}
                        @endif
                        | Case No: {{ $patient->case_no }}
                    </h5>
                    <a href="{{ route('patient.index') }}" class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                    </a>
                </div>

                @include('common.alert')
                @include('patient.show', ['id' => $patient->id])

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Invoices</h5>
                        <a href="{{ route('orders.create', $patient->id) }}" class="btn btn-primary">+ Add Invoice</a>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr no.</th>
                                    <th>Invoice No</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    {{-- <th>Discount</th> --}}
                                    {{-- <th>Net Amount</th> --}}
                                    <!-- <th>Paid Payment</th> -->
                                    <!-- <th>Due Payment</th> -->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $key => $order)
                                    <tr>
                                        <td>{{ $orders->firstItem() + $key }}</td>
                                        <td>{{ $order->invoice_no }}</td>
                                        <td>{{ $order->created_at->format('d-m-Y') }}</td>
                                        <td>{{ $order->amount }}</td>
                                        {{-- <td>{{ $order->discount }}</td> --}}
                                        {{-- <td>{{ $order->net_amount }}</td> --}}
                                        <!-- <td>{{ number_format($order->paid_amount, 2) }}</td> -->
                                        <!-- <td>{{ number_format($order->due_amount, 2) }}</td> -->
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-primary delete-order"
                                                    data-id="{{ $order->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#deleteRecordModal">
                                                    Delete
                                                </button>

                                                <a href="{{ route('orders.invoice', $order->id) }}" target="_blank"
                                                    class="btn btn-primary btn-sm">
                                                    Download Invoice
                                                </a>

                                                {{-- @if ($order->due_amount > 0)
                                                    <button class="btn btn-primary btn-sm"
                                                        onclick="openPaymentModal({{ $order->id }}, {{ $order->due_amount }})">
                                                        Make Payment
                                                    </button>
                                                @endif --}}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No invoices found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-light">
                               
                            </tfoot>
                        </table>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $orders->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->

    <!-- Payment Modal -->
    {{-- <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Make Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('payments.store') }}" method="POST" onsubmit="return validatePaymentAmount();">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="patient_id" id="payment_patient_id" value="{{ $patient->id }}">
                        <input type="hidden" name="order_id" id="payment_order_id">

                        <div class="mb-3">
                            <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" id="payment_date" class="form-control"
                                value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="text" name="amount" id="payment_amount" class="form-control" maxlength="10"
                                required oninput="this.value=this.value.replace(/[^0-9.]/g,'')">
                            <small class="text-danger" id="paymentError"></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Payment Mode <span class="text-danger">*</span></label>
                            <select name="mode" id="payment_mode" class="form-select" required>
                                <option value="Cash">Cash</option>
                                <option value="Online">Online</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Comments</label>
                            <textarea name="comments" id="payment_comments" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit Payment</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}


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
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this invoice?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-primary" id="confirmDelete">Yes, Delete It!</button>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="order_id" id="deleteid" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Modal End -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let maxDueAmount = 0; // Store due amount globally

        function openPaymentModal(orderId, dueAmount) {
            maxDueAmount = dueAmount; // Store due amount for validation

            document.getElementById('payment_order_id').value = orderId;
            document.getElementById('payment_amount').value = dueAmount || 0;
            document.getElementById('payment_date').value = new Date().toISOString().split('T')[0]; // Autofill today's date

            document.getElementById('paymentError').textContent = ''; // Clear error message

            let paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
            paymentModal.show();
        }

        function openGlobalPaymentModal() {
            // clear previous selection
            const picker = document.getElementById('payment_order_picker');
            picker.selectedIndex = 0;

            document.getElementById('payment_amount').value = '';
            document.getElementById('payment_order_id').value = '';
            document.getElementById('paymentError').textContent = '';
            document.getElementById('payment_date').value = new Date().toISOString().split('T')[0];

            // Show the order picker so user can select which invoice to pay
            document.getElementById('orderPickerRow').style.display = 'block';

            new bootstrap.Modal(document.getElementById('paymentModal')).show();
        }

        // When user picks an order in the global modal, prefill due
        document.getElementById('payment_order_picker')?.addEventListener('change', function() {
            const sel = this.options[this.selectedIndex];
            const due = parseFloat(sel.getAttribute('data-due')) || 0;
            const oid = sel.value;

            maxDueAmount = due;
            document.getElementById('payment_order_id').value = oid;
            document.getElementById('payment_amount').value = due.toFixed(2);
            document.getElementById('paymentError').textContent = '';
        });

        // Validate before submitting the form
        function validatePaymentAmount() {
            const entered = parseFloat(document.getElementById('payment_amount').value) || 0;
            if (entered > maxDueAmount + 0.0001) {
                document.getElementById('paymentError').textContent =
                    'Payment amount cannot exceed the due amount of ' + maxDueAmount.toFixed(2);
                return false;
            }
            if (!document.getElementById('payment_order_id').value) {
                document.getElementById('paymentError').textContent = 'Please select an invoice.';
                return false;
            }
            return true;
        }

        $(document).ready(function() {
            $(".delete-order").on("click", function() {
                let id = $(this).data("id");
                $("#deleteid").val(id);
            });

            // Confirm Delete Button Click
            $("#confirmDelete").on("click", function() {
                let id = $("#deleteid").val();
                let actionUrl = "{{ route('orders.destroy', ':id') }}".replace(':id', id);
                $("#deleteForm").attr("action", actionUrl);

                // Explicitly submit the form
                $("#deleteForm").submit();
            });
        });
    </script>
    <script>
        document.getElementById("payment_date").addEventListener("input", function() {
            let input = this.value;
            let parts = input.split("-");
            if (parts[0] && parts[0].length > 4) {
                parts[0] = parts[0].slice(0, 4); // Restrict the year to 4 digits
                this.value = parts.join("-");
            }
        });
    </script>

@endsection
