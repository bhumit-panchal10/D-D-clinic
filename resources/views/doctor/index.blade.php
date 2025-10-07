@extends('layouts.app')
@section('title', 'Doctors List')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <div class="col-lg-5">
                        <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="card-title mb-0">Add Doctor</h5>
                        </div>

                        <div class="card-body">
                            <form id="addDoctorForm" action="{{ route('doctors.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label>Doctor Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="doctor_name" minlength="3" maxlength="100" placeholder="Enter Doctor Name" required>
                                </div>

                                <div class="mb-3">
                                    <label>Mobile <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="mobile" minlength="10" maxlength="10"
                                        placeholder="Enter Mobile" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                                </div>

                                <div class="mb-3">
                                    <label>Address</label>
                                    <textarea class="form-control" name="address" maxlength="255" placeholder="Enter Address"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label>Pincode</label>
                                    <input type="text" class="form-control" name="pincode" id="pincode" 
                                        pattern="[0-9]{6}" minlength="6" maxlength="6" 
                                        placeholder="Enter Pincode" 
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                                <h5 class="card-title mb-0">Doctor List</h5>
                            </div>

                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Doctor Name</th>
                                            <th>Mobile</th>
                                            <th>Pincode</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($doctors as $key => $doctor)
                                            <tr>
                                                <td>{{ ($doctors->currentPage() - 1) * $doctors->perPage() + $key + 1 }}
                                                <td>{{ $doctor->doctor_name }}</td>
                                                <td>{{ $doctor->mobile }}</td>
                                                <td>{{ $doctor->pincode ?? '-' }}</td>
                                                <td>
                                                <button class="btn btn-sm btn-primary edit-doctor" data-id="{{ $doctor->id }}" 
                                                    data-name="{{ $doctor->doctor_name }}" 
                                                    data-mobile="{{ $doctor->mobile }}"
                                                    data-address="{{ $doctor->address }}"
                                                    data-pincode="{{ $doctor->pincode }}">
                                                    Edit
                                                </button>
                                                    <button class="btn btn-sm btn-primary delete-doctor" data-id="{{ $doctor->id }}"
                                                        data-bs-toggle="modal" data-bs-target="#deleteRecordModal">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="d-flex justify-content-center mt-3">
                                    {{ $doctors->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Edit Doctor Modal Start -->
<div class="modal fade zoomIn" id="editDoctorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Doctor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editDoctorForm" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="editDoctorId">

                    <div class="mb-3">
                        <label>Doctor Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="doctor_name" id="editDoctorName" minlength="3" maxlength="100" required>
                    </div>

                    <div class="mb-3">
                        <label>Mobile <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="mobile" id="editDoctorMobile" minlength="10" maxlength="10" required>
                    </div>

                    <div class="mb-3">
                        <label>Address</label>
                        <textarea class="form-control" name="address" id="editDoctorAddress" maxlength="255"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Pincode</label>
                        <input type="text" class="form-control" name="pincode" id="editDoctorPincode" 
                            minlength="6" maxlength="6" pattern="[0-9]{6}" 
                            placeholder="Enter Pincode" 
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
<!-- Edit Doctor Modal End -->


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
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this doctor?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-primary" id="confirmDelete">Yes, Delete It!</button>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="doctor_id" id="deleteid" value="">
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
            // Get the current page number from the URL
            let currentPage = new URLSearchParams(window.location.search).get("page") || 1;

            // Edit doctor modal
            $(".edit-doctor").on("click", function () {
                    let id = $(this).data("id");
                    let name = $(this).data("name");
                    let mobile = $(this).data("mobile");
                    let address = $(this).data("address") || '';
                    let pincode = $(this).data("pincode") || '';

                    $("#editDoctorId").val(id);
                    $("#editDoctorName").val(name);
                    $("#editDoctorMobile").val(mobile);
                    $("#editDoctorAddress").val(address);
                    $("#editDoctorPincode").val(pincode);

                    let actionUrl = "{{ route('doctors.store') }}?page=" + currentPage;
                    $("#editDoctorForm").attr("action", actionUrl);

                    $("#editDoctorModal").modal("show");
                });

           // Delete doctor Modal
           $(".delete-doctor").on("click", function () {
                let id = $(this).data("id");
                $("#deleteid").val(id); // Set doctor ID in hidden input
            });

            // Confirm Delete Button Click
            $("#confirmDelete").on("click", function () {
                let id = $("#deleteid").val();
                let actionUrl = "{{ route('doctors.destroy', ':id') }}".replace(':id', id);
                $("#deleteForm").attr("action", actionUrl);
                $("#deleteForm").submit();
            });
        });
    </script>
@endsection