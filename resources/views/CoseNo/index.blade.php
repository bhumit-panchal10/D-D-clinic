@extends('layouts.app')

@section('title', 'CaseNo List')

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

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">CaseNo List</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Prefix</th>
                                            <th>Postfix</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($CaseNos as $key => $case)
                                            <tr>
                                                <td>{{ ($CaseNos->currentPage() - 1) * $CaseNos->perPage() + $key + 1 }}
                                                </td>

                                                <td>
                                                    {{ $case->prefix ?? '' }}



                                                </td>
                                                <td>{{ $case->postfix ?? '' }}</td>


                                                <td>
                                                    <button class="btn btn-sm btn-primary edit-btn" data-bs-toggle="modal"
                                                        data-bs-target="#editcaseModal"
                                                        onclick="getEditData(<?= $case->id ?>)">
                                                        Edit
                                                    </button>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $CaseNos->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Edit Doctor Modal Start -->
    <div class="modal fade zoomIn" id="editcaseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Case No</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('Caseno.update') }}" id="editDoctorForm" method="POST">
                        @csrf
                        <input type="hidden" name="caseid" id="caseid">

                        <div class="mb-3">
                            <label>Prefix<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="prefix" id="editprefix" minlength="3"
                                maxlength="50" required>
                        </div>

                        <div class="mb-3">
                            <label>Postfix<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="postfix" id="editpostfix" minlength="3"
                                maxlength="50" required>
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


@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function getEditData(id) {

            var url = "{{ route('Caseno.edit', ':id') }}";
            url = url.replace(":id", id);
            if (id) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        id
                    },
                    success: function(data) {
                        var obj = JSON.parse(data);

                        $("#editprefix").val(obj.prefix);
                        $("#editpostfix").val(obj.postfix);
                        $("#caseid").val(obj.id);
                    },
                    error: function(xhr) {
                        alert('Failed to load data');
                    }
                });
            }
        }
    </script>



@endsection
