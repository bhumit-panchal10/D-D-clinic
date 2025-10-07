@extends('layouts.app')

@section('title', 'Diagnosis List')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
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

                <div class="row">
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0">Add Diagnosis</h5>
                            </div>

                            <div class="card-body">
                                <form action="{{ route('Diagnosis.store') }}" method="POST" id="createTreatmentForm">
                                    @csrf

                                    <div class="mb-3">
                                        <label>Type <span class="text-danger">*</span></label>
                                        <select class="form-control" name="type" required>
                                            <option value="1">General</option>
                                            <option value="2">Local</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>Diagnosis Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="Diagnosis_name" maxlength="30"
                                            placeholder="Enter Diagnosis Name" required>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                        <button type="reset" class="btn btn-primary">Clear</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Diagnosis List</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Diagnosis Name</th>
                                            <th>Type</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($Diagnosis as $key => $Diag)
                                            <tr>
                                                <td>{{ ($Diagnosis->currentPage() - 1) * $Diagnosis->perPage() + $key + 1 }}
                                                </td>
                                                <td>{{ $Diag->Diagnosis_name }}</td>
                                                <td>
                                                    @if ($Diag->type == 1)
                                                        General
                                                    @elseif($Diag->type == 2)
                                                        Local
                                                    @else
                                                        Unknown
                                                    @endif
                                                </td>

                                                <td>
                                                    <button class="btn btn-sm btn-primary edit-btn"
                                                        data-id="{{ $Diag->id }}"
                                                        data-name="{{ $Diag->Diagnosis_name }}"
                                                        data-type="{{ $Diag->type }}" data-bs-toggle="modal"
                                                        data-bs-target="#editDiagnosisModal">
                                                        Edit
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-primary delete-btn"
                                                        data-id="{{ $Diag->id }}">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $Diagnosis->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Edit Treatment Modal --}}
    <div class="modal fade" id="editDiagnosisModal" tabindex="-1" aria-labelledby="editDiagnosisModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDiagnosisModalLabel">Edit Diagnosis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editDiagnosisForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label>Type <span class="text-danger">*</span></label>
                            <select class="form-control" name="type" id="edit_type" required>
                                <option value="1">General</option>
                                <option value="2">Local</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Diagnosis Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="Diagnosis_name" id="edit_diagnosis_name"
                                required>
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
                            colors="primary:#f7b84b,secondary:#f06548" style="width : 100px; height : 100px">
                        </lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you Sure?</h4>
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Diagnosis?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-primary" id="confirmDelete">Yes, Delete It!</button>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="DiagnosisId" id="deleteid" value="">
                        </form>
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
            // Get the current page number from the URL
            let currentPage = new URLSearchParams(window.location.search).get("page") || 1;

            // Handle delete Button Click
            $(".delete-btn").on("click", function() {
                let id = $(this).data("id");
                $("#deleteid").val(id);


                let deleteUrl = "{{ route('Diagnosis.destroy', ':id') }}".replace(':id', id) + "?page=" +
                    currentPage;
                $("#deleteForm").attr("action", deleteUrl);

                $("#deleteRecordModal").modal("show");
            });

            $("#confirmDelete").on("click", function() {
                $("#deleteForm").submit();
            });


            // Handle Edit Button Click
            $(".edit-btn").on("click", function() {

                let id = $(this).data("id");
                let name = $(this).data("name");
                let type = $(this).data("type");

                $("#edit_diagnosis_name").val(name);
                $("#edit_type").val(type);

                let currentPage = new URLSearchParams(window.location.search).get("page") || 1;

                let actionUrl = "{{ route('Diagnosis.update', ':id') }}".replace(':id', id) + "?page=" +
                    currentPage;
                $("#editDiagnosisForm").attr("action", actionUrl);


            });

        });
    </script>
@endsection
