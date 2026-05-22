@extends('layouts.app')

@section('title', 'Labwork')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="d-flex justify-content-between align-items-center m-3">
                    <h5 class="mb-0">
                        Name: {{ $patient->name }} {{ $patient->middle_name }} {{ $patient->last_name }} | Mobile No 1:
                        {{ $patient->mobile1 }} |
                        Age: @php
                            $age = $patient->Age ?? null;
                            $dob = $patient->dob ?? null;

                            if (!$age && $dob && $dob !== '0000-00-00') {
                                $age = \Carbon\Carbon::parse($dob)->age;
                            }
                        @endphp
                        {{ $age ? $age : '-' }}
                        {{-- @if ($patient->mobile2 != '')
                            | Mobile No 2: {{ $patient->mobile2 }}
                        @endif --}}
                        | Case No: {{ $patient->case_no }}
                    </h5>
                    <a href="{{ route('patient.index') }}" class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                    </a>
                </div>

                @include('common.alert')
                @include('patient.show', ['id' => $patient->id])

                <div class="row">
                    <!-- Add Note Section -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0">Add Labwork</h5>
                            </div>

                            <div class="card-body">

                                <form action="{{ route('labworks.store', $patient->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                    <div class="mb-3">
                                        <label for="mode" class="form-label">Lab<span
                                                class="text-danger">*</span></label>
                                        <select name="lab" id="lab" class="form-select" required>
                                            <option value="">--Please Select--</option>
                                            @foreach ($labs as $lab)
                                                <option value="{{ $lab->id }}">{{ $lab->lab_name }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="entry_date" name="entry_date"
                                            rows="3" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Collection Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="payment_date" name="given_date"
                                            rows="3" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="given_by" class="form-label">Given By</label>
                                        <input type="text" name="given_by" id="given_by" class="form-control"
                                            value="">
                                    </div>

                                    <div class="mb-3">
                                        <label for="work_code" class="form-label">Work Type</label>
                                        <textarea type="text" name="work_code" id="work_code" class="form-control"></textarea>
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
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Labwork List</h5>
                                <div class="d-flex justify-content-between align-items-center m-3">
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Date</th>
                                            <th>Lab</th>
                                            <th>Work Type</th>
                                            <th>Collection Date</th>
                                            <th>Given By</th>
                                            <th>Received Date</th>
                                            <th>Received By</th>
                                            <th>Job No</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($labworks as $key => $lab)
                                            <tr class="
    @if (\Carbon\Carbon::parse($lab->entry_date)->addDays(4)->lt(now())) table-danger @endif
">
                                                <td class="text-center">{{ $labworks->firstItem() + $key }}</td>
                                                <td>{{ date('d-m-Y', strtotime($lab->entry_date)) }}</td>
                                                <td>{{ $lab->lab->lab_name ?? '' }}</td>
                                                <td>{{ $lab->work_code ?? '' }}</td>
                                                <td>{{ date('d-m-Y', strtotime($lab->entry_date)) }}</td>
                                                <td>{{ $lab->given_by }}</td>
                                                <td>
                                                    {{ !empty($lab->received_date) ? date('d-m-Y', strtotime($lab->received_date)) : '' }}
                                                </td>
                                                <td>{{ $lab->received_by ?? '' }}</td>
                                                <td>{{ $lab->job_work_no ?? '' }}</td>
                                                <td>
                                                    <div class="d-flex gap-1">

                                                        <!-- Edit -->
                                                        <button type="button" class="btn btn-sm btn-primary"
                                                            onclick="getEditData({{ $lab->id }})"
                                                            data-bs-toggle="modal" data-bs-target="#editlabModal"
                                                            title="Edit">

                                                            <i class="fas fa-edit"></i>
                                                        </button>

                                                        <!-- Received -->
                                                        <button type="button" class="btn btn-sm btn-success"
                                                            onclick="getreceivedData({{ $lab->id }})"
                                                            data-bs-toggle="modal" data-bs-target="#receivedlabModal"
                                                            title="Received">

                                                            <i class="fas fa-check-circle"></i>
                                                        </button>

                                                        <!-- Delete -->
                                                        <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                            data-id="{{ $lab->id }}"
                                                            data-patient-id="{{ $patient->id }}" data-toggle="modal"
                                                            data-target="#deleteRecordModal" title="Delete">

                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $labworks->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Edit Note Modal -->
    <div class="modal fade" id="editlabModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Labwork</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="{{ route('labworks.update') }}">
                        @csrf
                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                        <input type="hidden" name="id" id="edit_labwork_id" value="">

                        <div class="mb-3">
                            <label for="entry_date" class="form-label">Date<span class="text-danger">*</span></label>
                            <input type="date" name="entrydate" id="entrydate" class="form-control"
                                value="{{ old('entry_date') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="mode" class="form-label">Lab<span class="text-danger">*</span></label>
                            <select name="lab" id="edit_lab" class="form-select" required>
                                <option value="">--Please Select--</option>
                                @foreach ($labs as $lab)
                                    <option value="{{ $lab->id }}">{{ $lab->lab_name }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="payment_date" class="form-label">Collection Date<span
                                    class="text-danger">*</span></label>
                            <input type="date" name="date" id="edit_date" class="form-control"
                                value="{{ old('date') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="given_by" class="form-label">Given By<span class="text-danger">*</span></label>
                            <input type="text" name="given_by" id="edit_given_by" class="form-control"
                                value="{{ old('given_by') }}">
                        </div>

                        <div class="mb-3">
                            <label for="work_code" class="form-label">Work Code</label>
                            <textarea name="work_code" id="edit_work_code" class="form-control">{{ old('work_code') }}</textarea>
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

    <div class="modal fade" id="receivedlabModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Received Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="{{ route('labworks.received') }}">
                        @csrf
                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                        <input type="hidden" name="id" id="received_labwork_id" value="{{ $lab->id }}">

                        <div class="mb-3">
                            <label for="work_code" class="form-label">Job Work Code</label>
                            <input type="text" name="job_work_code" id="job_work_code"
                                class="form-control">{{ old('job_work_code') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="received_date" class="form-label">Received Date<span
                                    class="text-danger">*</span></label>
                            <input type="date" name="received_date" id="edit_received_date" class="form-control"
                                value="{{ old('received_date') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="received_by" class="form-label">Received By<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="received_by" id="edit_received_by" class="form-control"
                                value="{{ old('received_by') }}">
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
        function getreceivedData(id) {

            var url = "{{ route('labworks.edit', ':id') }}";
            url = url.replace(":id", id);

            $.ajax({
                url: url,
                type: 'GET',

                success: function(obj) {
                    console.log(obj);
                    $('#received_labwork_id').val(obj.id);

                    $('#job_work_code').val(obj.job_work_no);

                    $('#edit_received_date').val(obj.received_date.split(' ')[0]);
                    $('#edit_received_by').val(obj.received_by);
                },

                error: function(xhr) {
                    alert('Failed to load data');
                }
            });
        }
    </script>
    {{-- <script>
        function getreceivedData(id) {

            $('#received_labwork_id').val(id);

        }
    </script> --}}
    <script>
        function getEditData(id) {

            var url = "{{ route('labworks.edit', ':id') }}";
            url = url.replace(":id", id);
            if (id) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        id
                    },
                    success: function(obj) {
                        $("#edit_date").val(obj.entry_date);
                        $("#edit_given_by").val(obj.given_by);
                        $("#edit_work_code").val(obj.work_code);
                        $("#edit_lab").val(obj.lab_id);
                        $("#entrydate").val(obj.entry_date);
                        $('#edit_labwork_id').val(id);
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
                let actionUrl = "{{ route('labworks.destroy', ':id') }}".replace(':id', id);

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
            let today = new Date().toISOString().split('T')[0];
            document.getElementById('payment_date').value = today;
            document.getElementById('entry_date').value = today;
        });
    </script>
@endsection
