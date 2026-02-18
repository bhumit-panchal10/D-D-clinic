@extends('layouts.app')

@section('title', 'Intraoral Examination')

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
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Main Form (Existing form remains the same) -->
                <form action="{{ route('IntraoralExamination.store', $patient->id) }}" method="POST" id="intraoralForm">
                    @csrf
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
                            <a href="{{ route('IntraoralExamination.index', $patient->id) }}"
                                class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                            </a>
                        </div>
                    </div>


                    <div class="row">
                        <!-- LEFT COLUMN: Teeth Charts -->
                        <div class="col-lg-6">
                            <!-- Caries Section -->
                            <div class="card teeth-section mb-3">
                                <div class="chart-title bg-danger text-white">
                                    <i class="fas fa-tooth me-2"></i>Caries
                                </div>
                                <div class="card-body">
                                    @include('IntraoralExamination.partials.teeth-chart', [
                                        'section' => 'caries',
                                        // 'selectedTeeth' => $examination->caries
                                        //     ? explode(',', $examination->caries)
                                        //     : [],
                                    ])
                                    <input type="hidden" name="caries" id="caries_teeth"
                                        value="{{ $examination->caries ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <!-- Pain O.P. Section -->
                            <div class="card teeth-section mb-3">
                                <div class="chart-title bg-warning text-dark">
                                    <i class="fas fa-head-side-virus me-2"></i>Pain O.P.
                                </div>
                                <div class="card-body">
                                    @include('IntraoralExamination.partials.teeth-chart', [
                                        'section' => 'pain',
                                        // 'selectedTeeth' => $examination->pain_op
                                        //     ? explode(',', $examination->pain_op)
                                        //     : [],
                                    ])
                                    <input type="hidden" name="pain_op" id="pain_teeth"
                                        value="{{ $examination->pain_op ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <!-- Missing Section -->
                            <div class="card teeth-section mb-3">
                                <div class="chart-title bg-dark text-white">
                                    <i class="fas fa-times-circle me-2"></i>Missing
                                </div>
                                <div class="card-body">
                                    @include('IntraoralExamination.partials.teeth-chart', [
                                        'section' => 'missing',
                                        // 'selectedTeeth' => $examination->missing
                                        //     ? explode(',', $examination->missing)
                                        //     : [],
                                    ])
                                    <input type="hidden" name="missing" id="missing_teeth"
                                        value="{{ $examination->missing ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <!-- Mobility Section -->
                            <div class="card teeth-section mb-3">
                                <div class="chart-title bg-primary text-white">
                                    <i class="fas fa-arrows-alt me-2"></i>Mobility
                                </div>
                                <div class="card-body">
                                    @include('IntraoralExamination.partials.teeth-chart', [
                                        'section' => 'mobility',
                                        // 'selectedTeeth' => $examination->mobility
                                        //     ? explode(',', $examination->mobility)
                                        //     : [],
                                    ])
                                    <input type="hidden" name="mobility" id="mobility_teeth"
                                        value="{{ $examination->mobility ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card teeth-section mb-3">
                                <div class="chart-title bg-purple text-white" style="background: pink;">
                                    <i class="fas fa-teeth me-2"></i>Prosthesis
                                </div>
                                <div class="card-body">
                                    @include('IntraoralExamination.partials.teeth-chart', [
                                        'section' => 'prosthesis',
                                        'selectedTeeth' => $examination->prosthesis_teeth ?? [],
                                    ])
                                    <input type="hidden" name="prosthesis" id="prosthesis_teeth"
                                        value="{{ $examination->prosthesis ?? '' }}">
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-lg-8">
                            <!-- Caries Section -->
                            <div class="card teeth-section mb-3">
                                <div class="chart-title bg-danger text-white">
                                    <i class="fas fa-tooth me-2"></i>Caries
                                </div>
                                <div class="card-body">
                                    @include('IntraoralExamination.partials.teeth-chart', [
                                        'section' => 'caries',
                                        'selectedTeeth' => $examination->caries
                                            ? explode(',', $examination->caries)
                                            : [],
                                    ])
                                    <input type="hidden" name="caries" id="caries_teeth"
                                        value="{{ $examination->caries ?? '' }}">
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
                                        'selectedTeeth' => $examination->pain_op
                                            ? explode(',', $examination->pain_op)
                                            : [],
                                    ])
                                    <input type="hidden" name="pain_op" id="pain_teeth"
                                        value="{{ $examination->pain_op ?? '' }}">
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
                                        'selectedTeeth' => $examination->missing
                                            ? explode(',', $examination->missing)
                                            : [],
                                    ])
                                    <input type="hidden" name="missing" id="missing_teeth"
                                        value="{{ $examination->missing ?? '' }}">
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
                                        'selectedTeeth' => $examination->mobility
                                            ? explode(',', $examination->mobility)
                                            : [],
                                    ])
                                    <input type="hidden" name="mobility" id="mobility_teeth"
                                        value="{{ $examination->mobility ?? '' }}">
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
                                    <input type="hidden" name="prosthesis" id="prosthesis_teeth"
                                        value="{{ $examination->prosthesis ?? '' }}">
                                </div>
                            </div>

                        </div> --}}

                        <!-- RIGHT COLUMN: Text Parameters -->
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
                                                <label for="exam_date" class="form-label">Exam Date</label>
                                                <input type="date" name="exam_date" id="exam_date" class="form-control"
                                                    value="{{ date('Y-m-d') }}">
                                            </div>
                                            <div class="mb-3 col-lg-6">
                                                <label for="impacted" class="form-label">Impacted</label>
                                                <textarea name="impacted" id="impacted" class="form-control" rows="2"
                                                    placeholder="Describe impacted teeth...">{{ $examination->impacted ?? '' }}</textarea>
                                            </div>

                                            <div class="mb-3 col-lg-6">
                                                <label for="pocket" class="form-label">Pocket</label>
                                                <textarea name="pocket" id="pocket" class="form-control" rows="2"
                                                    placeholder="Describe pocket conditions...">{{ $examination->Pocket ?? '' }}</textarea>
                                            </div>

                                            <div class="mb-3 col-lg-12">
                                                <label for="vitality" class="form-label">Vitality</label>
                                                <textarea name="vitality" id="vitality" class="form-control" rows="2" placeholder="Describe vitality...">{{ $examination->vitality ?? '' }}</textarea>
                                            </div>

                                            <div class="mb-3 col-lg-12">
                                                <label for="sensitivity" class="form-label">Sensitivity</label>
                                                <textarea name="sensitivity" id="sensitivity" class="form-control" rows="2"
                                                    placeholder="Describe sensitivity...">{{ $examination->Sensitivity ?? '' }}</textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-6 bg-white">
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2 mb-3">Scale Parameters</h6>

                                <div class="row">
                                    <div class="col-6 mb-3">
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
                                                {{ ($examination->plaque ?? '') == '+++' ? 'selected' : '' }}>
                                                +++
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-6 mb-3">
                                        <label for="calculus" class="form-label">Calculus</label>
                                        <select name="calculus" id="calculus" class="form-control">
                                            <option value="">Select</option>
                                            <option value="+"
                                                {{ ($examination->calculus ?? '') == '+' ? 'selected' : '' }}>+
                                            </option>
                                            <option value="++"
                                                {{ ($examination->calculus ?? '') == '++' ? 'selected' : '' }}>
                                                ++
                                            </option>
                                            <option value="+++"
                                                {{ ($examination->calculus ?? '') == '+++' ? 'selected' : '' }}>
                                                +++
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-6 mb-3">
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
                                                {{ ($examination->stains ?? '') == '+++' ? 'selected' : '' }}>
                                                +++
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-6 mb-3">
                                        <label for="bop" class="form-label">B.O.P</label>
                                        <select name="bop" id="bop" class="form-control">
                                            <option value="">Select</option>
                                            <option value="Present"
                                                {{ ($examination->BOP ?? '') == 'Present' ? 'selected' : '' }}>
                                                Present</option>
                                            <option value="Absent"
                                                {{ ($examination->BOP ?? '') == 'Absent' ? 'selected' : '' }}>
                                                Absent</option>
                                            <option value="Localized"
                                                {{ ($examination->BOP ?? '') == 'Localized' ? 'selected' : '' }}>
                                                Localized</option>
                                            <option value="Generalized"
                                                {{ ($examination->BOP ?? '') == 'Generalized' ? 'selected' : '' }}>
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

                        <div class="col-lg-6">
                            <!-- Summary Card -->
                            <div class="card mt-3">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Selected Teeth Summary</h6>
                                    {{-- <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="clearAllSelections()">
                                        <i class="fas fa-times"></i> Clear All
                                    </button> --}}
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
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> {{ 'Save' }} Examination
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

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

        // Confirm delete
        function confirmDelete(examId) {
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
    </script>
@endsection
