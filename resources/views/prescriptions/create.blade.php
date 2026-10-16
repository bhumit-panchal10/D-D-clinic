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
                        <h5 class="card-title">Create Prescription </h5>
                        <div class="d-flex gap-5 align-items-center">

                            <span>Drug / Dental Material Allergy :
                                {{ $patient->allergy ?? 'No known allergies' }}</span>
                            <span>Weight :
                                {{ $patient->weight ?? '' }}</span>
                        </div>
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
                                <div class="col-md-4">
                                    <label>Special Instruction</label>
                                    <textarea type="text" name="strSpecialInstruction" placeholder="Enter Special Instruction" class="form-control" value="{{ $patient->strSpecialInstruction }}">{{ old('strSpecialInstruction',$patient->strSpecialInstruction ?? '') }}</textarea>
                                </div>
                                
                            </div>

                            <!-- Quick Prescription Template -->
                            <!-- Prescription Template -->
                            <div class="row mb-3 align-items-end">
                                <div class="col-md-4">
                                    <label>Prescription Template</label>

                                    <select id="prescriptionTemplate" class="form-control select2"
                                        data-items-url="{{ route('prescription-templates.items', ['template' => '__TEMPLATE_ID__']) }}">
                                        <option value="">Select Prescription Template</option>

                                        @foreach ($prescriptionTemplates as $template)
                                            <option value="{{ $template->id }}">
                                                {{ $template->template_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <button type="button" id="addPrescriptionTemplate" class="btn btn-primary w-100">
                                        Add Template
                                    </button>
                                </div>

                                <div class="col-md-5">
                                    <small class="text-muted">
                                        Select a template and click Add Template to add all medicines.
                                    </small>
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
        let currentComment = '';

        document.addEventListener('DOMContentLoaded', function() {
            initializeSelect2();

            const addTemplateButton =
                document.getElementById('addPrescriptionTemplate');

            addTemplateButton.addEventListener('click', async function() {
                const templateSelect =
                    document.getElementById('prescriptionTemplate');

                const templateId = templateSelect.value;

                if (!templateId) {
                    alert('Please select prescription template.');
                    return;
                }

                let url = templateSelect.dataset.itemsUrl;

                url = url.replace(
                    '__TEMPLATE_ID__',
                    templateId
                );

                try {
                    addTemplateButton.disabled = true;
                    addTemplateButton.innerText = 'Adding...';

                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        throw new Error(
                            data.message ||
                            'Unable to load prescription template.'
                        );
                    }

                    if (!Array.isArray(data.items) || data.items.length === 0) {
                        alert('No medicines found in this template.');
                        return;
                    }

                    let addedCount = 0;
                    let skippedCount = 0;

                    data.items.forEach(function(item) {
                        const added = appendPrescriptionRow({
                            medicineId: item.medicine_id,
                            medicineName: item.medicine_name,
                            dosageId: item.dosage_id,
                            dosageText: item.dosage_text,
                            days: item.days,
                            qty: item.qty,
                            comment: item.comment
                        });

                        if (added) {
                            addedCount++;
                        } else {
                            skippedCount++;
                        }
                    });

                    if (addedCount > 0 && skippedCount === 0) {
                        alert(
                            addedCount +
                            ' medicine(s) added successfully.'
                        );
                    }

                    if (skippedCount > 0) {
                        alert(
                            addedCount + ' medicine(s) added and ' +
                            skippedCount + ' duplicate medicine(s) skipped.'
                        );
                    }

                    $('#prescriptionTemplate')
                        .val(null)
                        .trigger('change');

                } catch (error) {
                    console.error('Template error:', error);
                    alert(error.message);
                } finally {
                    addTemplateButton.disabled = false;
                    addTemplateButton.innerText = 'Add Template';
                }
            });

            const templateSelect =
                document.getElementById('prescriptionTemplate');

            const addButton =
                document.getElementById('addToPrescription');

            /*
            |--------------------------------------------------------------------------
            | Add all medicines from template
            |--------------------------------------------------------------------------
            */
            templateSelect.addEventListener('change', async function() {
                const templateId = this.value;

                if (!templateId) {
                    return;
                }

                let url = this.dataset.itemsUrl;

                url = url.replace(
                    '__TEMPLATE_ID__',
                    templateId
                );

                try {
                    this.disabled = true;

                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        throw new Error(
                            data.message ||
                            'Unable to load prescription template.'
                        );
                    }

                    if (!data.items || data.items.length === 0) {
                        alert('No medicines found in this template.');
                        return;
                    }

                    let addedCount = 0;
                    let skippedCount = 0;

                    data.items.forEach(function(item) {
                        const added = appendPrescriptionRow({
                            medicineId: item.medicine_id,
                            medicineName: item.medicine_name,
                            dosageId: item.dosage_id,
                            dosageText: item.dosage_text,
                            days: item.days,
                            qty: item.qty,
                            comment: item.comment
                        });

                        if (added) {
                            addedCount++;
                        } else {
                            skippedCount++;
                        }
                    });

                    if (skippedCount > 0) {
                        alert(
                            addedCount + ' medicine(s) added. ' +
                            skippedCount + ' duplicate medicine(s) skipped.'
                        );
                    }

                    $('#prescriptionTemplate')
                        .val(null)
                        .trigger('change.select2');

                } catch (error) {
                    console.error(error);
                    alert(error.message);
                } finally {
                    this.disabled = false;
                }
            });

            /*
            |--------------------------------------------------------------------------
            | Add single medicine manually
            |--------------------------------------------------------------------------
            */
            addButton.addEventListener('click', function() {
                const medicineSelect =
                    document.getElementById('medicines');

                const dosageSelect =
                    document.getElementById('dosages');

                const daysInput =
                    document.getElementById('Days');

                const medicineId = medicineSelect.value;

                const medicineName =
                    medicineSelect.options[
                        medicineSelect.selectedIndex
                    ]?.textContent.trim() || '';

                const dosageId = dosageSelect.value;

                const dosageText =
                    dosageSelect.options[
                        dosageSelect.selectedIndex
                    ]?.textContent.trim() || '';

                const days = parseInt(daysInput.value, 10);

                if (!medicineId) {
                    alert('Please select medicine.');
                    return;
                }

                if (!dosageId) {
                    alert('Please select dosage.');
                    return;
                }

                if (!days || days < 1) {
                    alert('Please enter valid days.');
                    return;
                }

                const qty = calculateMedicineQty(
                    dosageText,
                    days
                );

                const added = appendPrescriptionRow({
                    medicineId: medicineId,
                    medicineName: medicineName,
                    dosageId: dosageId,
                    dosageText: dosageText,
                    days: days,
                    qty: qty,
                    comment: currentComment
                });

                if (!added) {
                    alert(
                        'This medicine, dosage and days combination is already added.'
                    );

                    return;
                }

                resetManualFields();
            });

            /*
            |--------------------------------------------------------------------------
            | Remove medicine row
            |--------------------------------------------------------------------------
            */
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-row')) {
                    event.target.closest('tr').remove();
                }
            });

            /*
            |--------------------------------------------------------------------------
            | Validate prescription before submit
            |--------------------------------------------------------------------------
            */
            document
                .querySelector('form')
                .addEventListener('submit', function(event) {
                    const rows = document.querySelectorAll(
                        '#prescriptionTable tbody tr'
                    );

                    if (rows.length === 0) {
                        event.preventDefault();

                        alert(
                            'Please add at least one medicine before saving.'
                        );
                    }
                });
        });

        function initializeSelect2() {
            if (!window.jQuery || !jQuery.fn.select2) {
                return;
            }

            $('#prescriptionTemplate').select2({
                placeholder: 'Select Prescription Template',
                allowClear: true,
                width: '100%'
            });

            $('#medicines').select2({
                placeholder: 'Select Medicine',
                allowClear: true,
                width: '100%'
            });

            $('#dosages').select2({
                placeholder: 'Select Dosage',
                allowClear: true,
                width: '100%'
            });
        }

        function appendPrescriptionRow(item) {
            if (
                prescriptionItemExists(
                    item.medicineId,
                    item.dosageId,
                    item.days
                )
            ) {
                return false;
            }

            let qty = item.qty;

            if (
                qty === null ||
                qty === undefined ||
                qty === ''
            ) {
                qty = calculateMedicineQty(
                    item.dosageText,
                    item.days
                );
            }

            const row = `
            <tr>
                <td>
                    <input
                        type="hidden"
                        name="medicine_id[]"
                        value="${item.medicineId}"
                    >

                    ${escapeHtml(item.medicineName)}
                </td>

                <td>
                    <input
                        type="hidden"
                        name="dosage_id[]"
                        value="${item.dosageId}"
                    >

                    ${escapeHtml(item.dosageText)}
                </td>

                <td>
                    <input
                        type="number"
                        class="form-control"
                        value="${escapeHtml(item.days)}"
                        disabled
                    >

                    <input
                        type="hidden"
                        name="days[]"
                        value="${escapeHtml(item.days)}"
                    >
                </td>

                <td>
                    <input
                        type="number"
                        class="form-control"
                        value="${escapeHtml(qty)}"
                        disabled
                    >

                    <input
                        type="hidden"
                        name="qtys[]"
                        value="${escapeHtml(qty)}"
                    >
                </td>

                <td>
                    <input
                        type="text"
                        name="comments[]"
                        class="form-control"
                        value="${escapeHtml(item.comment || '')}"
                        placeholder="Enter comment"
                    >
                </td>

                <td>
                    <button
                        type="button"
                        class="btn btn-primary btn-sm remove-row"
                    >
                        Cancel
                    </button>
                </td>
            </tr>
        `;

            document
                .querySelector('#prescriptionTable tbody')
                .insertAdjacentHTML('beforeend', row);

            return true;
        }

        function prescriptionItemExists(
            medicineId,
            dosageId,
            days
        ) {
            const rows = document.querySelectorAll(
                '#prescriptionTable tbody tr'
            );

            return Array.from(rows).some(function(row) {
                const existingMedicineId = row.querySelector(
                    'input[name="medicine_id[]"]'
                )?.value;

                const existingDosageId = row.querySelector(
                    'input[name="dosage_id[]"]'
                )?.value;

                const existingDays = row.querySelector(
                    'input[name="days[]"]'
                )?.value;

                return (
                    String(existingMedicineId) ===
                    String(medicineId) &&
                    String(existingDosageId) ===
                    String(dosageId) &&
                    String(existingDays) ===
                    String(days)
                );
            });
        }

        function calculateMedicineQty(dosageText, days) {
            if (!dosageText || !days) {
                return '';
            }

            const totalPerDay = String(dosageText)
                .split('-')
                .map(parseDosePart)
                .reduce(function(total, value) {
                    return total + value;
                }, 0);

            return Math.ceil(
                totalPerDay * Number(days)
            );
        }

        function parseDosePart(value) {
            value = String(value).trim();

            if (value.includes('/')) {
                const parts = value.split('/');

                const numerator = Number(parts[0]);
                const denominator = Number(parts[1]);

                if (denominator) {
                    return numerator / denominator;
                }
            }

            return Number(value) || 0;
        }

        function getDosages() {
            const medicineId =
                document.getElementById('medicines').value;

            const dosageSelect =
                document.getElementById('dosages');

            const daysInput =
                document.getElementById('Days');

            if (!medicineId) {
                currentComment = '';
                daysInput.value = '';

                dosageSelect.innerHTML =
                    '<option value="">Select Dosage</option>';

                $('#dosages').val(null).trigger('change');

                return;
            }

            let url =
                "{{ route('prescriptions.get_dosages', ':id') }}";

            url = url.replace(':id', medicineId);

            fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error(
                            'Unable to load dosage.'
                        );
                    }

                    return response.json();
                })
                .then(function(data) {
                    currentComment = data.comment || '';

                    daysInput.value = data.days || 1;

                    dosageSelect.innerHTML =
                        '<option value="">Select Dosage</option>';

                    if (
                        data.dosages &&
                        Array.isArray(data.dosages)
                    ) {
                        data.dosages.forEach(function(dosage) {
                            const option =
                                document.createElement('option');

                            option.value = dosage.id;
                            option.textContent = dosage.dosage;

                            if (
                                String(dosage.id) ===
                                String(data.selected_dosage_id)
                            ) {
                                option.selected = true;
                            }

                            dosageSelect.appendChild(option);
                        });
                    }

                    $('#dosages').trigger('change');
                })
                .catch(function(error) {
                    console.error(error);
                    alert(error.message);
                });
        }

        function resetManualFields() {
            currentComment = '';

            $('#medicines')
                .val(null)
                .trigger('change.select2');

            $('#dosages')
                .val(null)
                .trigger('change.select2');

            document.getElementById('Days').value = '';
        }

        function escapeHtml(value) {
            return String(value ?? '').replace(
                /[&<>"']/g,
                function(character) {
                    const map = {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    };

                    return map[character];
                }
            );
        }
    </script>

    {{-- <script>
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
    </script> --}}

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
