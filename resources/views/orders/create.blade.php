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
                        <form action="{{ route('orders.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                            <!-- First Row: Invoice No, Date, Patient Name -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label>Invoice No</label>
                                    <input type="text" name="invoice_no" class="form-control"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                        value="{{ str_pad($invoice_no, 4, '0', STR_PAD_LEFT) }}">
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
                                            @if ($treatment->is_billed == 1)
                                                {{-- Only show treatments that are NOT billed --}}
                                                <option value="{{ $treatment->item_id }}"
                                                    data-treatment-name="{{ $treatment->treatment_name }}"
                                                    data-patienttreatment-id="{{ $treatment->patient_treatment_id }}"
                                                    data-tooth-selection="{{ $treatment->tooth_selection }}"
                                                    data-rate="{{ $treatment->treatment_rate }}"
                                                    data-qty="{{ $treatment->treatment_qty }}"
                                                    data-amount="{{ $treatment->treatment_amount }}">
                                                    {{ $treatment->treatment_name }} - (Tooth:
                                                    {{ $treatment->tooth_selection }})
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2 text-end">
                                    <button type="button" id="addToInvoice" class="btn btn-primary w-100">Add to
                                        Invoice</button>
                                </div>
                            </div>

                            <!-- Treatment List View (Dynamic Table) -->
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered" id="invoiceTable">
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
                                    <a href="{{ route('orders.index', $patient->id) }}" class="btn btn-primary">Cancel</a>
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
            // Initialize Select2
            if (window.jQuery && jQuery.fn.select2) {
                jQuery("#patient_treatment_select").select2({
                    placeholder: "Select Treatment & Tooth",
                    allowClear: true
                });
            }

            // Add to Invoice Button
            document.getElementById('addToInvoice').addEventListener('click', function() {
                let selectedOptions = document.querySelectorAll('#patient_treatment_select option:checked');
                let invoiceTable = document.querySelector('#invoiceTable tbody');

                selectedOptions.forEach(option => {
                    let treatmentId = option.value;
                    let patienttreatmentId = option.dataset.patienttreatmentId;
                    let treatmentName = option.dataset.treatmentName;
                    let toothSelection = option.dataset.toothSelection;
                    let rate = parseFloat(option.dataset.rate) || 0;
                    let qty = parseInt(option.dataset.qty) || 0;
                    let amount = rate * qty;

                    // Prevent duplicates
                    if (document.getElementById('row-' + treatmentId)) {
                        alert("This treatment is already added to the invoice.");
                        return;
                    }

                    // Append new row
                    let row = `
                <tr id="row-${treatmentId}">
                    <td>
                        <input type="hidden" name="patient_treatment_id[]" value="${patienttreatmentId}">
                        ${treatmentName}
                    </td>
                  
                        <input type="hidden" name="treatment_id[]" value="${treatmentId}">
                       
                  
                    <td>${toothSelection}</td>
                    <td><input type="number" name="rate[]" value="${rate}" class="form-control rate"></td>
                    <td><input type="number" name="qty[]" value="${qty}" class="form-control qty"></td>
                    <td><input type="text" name="amount[]" value="${amount.toFixed(2)}" class="form-control amount" readonly></td>
                    <td><button type="button" class="btn btn-primary btn-sm remove-row">X</button></td>
                </tr>
            `;
                    invoiceTable.insertAdjacentHTML('beforeend', row);
                });

                // Reset dropdown
                jQuery("#patient_treatment_select").val(null).trigger("change");

                // Update totals
                updateTotals();
            });

            // Update row amount
            function updateRowAmount(row) {
                const rate = parseFloat(row.querySelector('.rate')?.value) || 0;
                const qty = parseFloat(row.querySelector('.qty')?.value) || 0;
                const amt = rate * qty;
                const amtInput = row.querySelector('.amount');
                if (amtInput) amtInput.value = amt.toFixed(2);
            }

            // Update total
            function updateTotals() {
                let totalAmount = 0;
                document.querySelectorAll('#invoiceTable tbody tr').forEach(row => {
                    updateRowAmount(row); // recalc each row
                    totalAmount += parseFloat(row.querySelector('.amount').value) || 0;
                });
                document.getElementById('totalAmount').innerText = totalAmount.toFixed(2);
            }

            // When rate or qty changes
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('rate') || e.target.classList.contains('qty')) {
                    const row = e.target.closest('tr');
                    if (row) {
                        updateRowAmount(row);
                        updateTotals();
                    }
                }
            });

            // Remove row
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row')) {
                    e.target.closest('tr').remove();
                    updateTotals();
                }
            });

            // Validate submit
            document.querySelector("form").addEventListener("submit", function(event) {
                if (document.querySelectorAll("#invoiceTable tbody tr").length === 0) {
                    event.preventDefault();
                    alert("Please add at least one treatment to the invoice before submitting.");
                }
            });
        });
    </script>


@endsection
