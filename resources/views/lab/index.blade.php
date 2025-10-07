@extends('layouts.app')

@section('title', 'Labs List')

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
                                <h5 class="card-title mb-0">Add Lab</h5>
                            </div>

                            <div class="card-body">
                                <form action="{{ route('lab.store') }}" method="POST" id="createLabForm">
                                    @csrf

                                    <div class="mb-3">
                                        <label>Lab Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="lab_name" maxlength="50"
                                            placeholder="Enter Lab Name" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Contact Person <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="contact_person" maxlength="30"
                                            placeholder="Enter Contact Person" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Mobile <span class="text-danger">*</span></label>
                                        <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                            class="form-control" name="mobile" minlength="10" maxlength="10"
                                            placeholder="Enter Mobile" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Address</label>
                                        <textarea class="form-control" name="address" maxlength="255"
                                            placeholder="Enter Address"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="email" maxlength="30"
                                            placeholder="Enter Email">
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
                                <h5 class="card-title mb-0">Lab List</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Lab Name</th>
                                            <th>Contact Person</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($labs as $key => $lab)
                                            <tr>
                                                <td>{{ ($labs->currentPage() - 1) * $labs->perPage() + $key + 1 }}</td>
                                                <td>{{ $lab->lab_name }}</td>
                                                <td>{{ $lab->contact_person }}</td>
                                                <td>{{ $lab->mobile }}</td>
                                                <td>{{ $lab->email ?? '-' }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary edit-lab" data-id="{{ $lab->id }}"
                                                        data-lab_name="{{ $lab->lab_name }}"
                                                        data-contact_person="{{ $lab->contact_person }}"
                                                        data-mobile="{{ $lab->mobile }}" data-address="{{ $lab->address }}"
                                                        data-email="{{ $lab->email }}" data-bs-toggle="modal"
                                                        data-bs-target="#editLabModal">
                                                        Edit
                                                    </button>

                                                    <button class="btn btn-sm btn-primary delete-lab" data-id="{{ $lab->id }}"
                                                        data-bs-toggle="modal" data-bs-target="#deleteRecordModal">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $labs->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Edit Lab Modal -->
    <div class="modal fade" id="editLabModal" tabindex="-1" aria-labelledby="editLabModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Lab</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editLabForm" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="lab_id" id="editLabId">

                        <div class="mb-3">
                            <label>Lab Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="lab_name" id="editLabName" required>
                        </div>

                        <div class="mb-3">
                            <label>Contact Person <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="contact_person" id="editContactPerson" required>
                        </div>

                        <div class="mb-3">
                            <label>Mobile <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="mobile" id="editMobile" required>
                        </div>

                        <div class="mb-3">
                            <label>Address</label>
                            <textarea class="form-control" name="address" id="editAddress"></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" id="editEmail">
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
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this lab?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-primary" id="confirmDelete">Yes, Delete It!</button>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="lab_id" id="deleteid" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Modal End -->


@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Get current page from URL
            let currentPage = new URLSearchParams(window.location.search).get('page') || 1;
            // Edit Lab
            $(".edit-lab").on("click", function () {
                let id = $(this).data("id");
                $("#editLabId").val(id);
                $("#editLabName").val($(this).data("lab_name"));
                $("#editContactPerson").val($(this).data("contact_person"));
                $("#editMobile").val($(this).data("mobile"));
                $("#editAddress").val($(this).data("address"));
                $("#editEmail").val($(this).data("email"));

                // Correctly set the update form action
                let actionUrl = "{{ route('lab.update', ':id') }}".replace(':id', id)+ "?page=" + currentPage;
                $("#editLabForm").attr("action", actionUrl);
            });

            // Delete Lab Modal
            $(".delete-lab").on("click", function () {
                let id = $(this).data("id");
                $("#deleteid").val(id); // Set lab ID in hidden input
            });

            // Confirm Delete Button Click
            $("#confirmDelete").on("click", function () {
                let id = $("#deleteid").val();
                let actionUrl = "{{ route('lab.destroy', ':id') }}".replace(':id', id) + "?page=" + currentPage;
                $("#deleteForm").attr("action", actionUrl);
                $("#deleteForm").submit();
            });
        });
    </script>
@endsection