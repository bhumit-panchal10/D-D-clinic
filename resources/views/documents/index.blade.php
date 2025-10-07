@extends('layouts.app')

@section('title', 'Patient Documents')

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
                    </h5>
                    <a href="{{ route('patient.index') }}" class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                    </a>
                </div>

                @include('common.alert')


                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($patient)
                    @include('patient.show', ['id' => $patient->id])
                @endif

                <div class="row">
                    <!-- Document Upload Section -->
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0">Add Document</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('document.store', $patient->id ?? 0) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                                    @if (request('treatment_id'))
                                        <input type="hidden" name="treatment_id" value="{{ request('treatment_id') }}">
                                    @else
                                        <input type="hidden" name="treatment_id" value="">
                                    @endif

                                    @if (request('patient_treatment_id'))
                                        <input type="hidden" name="patient_treatment_id"
                                            value="{{ request('patient_treatment_id') }}">
                                    @else
                                        <input type="hidden" name="patient_treatment_id" value="">
                                    @endif

                                    <div class="mb-3">
                                        <label>Document <span class="text-danger">*</span></label>
                                        <input type="file" name="document" class="form-control"
                                            accept="image/jpeg, image/png, application/pdf" required>
                                    </div>
                                    
                                     <div class="mb-3">
                                        <label>Date</label>
                                        <input type="date" name="date" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label>Comment</label>
                                        <textarea name="comment" class="form-control"></textarea>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                        <button type="reset" class="btn btn-primary">Clear</button>
                                    </div>
                                </form>


                            </div>
                        </div>
                    </div>

                    <!-- Document List Section -->
                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Document List</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr no.</th>
                                            <th>Document Name</th>
                                            <th>Date</th>
                                            <th>Comment</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($documents as $key => $document)
                                            <tr>
                                                <td>{{ $documents->firstItem() + $key }}</td>
                                                <td>{{ $document->document ?? 'N/A' }}</td>
                                                <td>
                                                    {{ ($document->date && $document->date != '0000-00-00') ? date('d-m-Y', strtotime($document->date)) : '' }}
                                                </td>

                                                <td>{{ $document->comment }}</td>
                                                <td>
                                                    <a href="{{ asset('/D&D_DENTAL_CLINIC/documents/' . $document->document) }}"
                                                        target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-primary delete-btn"
                                                        data-id="{{ $document->id }}"
                                                        data-patient-id="{{ $patient->id }}">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $documents->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Delete Modal Start -->
    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                            colors="primary:#f7b84b,secondary:#f06548" style="width: 100px; height: 100px">
                        </lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you Sure?</h4>
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this document?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="document_id" id="deleteid" value="">
                            <button type="submit" class="btn btn-primary">Yes, Delete It!</button>
                        </form>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Modal End -->

@endsection

@section('scripts')
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
