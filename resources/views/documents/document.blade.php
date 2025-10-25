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
                            $treatment = $patientdoc->patientTreatment;
                            // Safely check if treatment exists
                            $createdPath = optional($treatment)->created_at ? $treatment->created_at->format('Y/m/d') : 'unknown';
                            $treatmentId = $patientdoc->patient_treatment_id ?? 'no_treatment';
                            $path = "/dental_clinic/patient_treatments/{$createdPath}/{$treatmentId}/{$patientdoc->document}";
                        @endphp

                        <div class="col-lg-3">
                            <a href="{{ asset($path) }}" data-lightbox="gallery" data-title="{{ $patientdoc->comment ?? 'No comment' }}">
                                <img src="{{ asset($path) }}" class="img-fluid" alt="Document Image">
                            </a>
                            <p>{{ $patientdoc->comment ?? 'No comment provided' }}</p>
                            <p>Teeth: {{ optional($treatment)->tooth_selection ?? 'N/A' }}</p>
                            <p>Date: {{ $patientdoc->date ? date('d-m-Y', strtotime($patientdoc->date)) : 'N/A' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteRecordModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="deleteid" name="id">
                    <div class="modal-body">
                        Are you sure you want to delete this document?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" id="confirmDelete" class="btn btn-danger">Delete</button>
                    </div>
                </form>
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
