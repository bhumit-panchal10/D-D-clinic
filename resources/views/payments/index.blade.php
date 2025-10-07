@extends('layouts.app')

@section('title', 'Payments')

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

                <div class="row">
                    <!-- Add Note Section -->
                                <div class="col-lg-5">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between">
                                            <h5 class="card-title mb-0">Add Payment</h5>
                                        </div>

                                        <div class="card-body">
                               
                                <form action="{{ route('payments.store', $patient->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                    <div class="mb-3">
                                        <label>Payment Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="payment_date" name="payment_date" rows="3" required>
                                    </div>
                                     <div class="mb-3">
                                        <label for="amount" class="form-label">Amount<span class="text-danger">*</span></label>
                                        <input type="text" name="amount" id="amount" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" maxlength="10" value="" required>
                                    </div>
                                     <div class="mb-3">
                                        <label for="mode" class="form-label">Payment Mode<span class="text-danger">*</span></label>
                                        <select name="mode" id="mode" class="form-select" required>
                                            <option value="Cash">Cash</option>
                                            <option value="UPI">UPI</option>
                                            <option value="Card swipe">Card swipe</option>
                                            <option value="Cheque">Cheque</option>
                                        </select>
                                    </div>
                                      <div class="row mt-3">
                                    <div class="mb-3">
                                        <label for="comments" class="form-label">Comments</label>
                                        <textarea name="comments" id="comments" class="form-control"></textarea>
                                    </div>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                        <button type="reset" class="btn btn-primary">Clear</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Notes List Section -->
                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Payments List</h5>
                                 <div class="d-flex justify-content-between align-items-center m-3">
                                    <h5 class="mb-0">
                                        Total Amount: {{ $Totalamount }} | Paid Amount: {{ $Paidamount }}
                                        @if($Totalamount > $Paidamount)
                                        | Due Amount: {{ $Totalamount - $Paidamount }}
                                        @endif

                                    </h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Payment Date</th>
                                            <th>Amount</th>
                                            <th>Payment Mode</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payments as $key => $payment)
                                            <tr>
                                                <td class="text-center">{{ $payments->firstItem() + $key }}</td>
                                                <td>{{ date('d-m-Y',strtotime($payment->payment_date)) }}</td>
                                                <td>{{ $payment->amount }}</td>
                                                <td>{{ $payment->mode }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-primary edit-btn"
                                                       onclick="getEditData(<?= $payment->id ?>)"
                                                        data-bs-toggle="modal" data-bs-target="#editNoteModal">
                                                        Edit
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-primary delete-btn"
                                                        data-id="{{ $payment->id }}"
                                                        data-patient-id="{{ $patient->id }}" data-toggle="modal"
                                                        data-target="#deleteRecordModal">
                                                        Delete
                                                    </button>
                                                    <a href="{{ route('payments.invoice', $payment->id) }}" target="_blank"
                                                    class="btn btn-primary btn-sm">
                                                    Download Payment
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $payments->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Edit Note Modal -->
    <div class="modal fade" id="editNoteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="{{ route('payments.update') }}">
                        @csrf
                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                        <input type="hidden" name="id" id="payment_id" value="">

                        <div class="mb-3">
                            <label for="payment_date" class="form-label">Payment Date<span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" id="edit_payment_date" class="form-control" value="{{ old('payment_date') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount<span class="text-danger">*</span></label>
                            <input type="text" name="amount" id="edit_amount" class="form-control" value="{{ old('amount') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" maxlength="10" required>
                        </div>

                        <div class="mb-3">
                            <label for="mode" class="form-label">Payment Mode<span class="text-danger">*</span></label>
                            <select name="mode" id="edit_mode" class="form-select" required>
                                <option value="Cash">Cash</option>
                                <option value="UPI">UPI</option>
                                <option value="Card swipe">Card swipe</option>
                                <option value="Cheque">Cheque</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="comments" class="form-label">Comments</label>
                            <textarea name="comments" id="edit_comments" class="form-control">{{ old('comments') }}</textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
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
                        colors="primary:#f7b84b,secondary:#f06548" style="width: 100px; height: 100px">
                    </lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4>Are you Sure?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to remove this payment?</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <!-- Hidden input for the payment ID -->
                        <input type="hidden" name="id" id="deleteid" value="">
                        <button type="submit" class="btn btn-primary">Yes, Delete It!</button>
                    </form>
                    <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
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
        function getEditData(id) {
           
            var url = "{{ route('payments.edit', ':id') }}";
            url = url.replace(":id", id);
            if (id) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        id
                    },
                    success: function(data) {
                        var obj = JSON.parse(data);
                        console.log(obj);
                        $("#edit_payment_date").val(obj.payment_date);
                        $("#edit_amount").val(obj.amount);
                        $("#edit_mode").val(obj.mode);
                        $("#edit_comments").val(obj.comments);
                        $('#payment_id').val(id);
                    },
                    error: function(xhr) {
                        alert('Failed to load data');
                    }
                });
            }
        }
    </script>
    <script>
       $(document).ready(function() {
            $(".delete-btn").on("click", function() {
                let id = $(this).data("id");
                let patientId = $(this).data("patient-id");

                // Set the delete form action to the payments.destroy route
                let actionUrl = "{{ route('payments.destroy', ':id') }}".replace(':id', id);

                // Set the form action dynamically
                $("#deleteForm").attr("action", actionUrl);

                // Open the modal
                $("#deleteRecordModal").modal("show");
            });

            // Confirm Delete Button Click (optional if you want a separate confirm button, otherwise remove this part)
            $("#confirmDelete").on("click", function() {
                $("#deleteForm").submit();
            });
        });

    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
        document.getElementById('payment_date').value = today; // Set today's date as the value
    });
</script>
@endsection
