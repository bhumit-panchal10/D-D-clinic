@extends('layouts.app')

@section('title', 'SubTreatments List')

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
                                <h5 class="card-title mb-0">Add SubTreatments</h5>
                            </div>

                            <div class="card-body">
                                <form action="{{ route('subtreatment.store') }}" method="POST" id="createTreatmentForm">
                                    @csrf


                                    <div class="mb-3">
                                        <label>Treatment <span class="text-danger">*</span></label>
                                        <select class="form-control" name="treatment_id" id="treatment_id" required>
                                            <option value="">Select Treatment</option>
                                            @foreach ($treatments as $treatment)
                                                <option value="{{ $treatment->id }}">{{ $treatment->treatment_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>Sub Treatment Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" maxlength="50"
                                            placeholder="Enter Sub Treatment Name" required>
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
                                <h5 class="card-title mb-0">Treatment List</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Name</th>
                                            <th>Treatment Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subtreatments as $key => $subtreatment)
                                            <tr>
                                                <td>{{ ($subtreatments->currentPage() - 1) * $subtreatments->perPage() + $key + 1 }}
                                                </td>
                                                <td>{{ $subtreatment->name }}</td>
                                                <td>{{ $subtreatment->treatment->treatment_name ?? '' }}</td>

                                                <td>
                                                    <button class="btn btn-sm btn-primary edit-btn"
                                                        data-id="{{ $subtreatment->sub_treatment_id }}"
                                                        data-treatment-id="{{ $subtreatment->treatment_id }}"
                                                        data-subtreatment-name="{{ $subtreatment->name }}"
                                                        data-bs-toggle="modal" data-bs-target="#editSubTreatmentModal">
                                                        Edit
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-primary delete-btn"
                                                        data-id="{{ $subtreatment->sub_treatment_id }}">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $subtreatments->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Edit Treatment Modal --}}
    <div class="modal fade" id="editSubTreatmentModal" tabindex="-1" aria-labelledby="editSubTreatmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSubTreatmentModalLabel">Edit Treatment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editSubTreatmentForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label>Treatment <span class="text-danger">*</span></label>
                            <select class="form-control" name="treatment_id" id="edit_treatment_id" required>
                                <option value="">Select Treatment</option>
                                @foreach ($treatments as $treatment)
                                    <option value="{{ $treatment->id }}">{{ $treatment->treatment_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Sub Treatment Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_subtreatment_name" name="name"
                                maxlength="50" placeholder="Enter Sub Treatment Name" required>
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
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this treatment?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-primary" id="confirmDelete">Yes, Delete It!</button>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="subtreatmentId" id="deleteid" value="">
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


                let deleteUrl = "{{ route('subtreatment.destroy', ':id') }}".replace(':id', id) +
                    "?page=" +
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
                let treatmentId = $(this).data("treatment-id");
                let subName = $(this).data("subtreatment-name");

                $("#edit_treatment_id").val(String(treatmentId)); // match option value
                $("#edit_subtreatment_name").val(subName);


                let actionUrl = "{{ route('subtreatment.update', ':id') }}".replace(':id', id) + "?page=" +
                    currentPage;

                $("#editSubTreatmentForm").attr("action", actionUrl);
            });

        });
    </script>
@endsection
