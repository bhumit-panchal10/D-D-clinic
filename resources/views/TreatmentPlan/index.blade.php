@extends('layouts.app')

@section('title', 'Treatment Plan')

@section('content')
    <style>
        /* Previous styles remain the same... */

        /* New styles for list display */
        .list-display-container {
            margin-top: 20px;
            padding: 15px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }

        .field-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .field-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 15px;
            border-bottom: 1px solid #f1f1f1;
            transition: background 0.2s ease;
        }

        .field-item:hover {
            background: #f8f9fa;
        }

        .field-item:last-child {
            border-bottom: none;
        }

        .field-label {
            font-weight: 600;
            color: #495057;
            min-width: 150px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .field-label i {
            width: 20px;
            text-align: center;
        }

        .field-value {
            flex: 1;
            color: #212529;
            padding-left: 20px;
            word-break: break-word;
        }

        .field-value-empty {
            color: #6c757d;
            font-style: italic;
        }

        .teeth-list-display {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 5px;
        }

        .tooth-badge {
            display: inline-block;
            padding: 3px 8px;
            background: #e9ecef;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 500;
            border-left: 3px solid;
        }

        .tooth-badge.caries {
            border-left-color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
        }

        .tooth-badge.pain {
            border-left-color: #ffc107;
            background: rgba(255, 193, 7, 0.1);
        }

        .tooth-badge.missing {
            border-left-color: #6c757d;
            background: rgba(108, 117, 125, 0.1);
        }

        .tooth-badge.mobility {
            border-left-color: #0d6efd;
            background: rgba(13, 110, 253, 0.1);
        }

        .tooth-badge.prosthesis {
            border-left-color: #800080;
            background: rgba(128, 0, 128, 0.1);
        }

        .no-data {
            color: #6c757d;
            font-style: italic;
        }

        .exam-date-badge {
            background: #0d6efd;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .list-toggle-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .list-toggle-btn:hover {
            background: #218838;
        }

        /* Print specific styles */
        @media print {

            .list-toggle-btn,
            .btn {
                display: none !important;
            }

            .list-display-container {
                box-shadow: none;
                border: 1px solid #dee2e6;
                page-break-inside: avoid;
            }

            .field-item {
                page-break-inside: avoid;
            }
        }
    </style>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <!-- Patient Header -->
                <div class="d-flex justify-content-between align-items-center m-3">
                    <h5 class="mb-0">
                        Name: {{ $patient->name }} | Mobile: {{ $patient->mobile1 }}
                        @if ($patient->mobile2 != '')
                            | Mobile 2: {{ $patient->mobile2 }}
                        @endif
                        | Case No: {{ $patient->case_no }}
                    </h5>
                    <a href="{{ route('patient.index') }}" class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                    </a>
                </div>

                {{-- Alert Messages --}}
                @include('common.alert')
                @include('patient.show', ['id' => $patient->id])
                @include('patient_treatments.Submenu', ['id' => $patient->id])
                {{-- @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif --}}

                <!-- Date Selector -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('TreatmentPlan.index', $patient->id) }}" id="dateForm">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <label for="date" class="form-label">Treatment Date</label>

                                    <select name="date" id="date" class="form-control">
                                        <option value="">-- Select Date --</option>

                                        @foreach ($examinations->unique('date') as $exam)
                                            <option value="{{ $exam->date }}"
                                                {{ request('date') == $exam->date ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::parse($exam->date)->format('d-m-Y') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary w-100">
                                        Search
                                    </button>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <a href="{{ route('TreatmentPlan.index', $patient->id) }}"
                                        class="btn btn-secondary w-100">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- Main Form (Existing form remains the same) -->
                {{-- <input type="hidden" name="exam_date" value="{{ $selectedDate ?? '' }}"> --}}

                <div class="card mb-3 d-flex justify-content-center">
                    <div class="card-body ">
                        <label class="me-2"><strong>Teeth Type:</strong></label>
                        <button type="button" class="btn btn-outline-primary teeth-toggle-btn active" id="adultBtn"
                            onclick="toggleTeethType('adult')">
                            <i class="fas fa-user"></i> Adult Teeth
                        </button>
                        <button type="button" class="btn btn-outline-primary teeth-toggle-btn" id="childBtn"
                            onclick="toggleTeethType('child')">
                            <i class="fas fa-child"></i> Child Teeth
                        </button>
                    </div>
                    <div class="text-end">
                        <a href="{{ route('TreatmentPlan.add', $patient->id) }}"
                            class="btn btn-outline-primary teeth-toggle-btn active">
                            <i class="fas fa-plus"></i> Add
                        </a>
                    </div>
                </div>

                <div class="row">
                    <!-- LEFT COLUMN: Teeth Charts -->
                    @if (request()->filled('date') && isset($examination))
                        {{-- ================= RCT / IPC ================= --}}
                        <div class="col-lg-12">
                            <div class="card teeth-section mb-3">
                                <div class="chart-title bg-danger text-white">
                                    <i class="fas fa-tooth me-2"></i>RCT/IPC
                                </div>
                                <div class="card-body">

                                    @include('IntraoralExamination.partials.teeth-chart', [
                                        'section' => 'rct',
                                        'selectedTeeth' => $examination->RCT_IPC
                                            ? explode(',', $examination->RCT_IPC)
                                            : [],
                                    ])

                                    <input type="hidden" name="RCT_IPC" id="rct_teeth"
                                        value="{{ $examination->RCT_IPC ?? '' }}">
                                </div>
                            </div>
                        </div>

                        {{-- ================= Extraction ================= --}}
                        <div class="col-lg-12">
                            <div class="card teeth-section mb-3">
                                <div class="chart-title bg-warning text-dark">
                                    <i class="fas fa-head-side-virus me-2"></i>Extraction
                                </div>
                                <div class="card-body">

                                    @include('IntraoralExamination.partials.teeth-chart', [
                                        'section' => 'extraction',
                                        'selectedTeeth' => $examination->Extraction
                                            ? explode(',', $examination->Extraction)
                                            : [],
                                    ])

                                    <input type="hidden" name="Extraction" id="extraction_teeth"
                                        value="{{ $examination->Extraction ?? '' }}">
                                </div>
                            </div>
                        </div>

                        {{-- ================= Restoration ================= --}}
                        <div class="col-lg-12">
                            <div class="card teeth-section mb-3">
                                <div class="chart-title bg-dark text-white">
                                    <i class="fas fa-times-circle me-2"></i>Restoration
                                </div>
                                <div class="card-body">

                                    @include('IntraoralExamination.partials.teeth-chart', [
                                        'section' => 'restoration',
                                        'selectedTeeth' => $examination->Restoration
                                            ? explode(',', $examination->Restoration)
                                            : [],
                                    ])

                                    <input type="hidden" name="Restoration" id="restoration_teeth"
                                        value="{{ $examination->Restoration ?? '' }}">
                                </div>
                            </div>
                        </div>

                        {{-- ================= Prosthesis ================= --}}
                        <div class="col-lg-12">
                            <div class="card teeth-section mb-3">
                                <div class="chart-title text-white" style="background: pink;">
                                    <i class="fas fa-teeth me-2"></i>Prosthesis
                                </div>
                                <div class="card-body">

                                    @include('IntraoralExamination.partials.teeth-chart', [
                                        'section' => 'prosthesis',
                                        'selectedTeeth' => $examination->Prosthesis
                                            ? explode(',', $examination->Prosthesis)
                                            : [],
                                    ])

                                    <input type="hidden" name="Prosthesis" id="prosthesis_teeth"
                                        value="{{ $examination->Prosthesis ?? '' }}">
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (request()->filled('date') && isset($examination))

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="mb-0">Other Parameters</h5>
                                </div>
                                <div class="card-body text-parameters">
                                    <!-- Text Box Parameters -->
                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2 mb-3">Text Parameters</h6>
                                        <div class="row">

                                            <div class="mb-3 col-lg-12">
                                                <label for="exam_date" class="form-label">Date</label>
                                                <input type="date" name="exam_date" id="exam_date" class="form-control"
                                                    value="{{ date('Y-m-d') }}">
                                            </div>

                                            @php
                                                $treatments = [
                                                    'Scaling',
                                                    'polishing',
                                                    'Grinding',
                                                    'Bleaching',
                                                    'smile_design',
                                                    'orthodontics',
                                                    'surgery',
                                                    'biopsy',
                                                ];
                                            @endphp

                                            @foreach ($treatments as $treatment)
                                                <div class="mb-3">
                                                    <strong>{{ str_replace('_', ' ', $treatment) }}:</strong>

                                                    @if ($examination->$treatment == 1)
                                                        <span class="badge bg-success">Yes</span>
                                                        <div class="mt-1 text-muted">
                                                            {{ $examination->{$treatment . '_desc'} ?? '' }}
                                                        </div>
                                                    @else
                                                        <span class="text-muted">No</span>
                                                    @endif
                                                </div>
                                            @endforeach

                                            <div class="mb-3 col-lg-6">
                                                <label for="impacted" class="form-label">Dentures:CD / RPD / CPD /
                                                    Overdenture/</label>
                                                <textarea name="impacted" id="impacted" class="form-control" rows="2"
                                                    placeholder="Describe impacted teeth...">{{ $examination->Dentures ?? '' }}</textarea>
                                            </div>

                                            <div class="mb-3 col-lg-6">
                                                <label for="pocket" class="form-label">implants</label>
                                                <textarea name="pocket" id="pocket" class="form-control" rows="2"
                                                    placeholder="Describe pocket conditions...">{{ $examination->implants ?? '' }}</textarea>
                                            </div>

                                            <div class="mb-3 col-lg-12">
                                                <label for="vitality" class="form-label">Other treatment</label>
                                                <textarea name="vitality" id="vitality" class="form-control" rows="2" placeholder="Describe vitality...">{{ $examination->other_treatment ?? '' }}</textarea>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>



                        </div>
                    @endif

                    @if (request()->filled('date') && isset($examination))
                        <div class="col-lg-6">
                            <!-- Summary Card -->
                            <div class="card mt-3">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Selected Teeth Summary</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="selected-teeth-summary">
                                        <input type="hidden" name="treatmentplan_id" id="treatmentplan_id"
                                            value="{{ $examination->id }}">
                                        <!-- Caries -->
                                        <div class="condition-summary mb-3">
                                            <div class="d-flex align-items-start mb-2">
                                                <strong class="text-danger" style="min-width: 90px;">RCT/IPC:</strong>
                                                <div class="teeth-list-container flex-grow-1">
                                                    <div id="rct-teeth-list" class="teeth-list">
                                                        <span class="text-muted small">No teeth selected</span>
                                                    </div>
                                                    <div class="count-badge">
                                                        <span id="rct-count" class="badge bg-danger rounded-pill">0</span>
                                                    </div>
                                                    <div id="rct-textbox-container"></div>
                                                    <button type="button" class="btn btn-primary save-condition"
                                                        data-type="1">
                                                        Save
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Pain O.P. -->
                                        <div class="condition-summary mb-3">
                                            <div class="d-flex align-items-start mb-2">
                                                <strong class="text-warning" style="min-width: 90px;">Extraction:</strong>
                                                <div class="teeth-list-container flex-grow-1">
                                                    <div id="extraction-teeth-list" class="teeth-list">
                                                        <span class="text-muted small">No teeth selected</span>
                                                    </div>
                                                    <div class="count-badge">
                                                        <span id="extraction-count"
                                                            class="badge bg-warning rounded-pill">0</span>
                                                    </div>
                                                    <div id="extraction-textbox-container"></div>
                                                    <button type="button" class="btn btn-primary save-condition"
                                                        data-type="2">
                                                        Save
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Missing -->
                                        <div class="condition-summary mb-3">
                                            <div class="d-flex align-items-start mb-2">
                                                <strong class="text-dark" style="min-width: 90px;">Restoration:</strong>
                                                <div class="teeth-list-container flex-grow-1">
                                                    <div id="restoration-teeth-list" class="teeth-list">
                                                        <span class="text-muted small">No teeth selected</span>
                                                    </div>
                                                    <div class="count-badge">
                                                        <span id="restoration-count"
                                                            class="badge bg-dark rounded-pill">0</span>
                                                    </div>
                                                    <div id="restoration-textbox-container"></div>
                                                    <button type="button" class="btn btn-primary save-condition"
                                                        data-type="3">
                                                        Save
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Mobility -->
                                        <div class="condition-summary mb-3">
                                            <div class="d-flex align-items-start mb-2">
                                                <strong class="text-primary" style="min-width: 90px;">Prosthesis:</strong>
                                                <div class="teeth-list-container flex-grow-1">
                                                    <div id="prosthesis-teeth-list" class="teeth-list">
                                                        <span class="text-muted small">No teeth selected</span>
                                                    </div>
                                                    <div class="count-badge">
                                                        <span id="prosthesis-count"
                                                            class="badge bg-primary rounded-pill">0</span>
                                                    </div>
                                                    <div id="prosthesis-textbox-container"></div>
                                                    <button type="button" class="btn btn-primary save-condition"
                                                        data-type="4">
                                                        Save
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- LIST DISPLAY SECTION - Only show if examination exists -->
                @if ($examinations->count())
                    <h4 class="mt-4">Treatment Plan Records</h4>

                    <table class="table table-bordered table-striped">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Date</th>
                                <th>RCT</th>
                                <th>Extraction</th>
                                <th>Restoration</th>
                                <th>Prosthesis</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($examinations as $exam)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($exam->date)->format('d M Y') }}</td>

                                    <td>{{ $exam->RCT_IPC }}</td>
                                    <td>{{ $exam->Extraction }}</td>
                                    <td>{{ $exam->Restoration }}</td>
                                    <td>{{ $exam->Prosthesis }}</td>

                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm delete-btn"
                                            data-id="{{ $exam->id }}">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif


            </div>
        </div>
    </div>

    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center">

                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                            colors="primary:#f7b84b,secondary:#f06548" style="width: 100px; height: 100px">
                        </lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you Sure?</h4>
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this payment?</p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-2 mt-4">

                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger">
                                Yes, Delete It!
                            </button>
                        </form>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>

                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {

            $(".delete-btn").on("click", function() {

                let id = $(this).data("id");

                // Route change karo yahan
                let actionUrl = "{{ route('TreatmentPlan.destroy', ':id') }}";
                actionUrl = actionUrl.replace(':id', id);

                $("#deleteForm").attr("action", actionUrl);

                $("#deleteRecordModal").modal("show");
            });

        });
    </script>

    <script>
        $(document).on('click', '.save-condition', function() {

            let typeId = $(this).data('type');
            let treatmentplan_id = $('#treatmentplan_id').val();
            let patientId = "{{ $patient->id }}";

            let notes = [];

            $('.tooth-comment[data-type="' + typeId + '"]').each(function() {

                let comment = $(this).val().trim();

                if (comment !== '') {

                    notes.push({
                        tooth_no: $(this).data('tooth'),
                        comment: comment
                    });

                }

            });

            $.ajax({
                url: "{{ route('save.comments') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    patient_id: patientId,
                    type_id: typeId,
                    treatmentplan_id: treatmentplan_id,
                    notes: notes
                },
                success: function(response) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Saved',
                        timer: 1200,
                        showConfirmButton: false
                    });

                }
            });

        });
    </script>

    <script>
        let oldConditions = @json($conditions ?? []);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize teeth type to adult
            toggleTeethType('adult');

            // Initialize with selected teeth from saved data
            initializeSelectedTeeth();

            // Add click handlers for teeth
            document.querySelectorAll('.teeth-selectable').forEach(div => {
                div.addEventListener('click', function() {
                    const toothNumber = this.getAttribute('data-tooth');
                    const section = this.getAttribute('data-section');

                    // Toggle selection with color change
                    const isSelected = toggleToothColorAndSelection(this, !this.classList.contains(
                        'selected'));

                    // Update hidden input and summary
                    updateHiddenInput(section, toothNumber, isSelected);
                });
            });
        });

        // Function to toggle tooth color AND selection class
        function toggleToothColorAndSelection(element, isSelected) {
            const img = element.querySelector('img');
            const section = element.getAttribute('data-section');

            if (img) {
                if (isSelected) {
                    // Change to green image
                    img.src = img.getAttribute('data-green');
                    element.classList.add('selected');
                    img.classList.add('selected', `tooth-selected-${section}`);
                } else {
                    // Change to yellow image
                    img.src = img.getAttribute('data-yellow');
                    element.classList.remove('selected');
                    img.classList.remove('selected', `tooth-selected-${section}`);
                }
            }

            return isSelected;
        }

        // Toggle between adult and child teeth
        function toggleTeethType(type) {
            const adultSections = document.querySelectorAll('.adult-teeth');
            const childSections = document.querySelectorAll('.child-teeth');
            const adultBtn = document.getElementById('adultBtn');
            const childBtn = document.getElementById('childBtn');

            if (type === 'adult') {
                adultSections.forEach(el => el.style.display = 'block');
                childSections.forEach(el => el.style.display = 'none');
                adultBtn.classList.add('active');
                childBtn.classList.remove('active');
            } else {
                adultSections.forEach(el => el.style.display = 'none');
                childSections.forEach(el => el.style.display = 'block');
                childBtn.classList.add('active');
                adultBtn.classList.remove('active');
            }
        }

        // Update hidden input function
        function updateHiddenInput(section, toothNumber, isSelected) {
            const hiddenInput = document.getElementById(`${section}_teeth`);
            let selectedTeeth = hiddenInput.value ? hiddenInput.value.split(',').filter(t => t.trim() !== '') : [];

            const index = selectedTeeth.indexOf(toothNumber);

            if (isSelected && index === -1) {
                selectedTeeth.push(toothNumber);
            } else if (!isSelected && index !== -1) {
                selectedTeeth.splice(index, 1);
            }

            // Sort teeth numbers for better readability
            selectedTeeth.sort((a, b) => parseInt(a) - parseInt(b));

            hiddenInput.value = selectedTeeth.join(',');

            // Update summary display
            updateSummaryDisplay();
        }

        function getTypeId(section) {
            switch (section) {
                case 'rct':
                    return 1;
                case 'extraction':
                    return 2;
                case 'restoration':
                    return 3;
                case 'prosthesis':
                    return 4;
            }
        }

        function renderTextboxes(section, typeId) {

            const hiddenInput = document.getElementById(section + '_teeth');
            const container = document.getElementById(section + '-textbox-container');

            if (!hiddenInput || !container) return;

            let teeth = hiddenInput.value ?
                hiddenInput.value.split(',').filter(t => t.trim() !== '') : [];

            container.innerHTML = '';

            teeth.forEach(function(tooth) {

                let commentValue = '';

                if (oldConditions[typeId] &&
                    oldConditions[typeId][tooth] &&
                    oldConditions[typeId][tooth][0]) {

                    let dbValue = oldConditions[typeId][tooth][0].comment;

                    commentValue = dbValue ? dbValue : '';
                }

                container.innerHTML += `
            <div class="mb-2 d-flex align-items-center">
                <label style="width:80px;">Tooth ${tooth}</label>
                <input type="text"
                       class="form-control tooth-comment"
                       data-tooth="${tooth}"
                       data-type="${typeId}"
                       value="${commentValue}"
                       placeholder="Enter comment">
            </div>
        `;
            });
        }

        // Initialize with selected teeth from saved data
        function initializeSelectedTeeth() {
            const sections = ['rct', 'extraction', 'restoration', 'prosthesis'];

            sections.forEach(section => {
                const hiddenInput = document.getElementById(`${section}_teeth`);
                if (hiddenInput && hiddenInput.value) {
                    const teeth = hiddenInput.value.split(',').filter(t => t.trim() !== '');
                    teeth.forEach(tooth => {
                        const element = document.querySelector(
                            `[data-section="${section}"][data-tooth="${tooth}"]`);
                        if (element) {
                            // Set tooth as selected with green color
                            toggleToothColorAndSelection(element, true);
                        }
                    });
                }
            });

            updateSummaryDisplay();
        }

        // Update summary display with tooth numbers
        function updateSummaryDisplay() {

            const sections = [
                'rct',
                'extraction',
                'restoration',
                'prosthesis'
            ];

            sections.forEach(section => {

                const hiddenInput = document.getElementById(`${section}_teeth`);

                const teeth = hiddenInput && hiddenInput.value ?
                    hiddenInput.value.split(',').filter(t => t.trim() !== '') : [];

                const countElement = document.getElementById(`${section}-count`);
                const listElement = document.getElementById(`${section}-teeth-list`);

                // Update Count
                if (countElement) {
                    countElement.textContent = teeth.length;
                }

                // Update List
                if (listElement) {

                    if (teeth.length > 0) {

                        listElement.innerHTML = teeth.map(tooth =>
                            `<span class="tooth-number-badge">${tooth}</span>`
                        ).join(', ');

                    } else {

                        listElement.innerHTML =
                            '<span class="text-muted small">No teeth selected</span>';
                    }
                }
                // Dynamic textbox generate
                renderTextboxes(section, getTypeId(section));
            });
        }


        // Confirm delete
        // function confirmDelete(examId) {
        // const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        // modal.show();
        // }
    </script>
@endsection
