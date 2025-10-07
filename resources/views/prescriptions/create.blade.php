@extends('layouts.app')
@section('title', 'Create Prescription')
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
                @include('patient.show', ['id' => $patient->id])

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title">Create Prescription</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('prescriptions.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                            <!-- First Row: Date, Patient Name -->
                            <div class="row mb-3">
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

                            <!-- Second Row: Medicine & Dosage -->
                            <div class="row mb-3 align-items-end">
                                <div class="col-md-3">
                                    <label>Select Medicine<span class="text-danger">*</span></label>
                                    <select id="medicines" class="form-control select2" onchange="getDosages();">
                                        <option value="">Select Medicine</option>
                                        @foreach ($medicines->sortBy('medicine_name') as $medicine)
                                            <option value="{{ $medicine->id }}">{{ $medicine->medicine_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>Select Dosage<span class="text-danger">*</span></label>
                                    <select id="dosages" class="form-control select2">
                                        <option value="">Select Dosage</option>
                                        {{--  @foreach ($dosages as $dosage)
                                            <option value="{{ $dosage->id }}">{{ $dosage->dosage }}</option>
                                        @endforeach  --}}
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>Days</label>
                                    <input type="number" id="Days" name="days" class="form-control" value="">
                                </div>

                                <div class="col-md-2 text-end">
                                    <button type="button" id="addToPrescription" class="btn btn-primary w-100">Add</button>
                                </div>
                            </div>

                            <!-- Prescription List View (Dynamic Table) -->
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered" id="prescriptionTable">
                                    <thead>
                                        <tr>
                                            <th>Medicine</th>
                                            <th>Dosage</th>
                                            <th>Days</th>
                                            <th>Medicine Qty</th>
                                            <th>Comments</th> <!-- Comment field only in listing -->
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Dynamic Data Will Be Added Here -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a href="{{ route('prescriptions.index', $patient->id) }}"
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
                    jQuery("#medicines").select2({
                        placeholder: "Select Medicine",
                        allowClear: true
                    });

                    jQuery("#dosages").select2({
                        placeholder: "Select Dosage",
                        allowClear: true
                    });

                    console.log("✅ Select2 initialized successfully!");
                } else {
                    console.error("❌ Select2 is not loaded!");
                }
            } else {
                console.error("❌ jQuery is not loaded!");
            }

            // Add to Prescription Button Functionality
            document.getElementById('addToPrescription').addEventListener('click', function() {
                let medicine = document.querySelector('#medicines').value;
                let medicineText = document.querySelector('#medicines option:checked')?.textContent || '';

                let dosage = document.querySelector('#dosages').value;
                let Days = document.querySelector('#Days').value;
                Days = parseInt(Days);
                let dosageText = document.querySelector('#dosages option:checked')?.textContent || '';

                let prescriptionTable = document.querySelector('#prescriptionTable tbody');

                if (!medicine || !dosage) {
                    alert("Please select both a medicine and a dosage.");
                    return;
                }

                // Check if the same medicine and dosage already exists
                let exists = false;
                document.querySelectorAll("#prescriptionTable tbody tr").forEach(row => {
                    let existingMedicineId = row.querySelector('input[name="medicine_id[]"]').value;
                    let existingDosageId = row.querySelector('input[name="dosage_id[]"]').value;
                    let existingdays = row.querySelector('input[name="days[]"]').value;
                    if (existingMedicineId === medicine && existingDosageId === dosage &&
                        existingdays === days) {
                        exists = true;
                    }
                });
                let comment = currentComment;

                let dosageParts = dosageText.split('-').map(Number); // [1,1,1]
                let totalPerDay = dosageParts.reduce((sum, val) => sum + (isNaN(val) ? 0 : val), 0);
                let qty = Days * totalPerDay;


                if (!exists) {
                    // Append new row to table
                    let row = `
                <tr>
                    <td>
                        <input type="hidden" name="medicine_id[]" value="${medicine}">
                        ${medicineText}
                    </td>
                    <td>
                        <input type="hidden" name="dosage_id[]" value="${dosage}">
                        ${dosageText}
                    </td>
                    <td>
                        

                        <input type="number" class="form-control" value="${Days}" placeholder="Enter Days" disabled>
                        <input type="hidden" name="days[]" value="${Days}">
                       
                    </td>
                    <td>
                        

                        <input type="text" class="form-control" value="${qty}" placeholder="Enter Qtys" disabled>
                        <input type="hidden" name="qtys[]" value="${qty}">
                       
                    </td>
                    <td>
                        <input type="text" name="comments[]" class="form-control" value="${comment}" placeholder="Enter comment (optional)">
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm remove-row">Cancel</button>
                    </td>
                </tr>
            `;

                    prescriptionTable.insertAdjacentHTML('beforeend', row);
                } else {
                    alert("This medicine and dosage combination is already added.");
                }

                // Reset Dropdown Selection
                jQuery("#medicines").val(null).trigger("change");
                jQuery("#dosages").val(null).trigger("change");
                jQuery("#Days").val(null).trigger("change");
            });

            // Remove Row from Table
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-row')) {
                    e.target.closest('tr').remove();
                }
            });

            // Submit Validation
            document.querySelector("form").addEventListener("submit", function(event) {
                let tableRows = document.querySelectorAll("#prescriptionTable tbody tr");

                if (tableRows.length === 0) {
                    event.preventDefault();
                    alert("Please add at least one medicine to the prescription before submitting.");
                }
            });
        });
    </script>

    <script>
        function getDosages() {
            var medicineId = document.getElementById("medicines").value;
            var url = "{{ route('prescriptions.get_dosages', ':id') }}";
            url = url.replace(':id', medicineId);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.dosages) {
                        let comment = data.comment || '';
                        let days = data.days || '';
                        let selectedDosageId = data.selected_dosage_id || '';

                        currentComment = comment;
                        document.querySelector('input[name="days"]').value = days;

                        let dosagesSelect = document.getElementById("dosages");
                        dosagesSelect.innerHTML = '<option value="">Select Dosage</option>';

                        data.dosages.forEach(dosage => {
                            let option = document.createElement("option");
                            option.value = dosage.id;
                            option.textContent = dosage.dosage;

                            if (dosage.id == selectedDosageId) {
                                option.selected = true;
                            }

                            dosagesSelect.appendChild(option);
                        });

                        // Trigger select2 refresh (if you're using it)
                        if ($(dosagesSelect).hasClass("select2-hidden-accessible")) {
                            $(dosagesSelect).trigger('change');
                        }
                    }
                })
                .catch(error => console.error('Error fetching dosages:', error));
        }
    </script>

@endsection
