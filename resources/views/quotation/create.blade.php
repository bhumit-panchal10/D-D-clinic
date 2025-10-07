@extends('layouts.app')
@section('title', 'Create Invoice')
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

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title">Create Invoice</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('quotation.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                            <!-- First Row: Invoice No, Date, Patient Name -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label>Qutation No</label>
                                    <input type="text" name="quotation_no" class="form-control"
                                        value="{{ $quotation_no }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label>Date</label>
                                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}"
                                        readonly>
                                </div>
                                <div class="col-md-4">
                                    <label>Patient Name</label>
                                    <input type="text" class="form-control" value="{{ $patient->name }}" readonly>
                                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                </div>
                            </div>

                            <!-- Second Row: Treatment Selection -->
                            <div class="row mb-3 align-items-end">
                                <div class="col-md-5">
                                    <label>Select Treatment & Tooth</label>
                                    <select id="patient_treatment_select" class="form-control select2" multiple>
                                        @foreach ($patientTreatments as $treatment)
                                            @if ($treatment->is_billed == 0)
                                                <option value="{{ $treatment->item_id }}"
                                                    data-treatment-name="{{ $treatment->treatment_name }}"
                                                    data-tooth-selection="{{ $treatment->tooth_selection }}"
                                                    data-rate="{{ $treatment->treatment_rate }}"
                                                    data-qty="{{ $treatment->treatment_qty }}"
                                                    data-amount="{{ $treatment->treatment_amount }}">
                                                    {{ $treatment->treatment->treatment_name }} - (Tooth:
                                                    {{ $treatment->tooth_selection }})
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2 text-end">
                                    <button type="button" id="addToquotation" class="btn btn-primary w-100">Add to
                                        Quotation</button>
                                </div>
                            </div>

                            <!-- Treatment List View (Dynamic Table) -->
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered" id="quotationTable">
                                    <thead>
                                        <tr>
                                            <th>Treatment Name</th>
                                            <th>Tooth</th>
                                            <th>Rate</th>
                                            <th>Qty</th>
                                            <th>Amount</th>
                                            {{-- <th>Discount</th> --}}
                                            {{-- <th>Net Amount</th> --}}
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Dynamic Data Will Be Added Here -->
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="4" class="text-end">Total:</th>
                                            <th id="totalAmount">0.00</th>
                                            {{-- <th id="totalDiscount">0.00</th> --}}
                                            {{-- <th id="totalNetAmount">0.00</th> --}}
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ route('quotation.index', $patient->id) }}"
                                        class="btn btn-primary">Cancel</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (window.jQuery) {
                console.log("✅ jQuery is loaded! Version:", jQuery.fn.jquery);

                if (jQuery.fn.select2) {
                    jQuery("#patient_treatment_select").select2({
                        placeholder: "Select Treatment & Tooth",
                        allowClear: true
                    });
                    console.log("✅ Select2 initialized successfully!");
                } else {
                    console.error("❌ Select2 is not loaded!");
                }
            } else {
                console.error("❌ jQuery is not loaded!");
            }

            // Add to Invoice Button Functionality
            document.getElementById('addToquotation').addEventListener('click', function() {
                let treatment = document.querySelector('#patient_treatment_select').value;

                let selectedOptions = document.querySelectorAll('#patient_treatment_select option:checked');
                let quotationTable = document.querySelector('#quotationTable tbody');

                if (!treatment) {
                    alert("Please select treatment.");
                    return;
                }

                selectedOptions.forEach(option => {
                    let treatmentId = option.value;
                    let treatmentName = option.dataset.treatmentName;
                    let toothSelection = option.dataset.toothSelection;
                    let rate = parseFloat(option.dataset.rate);
                    let qty = parseInt(option.dataset.qty);
                    let amount = parseFloat(option.dataset.amount);
                    let discount = 0;
                    let netAmount = amount - discount;

                    // Check if the treatment is already added
                    if (document.getElementById('row-' + treatmentId)) {
                        alert("This treatment is already added to the invoice.");
                        return;
                    }

                    // Append new row to table
                    let row = `
                <tr id="row-${treatmentId}">
                    <td>
                        <input type="hidden" name="patient_treatment_id[]" value="${treatmentId}">
                        ${treatmentName}
                    </td>
                    <td>${toothSelection}</td>
                    <td><input type="text" name="rate[]" value="${rate}" class="form-control rate-input" oninput="this.value=this.value.replace(/[^0-9.]/g,'')"></td>
                    <td><input type="text" name="qty[]" value="${qty}" class="form-control qty-input" oninput="this.value=this.value.replace(/[^0-9.]/g,'')"></td>
                    <td><input type="text" name="amount[]" value="${amount}" class="form-control amount" readonly></td>
                    <td><button type="button" class="btn btn-primary btn-sm remove-row">X</button></td>
                </tr>
            `;

                    quotationTable.insertAdjacentHTML('beforeend', row);
                });

                // Reset Dropdown Selection
                jQuery("#patient_treatment_select").val(null).trigger("change");

                // Update Totals
                updateTotals();
            });

            // Submit Validation
            document.querySelector("form").addEventListener("submit", function(event) {
                let tableRows = document.querySelectorAll("#quotationTable tbody tr");

                if (tableRows.length === 0) {
                    event.preventDefault();
                    alert("Please add at least one treatment to the invoice before submitting.");
                }
            });

            // Update Net Amount on Discount Change
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('discount-input')) {
                    let row = e.target.closest('tr');
                    let amount = parseFloat(row.querySelector('.amount').value) || 0;
                    let discount = parseFloat(e.target.value) || 0;

                    if (discount > amount) {
                        alert("Discount cannot be greater than the amount!");
                        e.target.value = amount;
                        discount = amount;
                    }

                    let netAmountField = row.querySelector('.net-amount');
                    netAmountField.value = (amount - discount).toFixed(2);

                    updateTotals();
                }
            });

            // Recalculate amount & net when rate/qty changes
            document.addEventListener('input', function(e) {
                if (!e.target.matches('.rate-input, .qty-input')) return;

                const row = e.target.closest('tr');
                const rate = parseFloat(row.querySelector('.rate-input').value) || 0;
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const discountEl = row.querySelector('.discount-input');
                const amountEl = row.querySelector('.amount');
                const netEl = row.querySelector('.net-amount');

                const amount = rate * qty;
                amountEl.value = amount.toFixed(2);

                let discount = parseFloat(discountEl.value) || 0;
                if (discount > amount) { // keep discount sane
                    discount = amount;
                    discountEl.value = amount.toFixed(2);
                }

                netEl.value = (amount - discount).toFixed(2);
                updateTotals();
            });


            // Remove Row from Table (Using Event Delegation)
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row')) {
                    e.target.closest('tr').remove();
                    updateTotals();
                }
            });

            // Function to Calculate Totals
            function updateTotals() {
                let totalAmount = 0;
                let totalDiscount = 0;
                let totalNetAmount = 0;

                document.querySelectorAll('#quotationTable tbody tr').forEach(row => {
                    let amount = parseFloat(row.querySelector('.amount').value) || 0;
                    let discount = parseFloat(row.querySelector('.discount-input').value) || 0;
                    let netAmount = parseFloat(row.querySelector('.net-amount').value) || 0;

                    totalAmount += amount;
                    totalDiscount += discount;
                    totalNetAmount += netAmount;
                });

                document.getElementById('totalAmount').innerText = totalAmount.toFixed(2);
                document.getElementById('totalDiscount').innerText = totalDiscount.toFixed(2);
                document.getElementById('totalNetAmount').innerText = totalNetAmount.toFixed(2);
            }
        });
    </script>

@endsection
