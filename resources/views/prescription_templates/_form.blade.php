@php
    $isEdit = isset($template);

    $initialItems = [];

    if (old('medicine_id')) {
        foreach (old('medicine_id', []) as $index => $medicineId) {
            $medicine = $medicines->firstWhere('id', (int) $medicineId);

            $dosageId = old("dosage_id.$index");
            $dosage = $dosages->firstWhere('id', (int) $dosageId);

            $initialItems[] = [
                'medicine_id' => $medicineId,
                'medicine_name' => optional($medicine)->medicine_name,
                'dosage_id' => $dosageId,
                'dosage_text' => optional($dosage)->dosage,
                'days' => old("days.$index"),
                'qty' => old("qtys.$index"),
                'comment' => old("comments.$index"),
            ];
        }
    } elseif ($isEdit) {
        foreach ($template->items as $item) {
            $initialItems[] = [
                'medicine_id' => $item->medicine_id,
                'medicine_name' => optional($item->medicine)->medicine_name,
                'dosage_id' => $item->dosage_id,
                'dosage_text' => optional($item->dosage)->dosage,
                'days' => $item->days,
                'qty' => $item->medicine_qty,
                'comment' => $item->comments,
            ];
        }
    }
@endphp

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            {{ $isEdit ? 'Edit Template' : 'Add Template' }}
        </h5>
    </div>

    <div class="card-body">

        <form id="prescriptionTemplateForm"
            action="{{ $isEdit ? route('prescription-templates.update', $template->id) : route('prescription-templates.store') }}"
            method="POST">
            @csrf

            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="row mb-4">
                <div class="col-md-6">
                    <label>
                        Template Name
                        <span class="text-danger">*</span>
                    </label>

                    <input type="text" name="template_name" class="form-control"
                        value="{{ old('template_name', $template->template_name ?? '') }}"
                        placeholder="Enter Template Name" required>

                    @error('template_name')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="d-block">Status</label>

                    <input type="hidden" name="is_active" value="0">

                    <div class="form-check mt-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input"
                            {{ old('is_active', isset($template) ? $template->is_active : true) ? 'checked' : '' }}>

                        <label class="form-check-label" for="is_active">
                            Active
                        </label>
                    </div>
                </div>
            </div>

            <hr>

            <h5 class="mb-3">Add Medicines</h5>

            <div class="row align-items-end mb-3">
                <div class="col-md-3">
                    <label>Medicine</label>

                    <select id="templateMedicine" class="form-control select2">
                        <option value="">Select Medicine</option>

                        @foreach ($medicines as $medicine)
                            <option value="{{ $medicine->id }}" data-dosage-id="{{ $medicine->dosage_id }}"
                                data-days="{{ $medicine->days }}" data-comment="{{ $medicine->comment }}">
                                {{ $medicine->medicine_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label>Dosage</label>

                    <select id="templateDosage" class="form-control select2">
                        <option value="">Select Dosage</option>

                        @foreach ($dosages as $dosage)
                            <option value="{{ $dosage->id }}">
                                {{ $dosage->dosage }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1">
                    <label>Days</label>

                    <input type="number" id="templateDays" class="form-control" min="1">
                </div>

                <div class="col-md-1">
                    <label>Qty</label>

                    <input type="number" id="templateQty" class="form-control" min="0" step="0.01">
                </div>

                <div class="col-md-3">
                    <label>Comment</label>

                    <input type="text" id="templateComment" class="form-control" placeholder="Enter Comment">
                </div>

                <div class="col-md-2">
                    <button type="button" id="addTemplateMedicine" class="btn btn-primary w-100">
                        Add
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="templateMedicineTable">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Dosage</th>
                            <th width="120">Days</th>
                            <th width="120">Qty</th>
                            <th>Comment</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>

                    <tbody id="templateItemsBody">
                        @foreach ($initialItems as $item)
                            <tr data-medicine-id="{{ $item['medicine_id'] }}"
                                data-dosage-id="{{ $item['dosage_id'] }}">
                                <td>
                                    {{ $item['medicine_name'] }}

                                    <input type="hidden" name="medicine_id[]" value="{{ $item['medicine_id'] }}">
                                </td>

                                <td>
                                    {{ $item['dosage_text'] }}

                                    <input type="hidden" name="dosage_id[]" value="{{ $item['dosage_id'] }}">
                                </td>

                                <td>
                                    <input type="number" name="days[]" class="form-control"
                                        value="{{ $item['days'] }}" min="1">
                                </td>

                                <td>
                                    <input type="number" name="qtys[]" class="form-control"
                                        value="{{ $item['qty'] }}" min="0" step="0.01">
                                </td>

                                <td>
                                    <input type="text" name="comments[]" class="form-control"
                                        value="{{ $item['comment'] }}">
                                </td>

                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-template-row">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @error('medicine_id')
                <small class="text-danger d-block mb-3">
                    Please add at least one medicine.
                </small>
            @enderror

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    Save
                </button>

                <a href="{{ route('prescription-templates.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const medicineSelect = document.getElementById('templateMedicine');
        const dosageSelect = document.getElementById('templateDosage');
        const daysInput = document.getElementById('templateDays');
        const qtyInput = document.getElementById('templateQty');
        const commentInput = document.getElementById('templateComment');
        const addButton = document.getElementById('addTemplateMedicine');
        const tableBody = document.getElementById('templateItemsBody');

        if (window.jQuery && jQuery.fn.select2) {
            $('#templateMedicine').select2({
                placeholder: 'Select Medicine',
                allowClear: true,
                width: '100%'
            });

            $('#templateDosage').select2({
                placeholder: 'Select Dosage',
                allowClear: true,
                width: '100%'
            });
        }

        medicineSelect.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];

            if (!option || !option.value) {
                dosageSelect.value = '';
                daysInput.value = '';
                qtyInput.value = '';
                commentInput.value = '';

                $('#templateDosage').trigger('change');

                return;
            }

            const defaultDosageId = option.dataset.dosageId || '';
            const defaultDays = option.dataset.days || '';
            const defaultComment = option.dataset.comment || '';

            dosageSelect.value = defaultDosageId;
            daysInput.value = defaultDays;
            commentInput.value = defaultComment;

            $('#templateDosage').trigger('change');

            calculateQty();
        });

        dosageSelect.addEventListener('change', calculateQty);
        daysInput.addEventListener('input', calculateQty);

        addButton.addEventListener('click', function() {
            const medicineId = medicineSelect.value;
            const dosageId = dosageSelect.value;
            const days = daysInput.value;
            const qty = qtyInput.value;
            const comment = commentInput.value;

            const medicineText =
                medicineSelect.options[medicineSelect.selectedIndex]
                ?.textContent.trim() || '';

            const dosageText =
                dosageSelect.options[dosageSelect.selectedIndex]
                ?.textContent.trim() || '';

            if (!medicineId) {
                alert('Please select medicine.');
                return;
            }

            if (!dosageId) {
                alert('Please select dosage.');
                return;
            }

            if (!days || Number(days) < 1) {
                alert('Please enter valid days.');
                return;
            }

            if (isDuplicate(medicineId, dosageId)) {
                alert('This medicine and dosage are already added.');
                return;
            }

            const row = `
                <tr
                    data-medicine-id="${medicineId}"
                    data-dosage-id="${dosageId}"
                >
                    <td>
                        ${escapeHtml(medicineText)}

                        <input
                            type="hidden"
                            name="medicine_id[]"
                            value="${medicineId}"
                        >
                    </td>

                    <td>
                        ${escapeHtml(dosageText)}

                        <input
                            type="hidden"
                            name="dosage_id[]"
                            value="${dosageId}"
                        >
                    </td>

                    <td>
                        <input
                            type="number"
                            name="days[]"
                            class="form-control"
                            value="${escapeHtml(days)}"
                            min="1"
                        >
                    </td>

                    <td>
                        <input
                            type="number"
                            name="qtys[]"
                            class="form-control"
                            value="${escapeHtml(qty)}"
                            min="0"
                            step="0.01"
                        >
                    </td>

                    <td>
                        <input
                            type="text"
                            name="comments[]"
                            class="form-control"
                            value="${escapeHtml(comment)}"
                        >
                    </td>

                    <td>
                        <button
                            type="button"
                            class="btn btn-danger btn-sm remove-template-row"
                        >
                            Remove
                        </button>
                    </td>
                </tr>
            `;

            tableBody.insertAdjacentHTML('beforeend', row);

            resetMedicineFields();
        });

        document.addEventListener('click', function(event) {
            if (
                event.target.classList.contains(
                    'remove-template-row'
                )
            ) {
                event.target.closest('tr').remove();
            }
        });

        document
            .getElementById('prescriptionTemplateForm')
            .addEventListener('submit', function(event) {
                const rows = tableBody.querySelectorAll('tr');

                if (rows.length === 0) {
                    event.preventDefault();

                    alert(
                        'Please add at least one medicine to the template.'
                    );
                }
            });

        function calculateQty() {
            const dosageText =
                dosageSelect.options[dosageSelect.selectedIndex]
                ?.textContent.trim() || '';

            const days = Number(daysInput.value || 0);

            if (!dosageText || !days) {
                qtyInput.value = '';
                return;
            }

            const totalPerDay = dosageText
                .split('-')
                .map(parseDosePart)
                .reduce(function(total, value) {
                    return total + value;
                }, 0);

            qtyInput.value = Math.ceil(totalPerDay * days);
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

        function isDuplicate(medicineId, dosageId) {
            const rows = tableBody.querySelectorAll('tr');

            return Array.from(rows).some(function(row) {
                return (
                    String(row.dataset.medicineId) ===
                    String(medicineId) &&
                    String(row.dataset.dosageId) ===
                    String(dosageId)
                );
            });
        }

        function resetMedicineFields() {
            medicineSelect.value = '';
            dosageSelect.value = '';
            daysInput.value = '';
            qtyInput.value = '';
            commentInput.value = '';

            $('#templateMedicine').val(null).trigger('change');
            $('#templateDosage').val(null).trigger('change');
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
    });
</script>
