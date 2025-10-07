@extends('layouts.app')

@section('title', 'Patient Documents')

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
                    @foreach ($patienttreatmentdoc as $patientdoc)
                        @php
                            $path =
                                '/patient_treatments/' .
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".delete-btn").on("click", function() {
                let id = $(this).data("id");
                let patientId = $(this).data("patient-id");

                // Set the delete form action dynamically
                let actionUrl = "{{ route('document.destroy', [':patient_id', ':id']) }}"
                    .replace(':patient_id', patientId)
                    .replace(':id', id);

                $("#deleteForm").attr("action", actionUrl);
                $("#deleteid").val(id);

                // Show the modal
                $("#deleteRecordModal").modal("show");
            });

            // Confirm Delete Button Click
            $("#confirmDelete").on("click", function() {
                $("#deleteForm").submit();
            });
        });
    </script>
@endsection
