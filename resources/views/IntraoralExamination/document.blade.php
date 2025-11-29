@extends('layouts.app')

@section('title', 'Patient Note Documents')

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet" />
    <style>
        .lightbox .lb-close {
            top: 10px !important;
            right: 10px !important;
            left: auto !important;
            z-index: 1050;
            position: absolute;
        }
    </style>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    @if($documents->count() > 0)
                        @foreach ($documents as $patientdoc)
                            @php
                                $treatment = $patientdoc->patientTreatment;

                                // Build the path safely — check if related treatment exists
                                $createdPath = optional($treatment->created_at)->format('Y/m/d') ?? 'unknown';
                                $treatmentId = $patientdoc->patient_treatment_id ?? 'no_treatment';
                                $path = "/dental_clinic/patient_notes_documents/{$createdPath}/{$treatmentId}/{$patientdoc->document}";
                            @endphp

                            <div class="col-lg-3">
                                <a href="{{ asset($path) }}" data-lightbox="gallery" data-title="">
                                    <img src="{{ asset($path) }}" class="img-fluid" alt="Document Image">
                                </a>

                                <p>{{ $patientdoc->comment ?? '' }}</p>
                                <p>
                                    Teeth:
                                    {{ optional($patientdoc->patientNote)->tooth_number ?? 'N/A' }}
                                </p>
                                <p>
                                    Date:
                                    {{ $patientdoc->patientNote->date ? date('d-m-Y', strtotime($patientdoc->patientNote->date)) : 'N/A' }}
                                </p>
                            </div>
                        @endforeach
                    @else
                        <div class="col-lg-12">
                            <p>No documents found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
@endsection
