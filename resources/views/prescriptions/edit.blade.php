@extends('layouts.app')
@section('title', 'Edit Prescription')
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
                    </h5> <a href="{{ route('patient.index') }}" class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                    </a>
                </div>

                @include('common.alert')
                @include('patient.show', ['id' => $prescription->patient_id])

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Edit Prescription</h5>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('prescriptions.update', $prescription->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="table-responsive">
                                <table class="table table-bordered" id="prescriptionTable">
                                    <thead>
                                        <tr>
                                            <th>Medicine</th>
                                            <th>Dosage</th>
                                            <th>Days</th>
                                            <th>Medicine Qty</th>
                                            <th>Comments</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($prescriptionDetails as $index => $detail)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="detail_id[]" value="{{ $detail->id }}">
                                                    <select name="medicine_id[]" class="form-control">
                                                        @foreach ($medicines as $medicine)
                                                            <option value="{{ $medicine->id }}"
                                                                {{ $medicine->id == $detail->medicine_id ? 'selected' : '' }}>
                                                                {{ $medicine->medicine_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="dosage_id[]" class="form-control dosage-select"
                                                        data-dosages='@json($dosages->pluck('dosage', 'id'))'>
                                                        @foreach ($dosages as $dosage)
                                                            <option value="{{ $dosage->id }}"
                                                                {{ $dosage->id == $detail->dosage_id ? 'selected' : '' }}>
                                                                {{ $dosage->dosage }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </td>
                                                <td>
                                                    <input type="text" name="days[]" class="form-control"
                                                        value="{{ $detail->days ?? '' }}">
                                                </td>
                                                <td>
                                                    <input type="text" name="qtys[]" class="form-control"
                                                        value="{{ $detail->medicine_qty ?? '' }}">
                                                </td>
                                                <td>
                                                    <input type="text" name="comments[]" class="form-control"
                                                        value="{{ $detail->comments ?? '' }}">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-sm remove-row"
                                                        data-detail-id="{{ $detail->id }}">Cancel</button>
                                                    <input type="hidden" name="delete_ids[]" value=""
                                                        class="delete-id">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-primary" id="addRow">+ Add More</button>
                                <button type="submit" class="btn btn-primary">Update Prescription</button>
                                <a href="{{ route('prescriptions.index', $patient->id) }}"
                                    class="btn btn-primary">Cancel</a>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function calculateQty(row) {
                let dosageSelect = row.querySelector('.dosage-select');
                let dosageMap = JSON.parse(dosageSelect.getAttribute('data-dosages'));
                let dosageId = dosageSelect.value;
                let dosageString = dosageMap[dosageId] || '0-0-0';

                let dosageParts = dosageString.split('-').map(val => parseInt(val) || 0);
                let totalPerDay = dosageParts.reduce((sum, val) => sum + val, 0);

                let daysInput = row.querySelector('input[name="days[]"]');
                let days = parseInt(daysInput.value) || 0;

                let qtyInput = row.querySelector('input[name="qtys[]"]');
                qtyInput.value = totalPerDay * days;
            }

            const tableBody = document.querySelector("#prescriptionTable tbody");

            // Ensure at least one medicine is present before submitting
            document.querySelector("form").addEventListener("submit", function(event) {
                let tableRows = document.querySelectorAll(
                    "#prescriptionTable tbody tr:not([style*='display: none'])");

                if (tableRows.length === 0) {
                    event.preventDefault();
                    alert("You must add at least one medicine to the prescription.");
                }

                // Check for duplicate medicine + dosage combination
                let seenCombinations = new Set();
                let isDuplicate = false;

                tableRows.forEach(row => {
                    let medicine = row.querySelector('select[name="medicine_id[]"]').value;
                    let dosage = row.querySelector('select[name="dosage_id[]"]').value;
                    let combination = `${medicine}-${dosage}`;

                    if (seenCombinations.has(combination)) {
                        isDuplicate = true;
                    } else {
                        seenCombinations.add(combination);
                    }
                });

                if (isDuplicate) {
                    event.preventDefault();
                    alert("Duplicate medicine with the same dosage is not allowed.");
                }
            });

            // Remove row functionality (Existing Data)
            document.querySelectorAll('.remove-row').forEach(button => {
                button.addEventListener('click', function() {
                    let row = this.closest('tr');
                    let detailId = this.getAttribute('data-detail-id');

                    if (detailId) {
                        row.querySelector('.delete-id').value = detailId; // Mark for deletion
                    }
                    row.style.display = 'none'; // Hide the row
                });
            });

            // Add new row dynamically
            document.getElementById('addRow').addEventListener('click', function() {
                let newRow = `
            <tr>
                <td>
                    <input type="hidden" name="detail_id[]" value="">
                    <select name="medicine_id[]" class="form-control">
                        @foreach ($medicines as $medicine)
                            <option value="{{ $medicine->id }}">{{ $medicine->medicine_name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="dosage_id[]" class="form-control dosage-select" data-dosages='@json($dosages->pluck('dosage', 'id'))'>
                        @foreach ($dosages as $dosage)
                            <option value="{{ $dosage->id }}">{{ $dosage->dosage }}</option>
                        @endforeach
                    </select>

                </td>
                <td>
                    <input type="text" name="days[]" class="form-control" value="">
                </td>
                 <td>
                    <input type="text" name="qtys[]" class="form-control" value="">
                </td>
                <td>
                    <input type="text" name="comments[]" class="form-control" value="">
                </td>
                <td>
                    <button type="button" class="btn btn-primary btn-sm remove-row">Cancel</button>
                    <input type="hidden" name="delete_ids[]" value="" class="delete-id">
                </td>
            </tr>
        `;
                tableBody.insertAdjacentHTML('beforeend', newRow);

                let newRowElement = tableBody.lastElementChild;
                let dosageSelect = newRowElement.querySelector('.dosage-select');
                let daysInput = newRowElement.querySelector('input[name="days[]"]');

                if (dosageSelect && daysInput) {
                    dosageSelect.addEventListener('change', () => calculateQty(newRowElement));
                    daysInput.addEventListener('input', () => calculateQty(newRowElement));
                }


                // Attach remove event to new button
                document.querySelectorAll('.remove-row').forEach(button => {
                    button.addEventListener('click', function() {
                        let row = this.closest('tr');
                        row.remove();
                    });
                });
            });
        });

        document.querySelectorAll('#prescriptionTable tbody tr').forEach(row => {
            let dosageSelect = row.querySelector('.dosage-select');
            let daysInput = row.querySelector('input[name="days[]"]');

            if (dosageSelect && daysInput) {
                dosageSelect.addEventListener('change', () => calculateQty(row));
                daysInput.addEventListener('input', () => calculateQty(row));

                // Initial calculation on load
                calculateQty(row);
            }
        });
    </script>

@endsection
