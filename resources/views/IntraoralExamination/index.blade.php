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
                                            value="{{ $selectedDate ?? '' }}" max="{{ date('Y-m-d') }}">
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
                    </div>
                </div>

                <!-- LIST DISPLAY SECTION - Only show if examination exists -->
                @if ($examination)
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-2"></i>Examination Details
                                <span class="exam-date-badge ms-3">
                                    {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}
                                </span>
                            </h5>
                            {{-- <button class="list-toggle-btn" onclick="window.print()">
                                <i class="fas fa-print"></i> Print
                            </button> --}}
                        </div>
                        <div class="card-body">
                            <div class="list-display-container">
                                <div class="list-header">
                                    <h6 class="mb-0">All Examination Parameters</h6>
                                    {{-- <small class="text-muted">Doctor: {{ $examination->doctor->name ?? 'N/A' }}</small> --}}
                                </div>

                                <ul class="field-list">
                                    <!-- Caries -->
                                    <li class="field-item">
                                        <div class="field-label">
                                            <i class="fas fa-tooth text-danger"></i>
                                            Caries
                                        </div>
                                        <div class="field-value">
                                            @if (!empty($examination->caries))

                                                <div class="teeth-list-display">
                                                    @foreach ($examination->caries as $tooth)
                                                        <span class="tooth-badge caries">{{ $tooth }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="no-data">No teeth selected</span>
                                            @endif
                                        </div>
                                    </li>

                                    <!-- Pain O.P. -->
                                    <li class="field-item">
                                        <div class="field-label">
                                            <i class="fas fa-head-side-virus text-warning"></i>
                                            Pain O.P.
                                        </div>
                                        <div class="field-value">
                                            @if (!empty($examination->pain_op))
                                                <div class="teeth-list-display">
                                                    @foreach ($examination->pain_op as $tooth)
                                                        <span class="tooth-badge pain">{{ $tooth }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="no-data">No teeth selected</span>
                                            @endif
                                        </div>
                                    </li>

                                    <!-- Missing -->
                                    <li class="field-item">
                                        <div class="field-label">
                                            <i class="fas fa-times-circle text-dark"></i>
                                            Missing
                                        </div>
                                        <div class="field-value">
                                            @if (!empty($examination->missing))
                                                <div class="teeth-list-display">
                                                    @foreach ($examination->missing as $tooth)
                                                        <span class="tooth-badge missing">{{ $tooth }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="no-data">No teeth selected</span>
                                            @endif
                                        </div>
                                    </li>

                                    <!-- Mobility -->
                                    <li class="field-item">
                                        <div class="field-label">
                                            <i class="fas fa-arrows-alt text-primary"></i>
                                            Mobility
                                        </div>
                                        <div class="field-value">
                                            @if (!empty($examination->mobility))
                                                <div class="teeth-list-display">
                                                    @foreach ($examination->mobility as $tooth)
                                                        <span class="tooth-badge mobility">{{ $tooth }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="no-data">No teeth selected</span>
                                            @endif
                                        </div>
                                    </li>

                                    <!-- Prosthesis -->
                                    <li class="field-item">
                                        <div class="field-label">
                                            <i class="fas fa-teeth" style="color: #800080;"></i>
                                            Prosthesis
                                        </div>
                                        <div class="field-value">
                                            @if (!empty($examination->prosthesis))
                                                <div class="teeth-list-display">
                                                    @foreach ($examination->prosthesis as $tooth)
                                                        <span class="tooth-badge prosthesis">{{ $tooth }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="no-data">No teeth selected</span>
                                            @endif
                                        </div>
                                    </li>

                                    <!-- Text Parameters -->
                                    <li class="field-item">
                                        <div class="field-label">
                                            <i class="fas fa-file-alt text-secondary"></i>
                                            Impacted
                                        </div>
                                        <div class="field-value">
                                            {{ $examination->impacted ?: '<span class="field-value-empty">Not specified</span>' }}
                                        </div>
                                    </li>

                                    <li class="field-item">
                                        <div class="field-label">
                                            <i class="fas fa-ruler text-secondary"></i>
                                            Pocket
                                        </div>
                                        <div class="field-value">
                                            {{ $examination->Pocket ?: '<span class="field-value-empty">Not specified</span>' }}
                                        </div>
                                    </li>

                                    <li class="field-item">
                                        <div class="field-label">
                                            <i class="fas fa-heartbeat text-secondary"></i>
                                            Vitality
                                        </div>
                                        <div class="field-value">
                                            {{ $examination->vitality ?: '<span class="field-value-empty">Not specified</span>' }}
                                        </div>
                                    </li>

                                    <li class="field-item">
                                        <div class="field-label">
                                            <i class="fas fa-exclamation-circle text-secondary"></i>
                                            Sensitivity
                                        </div>
                                        <div class="field-value">
                                            {{ $examination->Sensitivity ?: '<span class="field-value-empty">Not specified</span>' }}
                                        </div>
                                    </li>

                                    <!-- Scale Parameters -->
                                    <li class="field-item">
                                        <div class="field-label">
                                            <i class="fas fa-layer-group text-info"></i>
                                            Plaque
                                        </div>
                                        <div class="field-value">
                                            @if ($examination->plaque)
                                                <span class="badge bg-info">{{ $examination->plaque }}</span>
                                            @else
                                                <span class="field-value-empty">Not specified</span>
                                            @endif
                                        </div>
                                    </li>

                                    <li class="field-item">
                                        <div class="field-label">
                                            <i class="fas fa-mountain text-info"></i>
                                            Calculus
                                        </div>
                                        <div class="field-value">
                                            @if ($examination->calculus)
                                                <span class="badge bg-info">{{ $examination->calculus }}</span>
                                            @else
                                                <span class="field-value-empty">Not specified</span>
                                            @endif
                                        </div>
                                    </li>

                                    <li class="field-item">
                                        <div class="field-label">
                                            <i class="fas fa-tint text-info"></i>
                                            Stains
                                        </div>
                                        <div class="field-value">
                                            @if ($examination->stains)
                                                <span class="badge bg-info">{{ $examination->stains }}</span>
                                            @else
                                                <span class="field-value-empty">Not specified</span>
                                            @endif
                                        </div>
                                    </li>

                                    <li class="field-item">
                                        <div class="field-label">
                                            <i class="fas fa-droplet text-info"></i>
                                            B.O.P.
                                        </div>
                                        <div class="field-value">
                                            @if ($examination->BOP)
                                                <span class="badge bg-info">{{ $examination->BOP }}</span>
                                            @else
                                                <span class="field-value-empty">Not specified</span>
                                            @endif
                                        </div>
                                    </li>

                                    <!-- Notes -->
                                    <li class="field-item">
                                        <div class="field-label">
                                            <i class="fas fa-sticky-note text-success"></i>
                                            Clinical Notes
                                        </div>
                                        <div class="field-value">
                                            @if ($examination->notes)
                                                <div class="notes-content" style="white-space: pre-wrap;">
                                                    {{ $examination->notes }}
                                                </div>
                                            @else
                                                <span class="field-value-empty">No notes added</span>
                                            @endif
                                        </div>
                                    </li>

                                    <!-- Summary Statistics -->
                                    <li class="field-item bg-light">
                                        <div class="field-label">
                                            <i class="fas fa-chart-bar text-primary"></i>
                                            Summary
                                        </div>
                                        <div class="field-value">
                                            <div class="d-flex gap-3">
                                                <span class="badge bg-danger">
                                                    Caries: {{ count($examination->caries ?? []) }}
                                                </span>
                                                <span class="badge bg-warning text-dark">
                                                    Pain: {{ count($examination->pain_op ?? []) }}
                                                </span>
                                                <span class="badge bg-dark">
                                                    Missing: {{ count($examination->missing ?? []) }}
                                                </span>
                                                <span class="badge bg-primary">
                                                    Mobility: {{ count($examination->mobility ?? []) }}
                                                </span>
                                                <span class="badge" style="background-color: #800080;">
                                                    Prosthesis: {{ count($examination->prosthesis ?? []) }}
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Main Form (Existing form remains the same) -->
                <form action="{{ route('IntraoralExamination.store', $patient->id) }}" method="POST"
                    id="intraoralForm">
                    @csrf
                    {{-- <input type="hidden" name="exam_date" value="{{ $selectedDate ?? '' }}"> --}}

                    <!-- The rest of your form remains exactly the same -->
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
                                    <input type="hidden" name="caries" id="caries_teeth"
                                        value="{{ implode(',', $examination->caries ?? []) }}">
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
                                    <input type="hidden" name="pain_op" id="pain_teeth"
                                        value="{{ implode(',', $examination->pain_op ?? []) }}">
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
                                    <input type="hidden" name="missing" id="missing_teeth"
                                        value="{{ implode(',', $examination->missing ?? []) }}">
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
                                    <input type="hidden" name="mobility" id="mobility_teeth"
                                        value="{{ implode(',', $examination->mobility ?? []) }}">
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
                                        value="{{ implode(',', $examination->prosthesis ?? []) }}">
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
                                            <label for="exam_date" class="form-label">Exam Date</label>
                                            <input type="date" name="exam_date" id="exam_date" class="form-control"
                                                value="{{ $examination->exam_date ?? ($selectedDate ?? '') }}"
                                                max="{{ date('Y-m-d') }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="impacted" class="form-label">Impacted</label>
                                            <textarea name="impacted" id="impacted" class="form-control" rows="2"
                                                placeholder="Describe impacted teeth...">{{ $examination->impacted ?? '' }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="pocket" class="form-label">Pocket</label>
                                            <textarea name="pocket" id="pocket" class="form-control" rows="2"
                                                placeholder="Describe pocket conditions...">{{ $examination->Pocket ?? '' }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="vitality" class="form-label">Vitality</label>
                                            <textarea name="vitality" id="vitality" class="form-control" rows="2" placeholder="Describe vitality...">{{ $examination->vitality ?? '' }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="sensitivity" class="form-label">Sensitivity</label>
                                            <textarea name="sensitivity" id="sensitivity" class="form-control" rows="2"
                                                placeholder="Describe sensitivity...">{{ $examination->Sensitivity ?? '' }}</textarea>
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
                            </div>

                            <!-- Summary Card -->
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
                    Are you sure you want to delete the examination for
                    {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}?
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

        // Confirm delete
        function confirmDelete(examId) {
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
    </script>
@endsection
