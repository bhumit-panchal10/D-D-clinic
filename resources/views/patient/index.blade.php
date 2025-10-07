@extends('layouts.app')
@section('title', 'Patients List')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')



                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Patient List</h5>

                                <div class="d-flex align-items-center gap-2">
                                     <form action="{{ route('patient.index') }}" method="GET"
                                        class="d-flex align-items-center gap-2 position-relative">
                                        <input type="text" name="search" id="patient-search"
                                            value="{{ request()->search }}" class="form-control form-control-sm"
                                            placeholder="Search by Name or Mobile" autocomplete="off">
                                        <button type="submit" class="btn btn-sm btn-primary">Search</button>
                                        @if (request()->has('search'))
                                            <a href="{{ route('patient.index') }}" class="btn btn-sm btn-primary">Clear</a>
                                        @endif

                                        <ul id="search-suggestions" class="list-group position-absolute"
                                            style="top: 38px; z-index: 1000; width: 100%; display: none;"></ul>
                                    </form>

                                    <a href="{{ route('patient.create') }}" class="btn btn-primary btn-sm">+ Add
                                        Patient</a>
                                </div>
                            </div>


                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Case No</th>
                                            <th>Patient Name</th>
                                            <th>Mobile</th>
                                            <th>Mobile - 2</th>
                                            <th>DOB</th>
                                            <th>Gender</th>
                                            <th>Entry Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                         <?php $i = 1; ?>
                                        @foreach ($patients as $key => $patient)
                                            <tr>
                                                <!--<td>{{ $key + 1 }}</td>-->
                                                <td>
                                                    {{ $i + $patients->perPage() * ($patients->currentPage() - 1) }}
                                                </td>
                                                <td>{{ $patient->case_no }}</td>
                                                <td>
                                                    <a href="{{ route('patient_treatments.index', $patient->id) }}"
                                                        class="text-primary">
                                                        {{ $patient->name }}
                                                    </a>
                                                </td>
                                                <td>{{ $patient->mobile1 }}</td>
                                                <td>{{ $patient->mobile2 ?? '-' }}</td>

                                                <td>
                                                    {{ $patient->dob && $patient->dob !== '0000-00-00' ? date('d-m-Y', strtotime($patient->dob)) : '-' }}
                                                </td>

                                                <td>

                                                {{ $patient->gender ?? '-' }}

                                                </td>
                                                 <td>
                                                    {{ $patient->created_at && strtotime($patient->created_at)
                                                        ? date('d-m-Y', strtotime($patient->created_at))
                                                        : '-' }}
                                                </td>

                                                <td>
                                                    <a href="{{ route('patient.edit', $patient->id) }}"
                                                        class="btn btn-sm btn-primary">Edit</a>
                                                    <button class="btn btn-sm btn-primary delete-patient"
                                                        data-id="{{ $patient->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#deleteRecordModal">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php $i++; ?>
                                        @endforeach
                                    </tbody>
                                </table>
                                
                                 <div class="d-flex justify-content-between">
                                    <form class="">
                                        Pagination : &nbsp;<select id="pagination">
                                            {{-- <option value="">Page</option> --}}
                                            <option value="20" @if (isset($items) && $items == 20) selected @endif>20
                                            </option>
                                            <option value="50" @if (isset($items) && $items == 50) selected @endif>50
                                            </option>
                                            <option value="100" @if (isset($items) && $items == 100) selected @endif>100
                                            </option>
                                            <option value="200" @if (isset($items) && $items == 200) selected @endif>200
                                            </option>
                                            <option value="500" @if (isset($items) && $items == 500) selected @endif>500
                                            </option>
                                        </select>
                                    </form>
                                    {{ $patients->appends(Request::except('page'))->links() }}

                                </div>

                                <!--<div class="d-flex justify-content-center mt-3">-->
                                <!--    {{ $patients->appends(request()->query())->links('pagination::bootstrap-4') }}-->
                                <!--</div>-->
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- container-fluid -->
        </div> <!-- page-content -->
    </div> <!-- main-content -->


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
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this patient?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-primary" id="confirmDelete">Yes, Delete It!</button>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="patient_id" id="deleteid" value="">
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
            // Autocomplete for Patient search
            $('#patient-search').on('keyup', function() {
                let query = $(this).val();
                if (query.length >= 3) {
                    $.ajax({
                        url: "{{ route('patient.autocomplete') }}",
                        type: "GET",
                        data: {
                            search: query
                        },
                        success: function(response) {
                            let suggestions = $('#search-suggestions');
                            suggestions.empty().show();
                            if (response.length > 0) {
                                response.forEach(patient => {
                                    suggestions.append(
                                        `<li class="list-group-item suggestion-item" style="cursor:pointer;" data-value="${patient.name}">${patient.name} (${patient.mobile1})</li>`
                                    );
                                });
                            } else {
                                suggestions.append(
                                    `<li class="list-group-item disabled">No results found</li>`
                                );
                            }
                        }
                    });
                } else {
                    $('#search-suggestions').hide().empty();
                }
            });

            // Click on suggestion
            $(document).on('click', '.suggestion-item', function() {
                let value = $(this).data('value');
                $('#patient-search').val(value);
                $('#search-suggestions').hide();
            });

            // Hide suggestion list on blur
            $('#patient-search').on('blur', function() {
                setTimeout(() => $('#search-suggestions').hide(), 150); // Delay to allow click event
            });

            // Delete confirmation logic (existing)
            $(".delete-patient").on("click", function() {
                let id = $(this).data("id");
                $("#deleteid").val(id);
            });

            $("#confirmDelete").on("click", function() {
                let id = $("#deleteid").val();
                let actionUrl = "{{ route('patient.destroy', ':id') }}".replace(':id', id);
                $("#deleteForm").attr("action", actionUrl);
                $("#deleteForm").submit();
            });

            document.getElementById('pagination').onchange = function() {
                var url = "{!! $patients->url(0) !!}&items=" + this.value;
                window.location.href = url;
            };
        });
    </script>
@endsection


<!--@section('scripts')-->
<!--    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
<!--    <script>-->
<!--        $(document).ready(function() {-->
<!--            $(".delete-patient").on("click", function() {-->
<!--                let id = $(this).data("id");-->
<!--                $("#deleteid").val(id);-->
<!--            });-->

            // Confirm Delete Button Click
<!--            $("#confirmDelete").on("click", function() {-->
<!--                let id = $("#deleteid").val();-->
<!--                let actionUrl = "{{ route('patient.destroy', ':id') }}".replace(':id', id);-->
<!--                $("#deleteForm").attr("action", actionUrl);-->
<!--                $("#deleteForm").submit();-->
<!--            });-->
<!--        });-->
<!--    </script>-->
<!--@endsection-->
