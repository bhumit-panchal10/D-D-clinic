@extends('layouts.app')

@section('title', 'Patient Notes')

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

                {{-- Alert Messages --}}
                @include('common.alert')

                @include('patient.show', ['id' => $patient->id]) <!-- ✅ Patient details included -->

                <div class="row">
                    <!-- Add Note Section -->
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0">Add Note</h5>
                            </div>

                            <div class="card-body">
                                <form action="{{ route('patient_notes.store', $patient->id) }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label">Treatment <span class="text-danger">*</span></label>
                                        <select name="treatment_id" class="form-control" required>
                                            @foreach ($treatments as $t)
                                                <option value="{{ $t->id }}">{{ $t->treatment_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="date" rows="3" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Note <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="notes" rows="3" required></textarea>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                        <button type="reset" class="btn btn-primary">Clear</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Notes List Section -->
                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Notes List</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Treatment</th>
                                            <th>Note</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($notes as $key => $note)
                                            <tr>
                                                <td>{{ $notes->firstItem() + $key }}</td>
                                                <td>{{ $note->treatment->treatment_name ?? '' }}</td>
                                                <td>{{ $note->notes }}</td>
                                                <td>{{ $note->date ? date('d-m-Y', strtotime($note->date)) : '-' }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-primary edit-btn"
                                                        data-id="{{ $note->id }}" data-notes="{{ $note->notes }}"
                                                        data-patient-id="{{ $patient->id }}"
                                                        data-date="{{ $note->date }}"
                                                        data-treatment-id="{{ $note->treatment_id }}"
                                                        data-bs-toggle="modal" data-bs-target="#editNoteModal">
                                                        Edit
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-primary delete-btn"
                                                        data-id="{{ $note->id }}"
                                                        data-patient-id="{{ $patient->id }}" data-toggle="modal"
                                                        data-target="#deleteRecordModal">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $notes->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Edit Note Modal -->
    <div class="modal fade" id="editNoteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label">Treatment <span class="text-danger">*</span></label>
                            <select name="treatment_id" class="form-control" required>
                                @foreach ($treatments as $t)
                                    <option value="{{ $t->id }}">{{ $t->treatment_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="date" id="editdate" required>
                        </div>
                        <div class="mb-3">
                            <label>Note <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="notes" id="editNotes" rows="3" required></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
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
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this note?</p>
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
            // Open Edit Modal & Load Data
            $(".edit-btn").on("click", function() {
                let id = $(this).data("id");
                let notes = $(this).data("notes");
                let date = $(this).data("date");
                let treatmentId = $(this).data("treatment-id");
                let patientId = $(this).data("patient-id");

                $("#editNotes").val(notes);
                // normalize to YYYY-MM-DD for <input type="date">
                let iso = "";
                if (date) {
                    // try to detect D-M-Y and swap
                    const dmy = /^(\d{2})[-\/](\d{2})[-\/](\d{4})$/.exec(date);
                    const ymd = /^(\d{4})[-\/](\d{2})[-\/](\d{2})$/.exec(date);
                    if (dmy) {
                        iso = `${dmy[3]}-${dmy[2]}-${dmy[1]}`;
                    } else if (ymd) {
                        iso = `${ymd[1]}-${ymd[2]}-${ymd[3]}`;
                    }
                }
                $("#editdate").val(iso);
                const $select = $('#editNoteModal select[name="treatment_id"]');
                $select.val(String(treatmentId)).trigger('change');

                let actionUrl = "{{ route('patient_notes.update', [':patient_id', ':id']) }}"
                    .replace(':patient_id', patientId)
                    .replace(':id', id);

                $("#editForm").attr("action", actionUrl);
            });

            $(".delete-btn").on("click", function() {
                let id = $(this).data("id");
                let patientId = $(this).data("patient-id");

                // Set the delete form action dynamically
                let actionUrl = "{{ route('patient_notes.destroy', [':patient_id', ':id']) }}"
                    .replace(':patient_id', patientId)
                    .replace(':id', id);

                $("#deleteForm").attr("action", actionUrl);
                $("#deleteRecordModal").modal("show");
            });

            // Confirm Delete Button Click
            $("#confirmDelete").on("click", function() {
                $("#deleteForm").submit();
            });
        });
    </script>
@endsection
