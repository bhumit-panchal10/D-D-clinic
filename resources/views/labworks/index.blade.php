@extends('layouts.app')

@section('title', 'Labwork Records')

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

                @include('common.alert')

                @if ($patient)
                    @include('patient.show', ['id' => $patient->id])
                @endif

                <div class="row">
                    <!-- Labwork List Section -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Labwork List</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr no.</th>
                                            <th>Lab</th>
                                            <th>Treatment</th>
                                            <th>Entry Date</th>
                                            <th>Comment</th>
                                            <th>Collection Date</th>
                                            <th>Received Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($labworks as $key => $labwork)
                                            <tr>
                                                <td>{{ $labworks->firstItem() + $key }}</td>
                                                <td>{{ $labwork->lab->lab_name }}</td>
                                                <td>{{ $labwork->treatment->treatment_name ?? 'N/A' }}</td>
                                                <td>{{ date('d-m-Y', strtotime($labwork->entry_date)) }}</td>
                                                <td>{{ $labwork->comment }}</td>
                                                <td>{{ $labwork->collection_date ? \Carbon\Carbon::parse($labwork->collection_date)->format('d-m-Y') : 'Pending' }}
                                                </td>
                                                <td>{{ $labwork->received_date ? \Carbon\Carbon::parse($labwork->received_date)->format('d-m-Y') : 'Pending' }}
                                                </td>
                                                <td>
                                                    <!-- Collected Button -->
                                                    <form action="{{ route('labworks.collected', $labwork->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary"
                                                            {{ $labwork->collection_date ? 'disabled' : '' }}>
                                                            {{ $labwork->collection_date ? 'Collected' : 'Mark as Collected' }}
                                                        </button>
                                                    </form>

                                                    <!-- Received Button -->
                                                    <form action="{{ route('labworks.received', $labwork->id) }}"
                                                        method="POST" class="d-inline received-form">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm btn-primary received-btn {{ $labwork->collection_date ? '' : 'd-none' }}"
                                                            {{ $labwork->received_date ? 'disabled' : '' }}>
                                                            {{ $labwork->received_date ? 'Received' : 'Mark as Received' }}
                                                        </button>
                                                    </form>

                                                    <button class="btn btn-sm btn-primary delete-btn"
                                                        data-id="{{ $labwork->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#deleteRecordModal">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $labworks->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
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
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this labwork?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-primary" id="confirmDelete">Yes, Delete It!</button>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="labwork_id" id="deleteid" value="">
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
            {{--  $(".collected-btn").on("click", function(e) {
                e.preventDefault();
                let form = $(this).closest("form");
                let collectedBtn = $(this);
                let receivedBtn = form.closest("td").find(".received-btn");
                let deleteBtn = form.closest("td").find(".delete-btn");

                collectedBtn.prop("disabled", true).text("Collected...");

                $.post(form.attr("action"), form.serialize(), function() {
                    collectedBtn.text("Collected");
                    receivedBtn.removeClass("d-none"); // Show "Mark as Received" button
                });
            });  --}}


            $(".received-btn").on("click", function(e) {
                e.preventDefault();
                let form = $(this).closest("form");
                let receivedBtn = $(this);

                receivedBtn.prop("disabled", true).text("Received...");
                form.submit();
            });

            $(".delete-btn").on("click", function() {
                let id = $(this).data("id");
                $("#deleteid").val(id);
            });

            // Confirm Delete Button Click
            $("#confirmDelete").on("click", function() {
                let id = $("#deleteid").val();
                let actionUrl = "{{ route('labworks.destroy', ':id') }}".replace(':id', id);
                $("#deleteForm").attr("action", actionUrl);
                $("#deleteForm").submit();
            });
        });
    </script>
@endsection
