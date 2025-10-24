@extends('layouts.app')

@section('title', 'Patient Note Documents')

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet" />
    <style>
        /* Move the close button to the top-right corner of the image */
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
                    @foreach ($documents as $patientdoc)
                        @php
                            $path =
                               '/dental_clinic/patient_notes_documents/' .
                                $patientdoc->patientTreatment->created_at->format('Y/m/d') .
                                '/' .
                                $patientdoc->patient_treatment_id .
                                '/' .
                                $patientdoc->document;
                          
                        @endphp

                        <div class="col-lg-3">
                            <a href="{{ asset($path) }}" data-lightbox="gallery" data-title="">
                                <img src="{{ asset($path) }}" class="img-fluid" alt="">
                            </a>
                            <p>{{ $patientdoc->comment ?? '' }}</p>
                            <p>Teeth: {{ $patientdoc->patientTreatment->tooth_selection ?? '' }}</p>
                            <p>Date: {{ date('d-m-Y', strtotime($patientdoc->date)) ?? '' }}</p>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
@endsection


