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
                                            <th>Patient Name</th>
                                            <th>Lab</th>
                                            <th>Treatment</th>
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
                                                <td>{{ $labwork->patient->name ?? 'N/A' }}</td>
                                                <td>{{ $labwork->lab->lab_name }}</td>
                                                <td>{{ $labwork->treatment->treatment_name ?? 'N/A' }}</td>
                                                <td>{{ date('d-m-Y', strtotime($labwork->entry_date)) }}</td>
                                                <td>{{ $labwork->collection_date ? \Carbon\Carbon::parse($labwork->collection_date)->format('d-m-Y') : 'Pending' }}</td>
                                                <td>{{ $labwork->received_date ? \Carbon\Carbon::parse($labwork->received_date)->format('d-m-Y') : 'Pending' }}</td>
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
                                                    <form action="{{ route('labworks.received', $labwork->id) }}" method="POST" class="d-inline received-form">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary received-btn {{ $labwork->collection_date ? '' : 'd-none' }}"
                                                            {{ $labwork->received_date ? 'disabled' : '' }}>
                                                            {{ $labwork->received_date ? 'Received' : 'Mark as Received' }}
                                                        </button>
                                                    </form>
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

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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


    $(".received-btn").on("click", function (e) {
        e.preventDefault();
        let form = $(this).closest("form");
        let receivedBtn = $(this);

            receivedBtn.prop("disabled", true).text("Received...");
            form.submit();
    });

});

</script>
@endsection
