@extends('layouts.app')

@section('title', 'Pay To Dr List')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="d-flex justify-content-between align-items-center m-3">
                   <h5 class="mb-0">
                        Name: {{ $patient->name }} | Mobile No 1: {{ $patient->mobile1 }}
                        @if($patient->mobile2 != '')
                        | Mobile No 2: {{ $patient->mobile2 }}
                        @endif
                        | Case No: {{ $patient->case_no }}
                   </h5>                    <a href="{{ route('patient.index') }}" class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                    </a>
                </div>

                {{-- Alert Messages --}}
                @include('common.alert')
                @include('patient.show', ['id' => $patient->id]) <!-- ✅ Patient details included -->


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
                                <h5 class="card-title mb-0">Add Pay To Dr</h5>
                            </div>

                            <div class="card-body">
                                <form action="{{ route('pay_to_dr.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                    <div class="mb-3">
                                        <label for="doctor_id" class="form-label">Doctor<span
                                                class="text-danger">*</span></label>
                                        <select name="doctor_id" class="form-control" required autofocus>
                                            <option value="" selected>Select Doctor</option>
                                            @foreach ($doctors->sortBy('doctor_name') as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->doctor_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Amount <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="amount"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="6"
                                            placeholder="Enter Amount" required autocomplete="off">
                                    </div>
                                    <div class="mb-3">
                                        <label for="mode" class="form-label">Mode<span
                                                class="text-danger">*</span></label>
                                        <select name="mode" class="form-control" required>
                                            <option value="" disabled selected>Select Mode</option>
                                            <option value="0">Cash</option>
                                            <option value="1">Online</option>
                                        </select>
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
                                <h5 class="card-title mb-0">Pay To Dr List</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Doctor</th>
                                            <th>Amount</th>
                                            <th>Mode</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datas as $key => $data)
                                            <tr>
                                                <td>{{ ($datas->currentPage() - 1) * $datas->perPage() + $key + 1 }}
                                                <td>{{ $data->doctor_name }}</td>
                                                <td>{{ $data->amount }}</td>
                                                <td>{{ $data->mode == 0 ? 'Cash' : 'Online' }}</td>
                                                <td>
                                                    <a class="btn btn-sm btn-primary edit-btn" title="Edit" href="#"
                                                        onclick="getEditData({{ $data->id }})" data-bs-toggle="modal"
                                                        data-bs-target="#editDosageModal">
                                                        Edit
                                                    </a>

                                                    <a class="btn btn-sm btn-primary delete-dosage" href="#"
                                                        data-bs-toggle="modal" title="Delete"
                                                        data-bs-target="#deleteRecordModal"
                                                        onclick="deleteData(<?= $data->id ?>);">
                                                        Delete
                                                    </a>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $datas->links('pagination::bootstrap-4') }}
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
                    <h5 class="modal-title" id="editDosageModalLabel">Edit Pay To Dr</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('pay_to_dr.update') }}" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id" id="pay_to_dr_id" value="">

                        <div class="mb-3">
                            <label for="doctor_id" class="form-label">Doctor<span class="text-danger">*</span></label>
                            <select name="doctor_id" id="Editdoctor_id" class="form-control" required autofocus>
                                <option value="" selected>Select Doctor</option>
                                @foreach ($doctors->sortBy('doctor_name') as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->doctor_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Amount <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="amount" id="Editamount"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="6" maxlength="5"
                                placeholder="Enter Amount" required>
                        </div>

                        <div class="mb-3">
                            <label for="mode" class="form-label">Mode<span class="text-danger">*</span></label>
                            <select name="mode" id="Editmode" class="form-control" required autofocus>
                                <option value="" selected>Select Mode</option>
                                <option value="0">Cash</option>
                                <option value="1">Online</option>
                            </select>
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
                            <p class="text-muted mx-4 mb-0">Are you Sure You want to Remove this Record
                                ?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <a class="btn btn-primary" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('user-delete-form').submit();">
                            Yes,
                            Delete It!
                        </a>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                        <form id="user-delete-form" method="POST" action="{{ route('pay_to_dr.destroy') }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" id="deleteid" value="">

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
        function deleteData(id) {
            $("#deleteid").val(id);
        }
    </script>

    <script>
        function getEditData(id) {

            var url = "{{ route('pay_to_dr.edit', ':id') }}";
            url = url.replace(":id", id);
            if (id) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        id,
                        id
                    },
                    success: function(data) {

                        var obj = JSON.parse(data);
                        $("#Editdoctor_id").val(obj.doctor_id);
                        $("#Editamount").val(obj.amount);
                        $("#Editmode").val(obj.mode);
                        $('#pay_to_dr_id').val(id);
                    }
                });
            }
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function validateDosage(inputElement, errorElement) {
                inputElement.addEventListener("input", function() {
                    // Remove any invalid characters (only allow numbers and hyphens)
                    this.value = this.value.replace(/[^0-9-]/g, '');

                    // Ensure the format is strictly X-X-X (5 characters)
                    const regex = /^[0-9]-[0-9]-[0-9]$/;
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
                    const regex = /^[0-9.]$/;

                    if (!regex.test(dosageInput.value)) {
                        e.preventDefault();
                        alert("Invalid dosage format! Please enter in '1-1-1' format.");
                    }
                });
            });
        });
    </script>

@endsection
