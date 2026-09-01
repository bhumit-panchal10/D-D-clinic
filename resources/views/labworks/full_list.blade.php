@extends('layouts.app')

@section('title', 'All Labwork Records')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <h5 class="mb-3">All Labwork Records</h5>

                @include('common.alert')

                <div class="row">
                    <!-- Labwork Full List Section -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0">Labwork List</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr no.</th>
                                            <th>Patient Name/Consult Name</th>
                                            <th>Case No</th>
                                            <th>Lab</th>
                                            <!--<th>Treatment</th>-->
                                            <th>Entry Date</th>
                                            <th>Collection Date</th>
                                            <th>Received Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($labworks as $key => $labwork)
                                            <tr>
                                                <td>{{ $labworks->firstItem() + $key }}</td>
                                                <td>{{ $labwork->patient->name ?? $labwork->consult_name }}</td>
                                                <td>{{ $labwork->patient->case_no ?? '' }}</td>
                                                <td>{{ $labwork->lab->lab_name }}</td>
                                                <!--<td>{{ $labwork->treatment->treatment_name ?? 'N/A' }}</td>-->
                                                <td>{{ date('d-m-Y', strtotime($labwork->entry_date)) }}</td>
                                                <td>{{ $labwork->collection_date ? \Carbon\Carbon::parse($labwork->collection_date)->format('d-m-Y') : 'Pending' }}</td>
                                                <td>{{ $labwork->received_date ? \Carbon\Carbon::parse($labwork->received_date)->format('d-m-Y') : '' }}</td>
                                                <td>
                                                    <!-- Collected Button -->
                                                    <form action="{{ route('labworks.collected', $labwork->id) }}" method="POST" class="d-inline collected-form">
                                                        @csrf
                                                        <button type="button" class="btn btn-sm btn-primary collected-btn" 
                                                            {{ $labwork->collection_date ? 'disabled' : '' }}>
                                                            {{ $labwork->collection_date ? 'Collected' : 'Mark as Collected' }}
                                                        </button>
                                                    </form>

                                                    <!-- Received Button -->
                                                    <!--<form action="{{ route('labworks.received', $labwork->id) }}" method="POST" class="d-inline received-form">-->
                                                    <!--    @csrf-->
                                                    <!--    <button type="submit" class="btn btn-sm btn-primary received-btn {{ $labwork->collection_date ? '' : 'd-none' }}"-->
                                                    <!--        {{ $labwork->received_date ? 'disabled' : '' }}>-->
                                                    <!--        {{ $labwork->received_date ? 'Received' : 'Mark as Received' }}-->
                                                    <!--    </button>-->
                                                    <!--</form>-->
                                                       <button type="button" class="btn btn-sm btn-success"
                                                            onclick="getreceivedData({{ $labwork->id }})"
                                                            data-bs-toggle="modal" data-bs-target="#receivedlabModal"
                                                            title="Received">

                                                            <i class="fas fa-check-circle"></i>
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

<div class="modal fade" id="receivedlabModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Received Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="{{ route('Otherlabworks.received') }}">
                        @csrf

                        <input type="hidden" name="id" id="received_labwork_id">
                        <div class="mb-3">
                            <label for="work_code" class="form-label">Job Work Code</label>
                            <input type="text" name="job_work_code" id="job_work_code"
                                class="form-control">{{ old('job_work_code') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="received_date" class="form-label">Received Date<span
                                    class="text-danger">*</span></label>
                            <input type="date" name="received_date" id="edit_received_date" class="form-control"
                                value="{{ old('received_date') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="received_by" class="form-label">Received By<span
                                    class="text-danger">*</span></label>
                            <input type="text" name="received_by" id="edit_received_by" class="form-control"
                                value="{{ old('received_by') }}">
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script>
        function getreceivedData(id) {

            var url = "{{ route('Otherlabworks.edit', ':id') }}";
            url = url.replace(":id", id);

            $.ajax({
                url: url,
                type: 'GET',

                success: function(obj) {
                    console.log(obj);
                    $('#received_labwork_id').val(obj.id);

                    $('#job_work_code').val(obj.job_work_no);

                    $('#edit_received_date').val(obj.received_date.split(' ')[0]);
                    $('#edit_received_by').val(obj.received_by);
                },

                error: function(xhr) {
                    alert('Failed to load data');
                }
            });
        }
    </script>
<script>
    $(document).ready(function () {
        $(".collected-btn").on("click", function (e) {
    e.preventDefault();
    let form = $(this).closest("form");
    let collectedBtn = $(this);
    let receivedBtn = form.closest("td").find(".received-btn");

    collectedBtn.prop("disabled", true).text("Collected...");

    $.post(form.attr("action"), form.serialize(), function () {
        collectedBtn.text("Collected");
        receivedBtn.removeClass("d-none"); // Show "Mark as Received" button
    });
});


    // $(".received-btn").on("click", function (e) {
    //     e.preventDefault();
    //     let form = $(this).closest("form");
    //     let receivedBtn = $(this);

    //         receivedBtn.prop("disabled", true).text("Received...");
    //         form.submit();
    // });

});

</script>
@endsection
