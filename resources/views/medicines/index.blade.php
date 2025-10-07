@extends('layouts.app')

@section('title', 'Medicines List')

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
                    <!-- Medicine Create Form -->
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0">Add Medicine</h5>
                            </div>

                            <div class="card-body">
                                <form action="{{ route('medicine.store') }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label>Medicine Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="medicine_name" maxlength="30"
                                            placeholder="Enter Medicine Name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Dosage <span class="text-danger">*</span></label>
                                        <select class="form-control" name="dosage_id" id="dosage_id" required>
                                            <option value="">Select Dosage</option>
                                            @foreach ($dosages as $dosage)
                                                <option value="{{ $dosage->id }}">{{ $dosage->dosage }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>Comment <span class="text-danger"></span></label>
                                        <textarea class="form-control" placeholder="Enter Comment" name="comment" rows="3"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label>Days<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="days" maxlength="10"
                                            placeholder="Enter Days" required>
                                    </div>


                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                        <button type="reset" class="btn btn-primary">Clear</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Medicine List -->
                    <div class="col-lg-7">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Medicine List</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Dosage</th>
                                            <th>Days</th>
                                            <th>Medicine Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($medicines as $key => $medicine)
                                            <tr>
                                                <td>{{ $medicines->firstItem() + $key }}</td>
                                                <td>{{ $medicine->dosage }}</td>
                                                <td>{{ $medicine->days }}</td>
                                                <td>{{ $medicine->medicine_name }}</td>
                                                <td>
                                                    <!-- Edit Button -->
                                                    <button class="btn btn-sm btn-primary edit-medicine"
                                                        data-id="{{ $medicine->id }}"
                                                        data-name="{{ $medicine->medicine_name }}"
                                                        data-dosage="{{ $medicine->dosage_id }}"
                                                        data-days="{{ $medicine->days }}"
                                                        data-comment="{{ $medicine->comment }}" data-bs-toggle="modal"
                                                        data-bs-target="#editMedicineModal">
                                                        Edit
                                                    </button>

                                                    <!-- Delete Button -->
                                                    <button class="btn btn-sm btn-primary delete-medicine"
                                                        data-id="{{ $medicine->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#deleteRecordModal">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $medicines->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Edit Medicine Modal -->
    <div class="modal fade" id="editMedicineModal" tabindex="-1" aria-labelledby="editMedicineModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMedicineModalLabel">Edit Medicine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editMedicineForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="medicine_id" id="editMedicineId">

                        <div class="mb-3">
                            <label>Medicine Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="medicine_name" id="editMedicineName"
                                maxlength="30" required>
                        </div>

                        <div class="mb-3">
                            <label>Dosage <span class="text-danger">*</span></label>
                            <select class="form-control" name="dosage_id" id="Editdosage_id" required>
                                <option value="">Select Dosage</option>
                                @foreach ($dosages as $dosage)
                                    <option value="{{ $dosage->id }}">{{ $dosage->dosage }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Comment <span class="text-danger"></span></label>
                            <textarea class="form-control" placeholder="Enter Comment" name="comment" id="Editcomment" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Days <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="days" id="editdays" maxlength="10"
                                required>
                        </div>



                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
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
                            <h4>Are you sure?</h4>
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this medicine?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <form id="deleteMedicineForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="medicine_id" id="deleteMedicineId">
                            <button type="submit" class="btn btn-primary">Yes, Delete It!</button>
                        </form>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Open Edit Modal and Fill Data 
            $(".edit-medicine").on("click", function() {
                let id = $(this).data("id");
                let name = $(this).data("name");
                let dosage = $(this).data("dosage");
                let comment = $(this).data("comment");
                let days = $(this).data("days");
                let currentPage = "{{ request('page', 1) }}";

                $("#editMedicineId").val(id);
                $("#editMedicineName").val(name);
                $("#Editdosage_id").val(dosage);
                $("#Editcomment").val(comment);
                $("#editdays").val(days);


                let actionUrl = "{{ route('medicine.update', ':id') }}".replace(':id', id) + "?page=" +
                    currentPage;
                $("#editMedicineForm").attr("action", actionUrl);
            });

            // Open Delete Modal and Set ID
            $(".delete-medicine").on("click", function() {
                let id = $(this).data("id");
                let currentPage = "{{ request('page', 1) }}";
                $("#deleteMedicineId").val(id);

                let actionUrl = "{{ route('medicine.destroy', ':id') }}".replace(':id', id) + "?page=" +
                    currentPage;
                $("#deleteMedicineForm").attr("action", actionUrl);
            });
        });
    </script>
@endsection
