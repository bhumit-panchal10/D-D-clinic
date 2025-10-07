@extends('layouts.app')

@section('title', 'Dosage List')

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
                                <h5 class="card-title mb-0">Add Dosage</h5>
                            </div>

                            <div class="card-body">
                                <form action="{{ route('dosage.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label>Dosage <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="dosage" minlength="5"
                                            maxlength="7" pattern="^[0-9]-[0-9]-[0-9]-[0-9]$"
                                            placeholder="Enter Dosage (e.g., 1-1-1)" required>
                                        <small class="text-danger d-none" id="dosageError">Invalid format! Use 1-1-1
                                            format.</small>
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
                                <h5 class="card-title mb-0">Dosage List</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Dosage</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dosages as $key => $dosage)
                                            <tr>
                                                <td>{{ ($dosages->currentPage() - 1) * $dosages->perPage() + $key + 1 }}
                                                <td>{{ $dosage->dosage }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary edit-btn"
                                                        data-id="{{ $dosage->id }}" data-dosage="{{ $dosage->dosage }}"
                                                        data-bs-toggle="modal" data-bs-target="#editDosageModal">
                                                        Edit
                                                    </button>
                                                    <button class="btn btn-sm btn-primary delete-dosage"
                                                        data-id="{{ $dosage->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#deleteRecordModal">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $dosages->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Modal -->
    <div class="modal fade" id="editDosageModal" tabindex="-1" aria-labelledby="editDosageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDosageModalLabel">Edit Dosage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editDosageForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label>Dosage <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="dosage" id="editDosageInput" minlength="5"
                                maxlength="7" pattern="^[0-9]-[0-9]-[0-9]-[0-9]$" placeholder="Enter Dosage (e.g., 1-1-1)"
                                required>
                            <small class="text-danger d-none" id="editDosageError">Invalid format! Use 1-1-1 format.</small>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                </div>
                </form>
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
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this dosage?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-primary" id="confirmDelete">Yes, Delete It!</button>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="dosage_id" id="deleteid" value="">
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

            // Handle Edit Button Click
            $(".edit-btn").on("click", function() {
                let id = $(this).data("id");
                let dosage = $(this).data("dosage"); // Fetch dosage correctly

                // Set the fetched dosage in the input field
                $("#editDosageInput").val(dosage);

                // Update form action URL dynamically
                let actionUrl = "{{ route('dosage.update', ':id') }}".replace(':id', id) + "?page=" +
                    currentPage;
                $("#editDosageForm").attr("action", actionUrl);
            });

            // Delete dosage Modal
            $(".delete-dosage").on("click", function() {
                let id = $(this).data("id");
                $("#deleteid").val(id); // Set dosage ID in hidden input
            });

            // Confirm Delete Button Click
            $("#confirmDelete").on("click", function() {
                let id = $("#deleteid").val();
                let actionUrl = "{{ route('dosage.destroy', ':id') }}".replace(':id', id) + "?page=" +
                    currentPage;
                $("#deleteForm").attr("action", actionUrl);
                $("#deleteForm").submit();
            });
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function validateDosage(inputElement, errorElement) {
                inputElement.addEventListener("input", function() {
                    // Remove any invalid characters (only allow numbers and hyphens)
                    this.value = this.value.replace(/[^0-9-]/g, '');

                    // Ensure the format is strictly X-X-X (5 characters)
                    const regex = /^[0-9]-[0-9]-[0-9]-[0-9]$/;
                    if (!regex.test(inputElement.value)) {
                        errorElement.classList.remove("d-none");
                    } else {
                        errorElement.classList.add("d-none");
                    }
                });
            }

            // Apply validation to Add & Edit form fields
            validateDosage(document.querySelector("input[name='dosage']"), document.getElementById("dosageError"));
            validateDosage(document.getElementById("editDosageInput"), document.getElementById("editDosageError"));

            // Prevent form submission if input is invalid
            document.querySelectorAll("form").forEach(form => {
                form.addEventListener("submit", function(e) {
                    const dosageInput = form.querySelector("input[name='dosage']");
                    const regex = /^[0-9]-[0-9]-[0-9]-[0-9]$/;

                    if (!regex.test(dosageInput.value)) {
                        e.preventDefault();
                        alert("Invalid dosage format! Please enter in '1-1-1' format.");
                    }
                });
            });
        });
    </script>

@endsection
