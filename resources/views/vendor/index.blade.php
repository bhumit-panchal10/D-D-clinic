@extends('layouts.app')
@section('title', 'Vendor List')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            @include('common.alert')

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Vendor List</h5>
                    <a href="{{ route('vendor.create') }}" class="btn btn-sm btn-primary">+ Add Vendor</a>
                </div>

                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Company Name</th>
                                <th>Contact Person</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vendors as $key => $vendor)
                            <tr>
                                <td>{{ ($vendors->currentPage() - 1) * $vendors->perPage() + $key + 1 }}</td>
                                <td>{{ $vendor->company_name }}</td>
                                <td>{{ $vendor->contact_person_name }}</td>
                                <td>{{ $vendor->mobile }}</td>
                                <td>{{ $vendor->email ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('vendor.edit', ['id' => $vendor->id, 'page' => request()->input('page', 1)]) }}" class="btn btn-sm btn-primary">Edit</a>

                                    <button class="btn btn-sm btn-primary delete-vendor" data-id="{{ $vendor->id }}"
                                        data-bs-toggle="modal" data-bs-target="#deleteRecordModal">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $vendors->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
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
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this vendor?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-primary" id="confirmDelete">Yes, Delete It!</button>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="vendor_id" id="deleteid" value="">
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
$(document).ready(function() {
    // Delete vendor Modal
    $(".delete-vendor").on("click", function () {
                let id = $(this).data("id");
                $("#deleteid").val(id); // Set vendor ID in hidden input
            });

            // Confirm Delete Button Click
            $("#confirmDelete").on("click", function () {
                let id = $("#deleteid").val();
                let actionUrl = "{{ route('vendor.destroy', ':id') }}".replace(':id', id);
                $("#deleteForm").attr("action", actionUrl);
                $("#deleteForm").submit();
            });
});
</script>
@endsection
