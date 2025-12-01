@extends('layouts.app')

@section('title', 'Intraoral Examination')

@section('content')
    <style>
        .selected-teeth-summary .teeth-list-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .selected-teeth-summary .teeth-list {
            min-height: 24px;
            font-size: 0.85rem;
            line-height: 1.4;
            color: #495057;
            flex-grow: 1;
            margin-right: 10px;
        }

        .selected-teeth-summary .count-badge {
            min-width: 30px;
            text-align: center;
        }

        .selected-teeth-summary .condition-summary {
            border-left: 3px solid #dee2e6;
            padding-left: 10px;
            transition: all 0.2s ease;
        }

        .selected-teeth-summary .condition-summary:hover {
            background-color: #f8f9fa;
            border-left-color: #adb5bd;
        }

        /* Color coding for condition borders */
        .condition-summary:nth-child(1) {
            border-left-color: #dc3545;
        }

        .condition-summary:nth-child(2) {
            border-left-color: #ffc107;
        }

        .condition-summary:nth-child(3) {
            border-left-color: #6c757d;
        }

        .condition-summary:nth-child(4) {
            border-left-color: #0d6efd;
        }

        .condition-summary:nth-child(5) {
            border-left-color: #800080;
        }

        .teeth_wrapper {
            width: 45px;
            cursor: pointer;
            text-align: center;
            margin: 2px;
        }

        .teeth_wrapper img {
            width: 35px;
            height: 35px;
            transition: all 0.3s ease;
        }

        /* Selected tooth styles */
        .tooth-selected-caries {
            filter: drop-shadow(0 0 3px #ff0000) brightness(0.8);
            transform: scale(1.15);
        }

        .tooth-selected-pain {
            filter: drop-shadow(0 0 3px #ff9900) brightness(0.8);
            transform: scale(1.15);
        }

        .tooth-selected-missing {
            filter: drop-shadow(0 0 3px #000000) brightness(0.5);
            transform: scale(1.15);
        }

        .tooth-selected-mobility {
            filter: drop-shadow(0 0 3px #0000ff) brightness(0.8);
            transform: scale(1.15);
        }

        .tooth-selected-prosthesis {
            filter: drop-shadow(0 0 3px #800080) brightness(0.8);
            transform: scale(1.15);
        }

        .tooth-text {
            font-size: 10px;
            font-weight: bold;
            margin-top: 3px;
        }

        .teeth-toggle-btn {
            padding: 5px 10px;
            margin: 0 5px;
            border: 1px solid #ddd;
            background: #f8f9fa;
            cursor: pointer;
        }

        .teeth-toggle-btn.active {
            background: #007bff;
            color: white;
        }

        .teeth-section {
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }

        .chart-title {
            background-color: #f8f9fa;
            padding: 8px 15px;
            border-bottom: 2px solid #dee2e6;
            font-weight: bold;
        }

        .text-parameters {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
        }

        .child-teeth {
            display: none;
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
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Date Selector -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('IntraoralExamination.index', $patient->id) }}"
                            id="dateForm">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <label for="exam_date" class="form-label">Examination Date</label>
                                    <div class="input-group">
                                        <input type="date" name="date" id="exam_date" class="form-control"
                                            value="" max="{{ date('Y-m-d') }}">
                                        <button type="button" class="btn btn-outline-secondary" onclick="setToday()">
                                            Today
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i> Load Date
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Previous Exams -->
                        {{-- @if ($allExamDates->count() > 0)
                            <div class="mt-3">
                                <small class="text-muted">Previous exams:</small>
                                @foreach ($allExamDates as $examDate)
                                    <a href="{{ route('intraoral.index', ['patient' => $patient->id, 'date' => $examDate['date']]) }}"
                                        class="badge {{ $selectedDate == $examDate['date'] ? 'bg-primary' : 'bg-secondary' }} text-decoration-none ms-1">
                                        {{ $examDate['formatted'] }}
                                    </a>
                                @endforeach
                            </div>
                        @endif --}}
                    </div>
                </div>

                <!-- Main Form -->
                <form action="{{ route('IntraoralExamination.store', $patient->id) }}" method="POST" id="intraoralForm">
                    @csrf
                    <input type="hidden" name="exam_date" value="">

                    <!-- Teeth Type Toggle -->
                    <div class="card mb-3">
                        <div class="card-body text-center">
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
                    </div>

                    @if ($examination)
                        {{-- <div class="alert alert-info mb-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <i class="fas fa-calendar-check me-2"></i>
                                    <strong>Exam Date:</strong> {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}
                                    <span class="ms-3">
                                        <i class="fas fa-user-md me-1"></i>
                                        <strong>Doctor:</strong> {{ $examination->doctor->name ?? 'N/A' }}
                                    </span>
                                </div>
                                <span class="badge bg-success">Saved</span>
                            </div>
                        </div> --}}
                    @else
                        {{-- <div class="alert alert-warning mb-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            No examination found for {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}.
                            Fill the form below to create a new examination.
                        </div> --}}
                    @endif

                    <div class="row">
                        <!-- LEFT COLUMN: Teeth Charts -->
                        <div class="col-lg-8">

                            <!-- Caries Section -->
                            <div class="card teeth-section mb-3">
                                <div class="chart-title bg-danger text-white">
                                    <i class="fas fa-tooth me-2"></i>Caries
                                </div>
                                <div class="card-body">
                                    @include('IntraoralExamination.partials.teeth-chart', [
                                        'section' => 'caries',
                                        'selectedTeeth' => $examination->caries_teeth ?? [],
                                    ])
                                    <input type="hidden" name="caries_teeth" id="caries_teeth"
                                        value="{{ implode(',', $examination->caries_teeth ?? []) }}">
                                </div>
                            </div>

                            <!-- Pain O.P. Section -->
                            <div class="card teeth-section mb-3">
                                <div class="chart-title bg-warning text-dark">
                                    <i class="fas fa-head-side-virus me-2"></i>Pain O.P.
                                </div>
                                <div class="card-body">
                                    @include('IntraoralExamination.partials.teeth-chart', [
                                        'section' => 'pain',
                                        'selectedTeeth' => $examination->pain_op_teeth ?? [],
                                    ])
                                    <input type="hidden" name="pain_op_teeth" id="pain_teeth"
                                        value="{{ implode(',', $examination->pain_op_teeth ?? []) }}">
                                </div>
                            </div>

                            <!-- Missing Section -->
                            <div class="card teeth-section mb-3">
                                <div class="chart-title bg-dark text-white">
                                    <i class="fas fa-times-circle me-2"></i>Missing
                                </div>
                                <div class="card-body">
                                    @include('IntraoralExamination.partials.teeth-chart', [
                                        'section' => 'missing',
                                        'selectedTeeth' => $examination->missing_teeth ?? [],
                                    ])
                                    <input type="hidden" name="missing_teeth" id="missing_teeth"
                                        value="{{ implode(',', $examination->missing_teeth ?? []) }}">
                                </div>
                            </div>

                            <!-- Mobility Section -->
                            <div class="card teeth-section mb-3">
                                <div class="chart-title bg-primary text-white">
                                    <i class="fas fa-arrows-alt me-2"></i>Mobility
                                </div>
                                <div class="card-body">
                                    @include('IntraoralExamination.partials.teeth-chart', [
                                        'section' => 'mobility',
                                        'selectedTeeth' => $examination->mobility_teeth ?? [],
                                    ])
                                    <input type="hidden" name="mobility_teeth" id="mobility_teeth"
                                        value="{{ implode(',', $examination->mobility_teeth ?? []) }}">
                                </div>
                            </div>

                            <!-- Prosthesis Section -->
                            <div class="card teeth-section mb-3">
                                <div class="chart-title bg-purple text-white" style="background: pink;">
                                    <i class="fas fa-teeth me-2"></i>Prosthesis
                                </div>
                                <div class="card-body">
                                    @include('IntraoralExamination.partials.teeth-chart', [
                                        'section' => 'prosthesis',
                                        'selectedTeeth' => $examination->prosthesis_teeth ?? [],
                                    ])
                                    <input type="hidden" name="prosthesis_teeth" id="prosthesis_teeth"
                                        value="{{ implode(',', $examination->prosthesis_teeth ?? []) }}">
                                </div>
                            </div>

                        </div>

                        <!-- RIGHT COLUMN: Text Parameters -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="mb-0">Other Parameters</h5>
                                </div>
                                <div class="card-body text-parameters">

                                    <!-- Text Box Parameters -->
                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2 mb-3">Text Parameters</h6>

                                        <div class="mb-3">
                                            <label for="impacted" class="form-label">Impacted</label>
                                            <textarea name="impacted" id="impacted" class="form-control" rows="2"
                                                placeholder="Describe impacted teeth...">{{ $examination->impacted ?? '' }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="pocket" class="form-label">Pocket</label>
                                            <textarea name="pocket" id="pocket" class="form-control" rows="2"
                                                placeholder="Describe pocket conditions...">{{ $examination->pocket ?? '' }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="vitality" class="form-label">Vitality</label>
                                            <textarea name="vitality" id="vitality" class="form-control" rows="2" placeholder="Describe vitality...">{{ $examination->vitality ?? '' }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="sensitivity" class="form-label">Sensitivity</label>
                                            <textarea name="sensitivity" id="sensitivity" class="form-control" rows="2"
                                                placeholder="Describe sensitivity...">{{ $examination->sensitivity ?? '' }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Dropdown Parameters -->
                                    <div class="mb-4">
                                        <h6 class="border-bottom pb-2 mb-3">Scale Parameters</h6>

                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <label for="plaque" class="form-label">Plaque</label>
                                                <select name="plaque" id="plaque" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="+"
                                                        {{ ($examination->plaque ?? '') == '+' ? 'selected' : '' }}>+
                                                    </option>
                                                    <option value="++"
                                                        {{ ($examination->plaque ?? '') == '++' ? 'selected' : '' }}>++
                                                    </option>
                                                    <option value="+++"
                                                        {{ ($examination->plaque ?? '') == '+++' ? 'selected' : '' }}>+++
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-12 mb-3">
                                                <label for="calculus" class="form-label">Calculus</label>
                                                <select name="calculus" id="calculus" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="+"
                                                        {{ ($examination->calculus ?? '') == '+' ? 'selected' : '' }}>+
                                                    </option>
                                                    <option value="++"
                                                        {{ ($examination->calculus ?? '') == '++' ? 'selected' : '' }}>++
                                                    </option>
                                                    <option value="+++"
                                                        {{ ($examination->calculus ?? '') == '+++' ? 'selected' : '' }}>+++
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-12 mb-3">
                                                <label for="stains" class="form-label">Stains</label>
                                                <select name="stains" id="stains" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="+"
                                                        {{ ($examination->stains ?? '') == '+' ? 'selected' : '' }}>+
                                                    </option>
                                                    <option value="++"
                                                        {{ ($examination->stains ?? '') == '++' ? 'selected' : '' }}>++
                                                    </option>
                                                    <option value="+++"
                                                        {{ ($examination->stains ?? '') == '+++' ? 'selected' : '' }}>+++
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-12 mb-3">
                                                <label for="bop" class="form-label">B.O.P</label>
                                                <select name="bop" id="bop" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Present"
                                                        {{ ($examination->bop ?? '') == 'Present' ? 'selected' : '' }}>
                                                        Present</option>
                                                    <option value="Absent"
                                                        {{ ($examination->bop ?? '') == 'Absent' ? 'selected' : '' }}>
                                                        Absent</option>
                                                    <option value="Localized"
                                                        {{ ($examination->bop ?? '') == 'Localized' ? 'selected' : '' }}>
                                                        Localized</option>
                                                    <option value="Generalized"
                                                        {{ ($examination->bop ?? '') == 'Generalized' ? 'selected' : '' }}>
                                                        Generalized</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Notes -->
                                    <div>
                                        <h6 class="border-bottom pb-2 mb-3">Additional Notes</h6>
                                        <div class="mb-3">
                                            <label for="notes" class="form-label">Clinical Notes</label>
                                            <textarea name="notes" id="notes" class="form-control" rows="4"
                                                placeholder="Enter any additional clinical notes...">{{ $examination->notes ?? '' }}</textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Summary Card -->
                            <!-- Enhanced Selected Teeth Summary Card -->
                            <div class="card mt-3">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Selected Teeth Summary</h6>
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="clearAllSelections()">
                                        <i class="fas fa-times"></i> Clear All
                                    </button>
                                </div>
                                <div class="card-body p-3">
                                    <div class="selected-teeth-summary">
                                        <!-- Caries -->
                                        <div class="condition-summary mb-3">
                                            <div class="d-flex align-items-start mb-2">
                                                <strong class="text-danger" style="min-width: 90px;">Caries:</strong>
                                                <div class="teeth-list-container flex-grow-1">
                                                    <div id="caries-teeth-list" class="teeth-list">
                                                        <span class="text-muted small">No teeth selected</span>
                                                    </div>
                                                    <div class="count-badge">
                                                        <span id="caries-count"
                                                            class="badge bg-danger rounded-pill">0</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Pain O.P. -->
                                        <div class="condition-summary mb-3">
                                            <div class="d-flex align-items-start mb-2">
                                                <strong class="text-warning" style="min-width: 90px;">Pain O.P.:</strong>
                                                <div class="teeth-list-container flex-grow-1">
                                                    <div id="pain-teeth-list" class="teeth-list">
                                                        <span class="text-muted small">No teeth selected</span>
                                                    </div>
                                                    <div class="count-badge">
                                                        <span id="pain-count"
                                                            class="badge bg-warning rounded-pill">0</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Missing -->
                                        <div class="condition-summary mb-3">
                                            <div class="d-flex align-items-start mb-2">
                                                <strong class="text-dark" style="min-width: 90px;">Missing:</strong>
                                                <div class="teeth-list-container flex-grow-1">
                                                    <div id="missing-teeth-list" class="teeth-list">
                                                        <span class="text-muted small">No teeth selected</span>
                                                    </div>
                                                    <div class="count-badge">
                                                        <span id="missing-count"
                                                            class="badge bg-dark rounded-pill">0</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Mobility -->
                                        <div class="condition-summary mb-3">
                                            <div class="d-flex align-items-start mb-2">
                                                <strong class="text-primary" style="min-width: 90px;">Mobility:</strong>
                                                <div class="teeth-list-container flex-grow-1">
                                                    <div id="mobility-teeth-list" class="teeth-list">
                                                        <span class="text-muted small">No teeth selected</span>
                                                    </div>
                                                    <div class="count-badge">
                                                        <span id="mobility-count"
                                                            class="badge bg-primary rounded-pill">0</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Prosthesis -->
                                        <div class="condition-summary mb-3">
                                            <div class="d-flex align-items-start mb-2">
                                                <strong class="text-purple"
                                                    style="min-width: 90px; color: #800080;">Prosthesis:</strong>
                                                <div class="teeth-list-container flex-grow-1">
                                                    <div id="prosthesis-teeth-list" class="teeth-list">
                                                        <span class="text-muted small">No teeth selected</span>
                                                    </div>
                                                    <div class="count-badge">
                                                        <span id="prosthesis-count" class="badge rounded-pill"
                                                            style="background-color: #800080;">0</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="card mt-3">
                                <div class="card-body text-center">
                                    @if ($examination)
                                        <button type="button" class="btn btn-danger me-2"
                                            onclick="confirmDelete({{ $examination->id }})">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    @endif
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> {{ $examination ? 'Update' : 'Save' }} Examination
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Delete examination for {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}? --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    @if ($examination)
                        <form id="deleteForm" method="POST"
                            action="{{ route('IntraoralExamination.destroy', $examination->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        // Set today's date
        function setToday() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('exam_date').value = today;
            document.getElementById('dateForm').submit();
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

        // Initialize with selected teeth from saved data
        function initializeSelectedTeeth() {
            const sections = ['caries', 'pain', 'missing', 'mobility', 'prosthesis'];

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
            const sections = ['caries', 'pain', 'missing', 'mobility', 'prosthesis'];

            sections.forEach(section => {
                const hiddenInput = document.getElementById(`${section}_teeth`);
                const teeth = hiddenInput && hiddenInput.value ?
                    hiddenInput.value.split(',').filter(t => t.trim() !== '') : [];

                const countElement = document.getElementById(`${section}-count`);
                const listElement = document.getElementById(`${section}-teeth-list`);

                // Update count
                if (countElement) {
                    countElement.textContent = teeth.length;
                }

                // Update tooth numbers list
                if (listElement) {
                    if (teeth.length > 0) {
                        // Create a comma-separated list
                        listElement.innerHTML = teeth.map(tooth =>
                            `<span class="tooth-number-badge">${tooth}</span>`
                        ).join(', ');
                    } else {
                        listElement.innerHTML = '<span class="text-muted small">No teeth selected</span>';
                    }
                }
            });
        }

        // Clear all selections
        function clearAllSelections() {
            if (confirm('Are you sure you want to clear all tooth selections?')) {
                const sections = ['caries', 'pain', 'missing', 'mobility', 'prosthesis'];

                sections.forEach(section => {
                    // Clear hidden inputs
                    const hiddenInput = document.getElementById(`${section}_teeth`);
                    if (hiddenInput) hiddenInput.value = '';

                    // Clear visual selections
                    document.querySelectorAll(`.teeth-selectable[data-section="${section}"]`).forEach(el => {
                        toggleToothColorAndSelection(el, false);
                    });
                });

                // Update summary
                updateSummaryDisplay();
            }
        }

        // Show toast notification (placeholder - you can implement proper toast)
        function showToast(message, type = 'info') {
            alert(message); // For now, using alert
        }

        // Confirm delete
        function confirmDelete(examId) {
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        // Add dynamic CSS for tooth badges
        const style = document.createElement('style');
        style.textContent = `
        .tooth-number-badge {
            display: inline-block;
            padding: 2px 6px;
            margin: 1px 2px;
            background-color: #e9ecef;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #495057;
            transition: all 0.2s ease;
        }
        
        .tooth-number-badge:hover {
            background-color: #dee2e6;
            transform: translateY(-1px);
            cursor: default;
        }
        
        /* Specific colors for each condition */
        #caries-teeth-list .tooth-number-badge { border-left: 3px solid #dc3545; }
        #pain-teeth-list .tooth-number-badge { border-left: 3px solid #ffc107; }
        #missing-teeth-list .tooth-number-badge { border-left: 3px solid #6c757d; }
        #mobility-teeth-list .tooth-number-badge { border-left: 3px solid #0d6efd; }
        #prosthesis-teeth-list .tooth-number-badge { border-left: 3px solid #800080; }
        
        /* Selected tooth styles */
        .teeth_wrapper.selected img {
            transform: scale(1.1);
            transition: transform 0.3s ease;
        }
        
        /* Condition-specific glow effects */
        .tooth-selected-caries {
            filter: drop-shadow(0 0 3px rgba(220, 53, 69, 0.5)) !important;
        }
        
        .tooth-selected-pain {
            filter: drop-shadow(0 0 3px rgba(255, 193, 7, 0.5)) !important;
        }
        
        .tooth-selected-missing {
            filter: drop-shadow(0 0 3px rgba(108, 117, 125, 0.5)) !important;
        }
        
        .tooth-selected-mobility {
            filter: drop-shadow(0 0 3px rgba(13, 110, 253, 0.5)) !important;
        }
        
        .tooth-selected-prosthesis {
            filter: drop-shadow(0 0 3px rgba(128, 0, 128, 0.5)) !important;
        }
    `;
        document.head.appendChild(style);
    </script>
@endsection
