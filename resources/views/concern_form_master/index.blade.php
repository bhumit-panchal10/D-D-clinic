@extends('layouts.app')

@section('title', 'Concern Form List')

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
                                <h5 class="card-title mb-0">Add ConcerForm</h5>
                            </div>

                            <div class="card-body">
                                <form action="{{ route('concern_form_master.store') }}" method="POST" id="createLabForm">
                                    @csrf

                                    <div class="mb-3">
                                        <label>Title<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="title" maxlength="50"
                                            placeholder="Enter Title" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Description<span class="text-danger">*</span></label>
                                        <textarea name="text" class="form-control" name="description" placeholder="Enter Description" required></textarea>

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
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($Concerforms as $key => $Concerform)
                                            <tr>
                                                <td>{{ ($Concerforms->currentPage() - 1) * $Concerforms->perPage() + $key + 1 }}
                                                </td>
                                                <td>{{ $Concerform->title }}</td>
                                                <td>{{ $Concerform->description }}</td>

                                                <td>

                                                    <a class="btn btn-sm btn-primary edit-concernform" title="Edit"
                                                        href="#" data-bs-toggle="modal" data-bs-target="#EditModal"
                                                        onclick="getEditData(<?= $Concerform->id ?>)">
                                                        Edit
                                                    </a>

                                                    <a class="btn btn-sm btn-primary mx-1" href="#"
                                                        data-bs-toggle="modal" title="Delete"
                                                        data-bs-target="#deleteRecordModal"
                                                        onclick="deleteData(<?= $Concerform->id ?>);">
                                                        Delete
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $Concerforms->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Edit Lab Modal -->
    <div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="editLabModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit ConcerForm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('concern_form_master.update') }}" method="POST">
                        @csrf

                        <input type="hidden" name="id" id="editconcernid" value="">

                        <div class="mb-3">
                            <label>Title<span class="text-danger">*</span></label>
                            <input type="text" name="title" id="edittitle" class="form-control" value=""
                                maxlength="50" placeholder="Enter Title" required>
                        </div>

                        <div class="mb-3">
                            <label>Description<span class="text-danger">*</span></label>
                            <textarea class="form-control" id="edit_description" name="description" placeholder="Enter Description" required></textarea>


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

    <!--Delete Modal Start -->
    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                            colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px">
                        </lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you Sure ?</h4>
                            <p class="text-muted mx-4 mb-0">Are you Sure You want to Remove this Record
                                ?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <a class="btn btn-success mx-2" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('user-delete-form').submit();">
                            Yes,
                            Delete It!
                        </a>
                        <button type="button" class="btn w-sm btn-success mx-2" data-bs-dismiss="modal">Close</button>
                        <form id="user-delete-form" method="POST" action="{{ route('concern_form_master.delete') }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" id="deleteid" value="">

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Delete modal End -->


@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function deleteData(id) {
            $("#deleteid").val(id);
        }
    </script>

    <script>
        function getEditData(id) {

            var url = "{{ route('concern_form_master.edit', ':id') }}";
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

                        $("#edittitle").val(obj.title);
                        $("#edit_description").val(obj.description);
                        $("#editconcernid").val(obj.id);
                    },
                    error: function(xhr) {
                        alert('Failed to load data');
                    }
                });
            }
        }

        function confirmSingleDelete(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel",
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: true,
                showCloseButton: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    fetch(`{{ route('concern_form_master.delete', ['id' => '__id__']) }}`.replace('__id__', id), {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show deleted message and then redirect
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Record deleted successfully.',
                                    icon: 'success',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'btn btn-success'
                                    },
                                    buttonsStyling: true
                                }).then(() => {
                                    window.location.href = '{{ route('concern_form_master.index') }}';
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'There was an issue deleting the record.',
                                    icon: 'error',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'btn btn-danger'
                                    },
                                    buttonsStyling: true
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Delete error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Something went wrong.',
                                icon: 'error',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                },
                                buttonsStyling: true
                            });
                        });
                }
            });
        }
    </script>
@endsection
