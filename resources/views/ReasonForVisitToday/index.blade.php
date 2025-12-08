@extends('layouts.app')

@section('title', 'Reason For Visit Today')

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
                @include('patient.show', ['id' => $patient->id])
                @include('patient_treatments.Submenu', ['id' => $patient->id])


                <div class="row">
                    <!-- Add Note Section -->
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0">Add</h5>
                            </div>

                            <div class="card-body">

                                <form action="{{ route('ReasonForVisitToday.store', $patient->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                    <div class="row mt-3">
                                        <div class="mb-3">
                                            <label for="comments" class="form-label">Reason For Visit Today</label>
                                            <textarea name="comments" id="comments" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label>Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="date" name="date" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="facial_asymmetry" class="form-label">Facial Asymmetry</label>
                                        <input type="text" name="facial_asymmetry" id="facial_asymmetry"
                                            class="form-control" value="">
                                    </div>
                                    <div class="mb-3">
                                        <label for="TMJ" class="form-label">TMJ</label>
                                        <input type="text" name="TMJ" id="TMJ" class="form-control"
                                            value="">
                                    </div>
                                    <div class="mb-3">
                                        <label for="TMJ" class="form-label">Lymphadenopathy</label>
                                        <input type="text" name="Lymphadenopathy" id="Lymphadenopathy"
                                            class="form-control" value="">
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
                                <h5 class="card-title mb-0">Reason For Visit Today List</h5>
                                <div class="d-flex justify-content-between align-items-center m-3">
                                    <h5 class="mb-0">


                                    </h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Facial Asymmetry</th>
                                            <th>TMJ</th>
                                            <th>Lymphadenopathy</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ReasonForVisitToday as $key => $ReasonForVisit)
                                            <tr>
                                                <td class="text-center">{{ $ReasonForVisitToday->firstItem() + $key }}</td>
                                                <td>{{ $ReasonForVisit->facial_asymmetry ?? '-' }}</td>
                                                <td>{{ $ReasonForVisit->TMJ ?? '-' }}</td>
                                                <td>{{ $ReasonForVisit->Lymphadenopathy ?? '-' }}</td>
                                                <td>{{ date('d-m-Y', strtotime($ReasonForVisit->date)) }}</td>

                                                <td>
                                                    <button type="button" class="btn btn-sm btn-primary edit-btn"
                                                        onclick="getEditData(<?= $ReasonForVisit->id ?>)"
                                                        data-bs-toggle="modal" data-bs-target="#editNoteModal">
                                                        Edit
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-primary delete-btn"
                                                        data-id="{{ $ReasonForVisit->id }}"
                                                        data-patient-id="{{ $ReasonForVisit->patient_id }}"
                                                        data-toggle="modal" data-target="#deleteRecordModal">
                                                        Delete
                                                    </button>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $ReasonForVisitToday->links('pagination::bootstrap-4') }}
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
                    <h5 class="modal-title">Edit Reason For Visit Today</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="{{ route('ReasonForVisitToday.update') }}">
                        @csrf
                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                        <input type="hidden" name="id" id="reasonforvisit_id" value="">

                        <div class="mb-3">
                            <label for="comments" class="form-label">Reason For Visit Today</label>
                            <textarea name="comments" id="edit_comments" class="form-control">{{ old('comments') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_date" class="form-label">Date<span class="text-danger">*</span></label>
                            <input type="date" name="edit_date" id="edit_date" class="form-control"
                                value="{{ old('edit_date') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_facial_asymmetry" class="form-label">Facial Asymmetry</label>
                            <input type="text" name="edit_facial_asymmetry" id="edit_facial_asymmetry"
                                class="form-control" value="{{ old('edit_facial_asymmetry') }}">
                        </div>
                        <div class="mb-3">
                            <label for="edit_TMJ" class="form-label">TMJ</label>
                            <input type="text" name="edit_TMJ" id="edit_TMJ" class="form-control"
                                value="{{ old('edit_TMJ') }}">
                        </div>
                        <div class="mb-3">
                            <label for="edit_Lymphadenopathy" class="form-label">Lymphadenopathy</label>
                            <input type="text" name="edit_Lymphadenopathy" id="edit_Lymphadenopathy"
                                class="form-control" value="{{ old('edit_Lymphadenopathy') }}">
                        </div>


                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this payment?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <!-- Hidden input for the payment ID -->
                            <input type="hidden" name="id" id="deleteid" value="">
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
        function getEditData(id) {

            var url = "{{ route('ReasonForVisitToday.edit', ':id') }}".replace(':id', id);

            //url = url.replace(":id", id);
            if (id) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        id
                    },
                    success: function(data) {

                        //var obj = JSON.parse(data);

                        $("#edit_facial_asymmetry").val(data.facial_asymmetry);
                        $("#edit_TMJ").val(data.TMJ);
                        $("#edit_Lymphadenopathy").val(data.Lymphadenopathy);
                        $("#edit_date").val(data.date ? data.date.substring(0, 10) : '');
                        $("#edit_comments").val(data.comment);
                        $('#reasonforvisit_id').val(id);
                    },
                    error: function(xhr) {
                        alert('Failed to load data');
                    }
                });
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            $(".delete-btn").on("click", function() {
                let id = $(this).data("id");
                let patientId = $(this).data("patient-id");

                // Set the delete form action to the payments.destroy route
                let actionUrl = "{{ route('ReasonForVisitToday.destroy', ':id') }}".replace(':id', id);

                // Set the form action dynamically
                $("#deleteForm").attr("action", actionUrl);

                // Open the modal
                $("#deleteRecordModal").modal("show");
            });

            // Confirm Delete Button Click (optional if you want a separate confirm button, otherwise remove this part)
            $("#confirmDelete").on("click", function() {
                $("#deleteForm").submit();
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
            document.getElementById('date').value = today; // Set today's date as the value
        });
    </script>
@endsection
