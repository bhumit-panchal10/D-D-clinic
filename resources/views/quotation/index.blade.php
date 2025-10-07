@extends('layouts.app')
@section('title', 'Quotations')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="d-flex justify-content-between align-items-center m-3">
                    <h5 class="mb-0">
                        Name: {{ $patient->name }} | Mobile No 1: {{ $patient->mobile1 }}
                        @if ($patient->mobile2 != '')
                            | Mobile No 2: {{ $patient->mobile2 }}
                        @endif
                        | Case No: {{ $patient->case_no }}
                    </h5>
                    <a href="{{ route('patient.index') }}" class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                    </a>
                </div>

                @include('common.alert')
                @include('patient.show', ['id' => $patient->id])

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Quotations</h5>
                        <a href="{{ route('quotation.create', $patient->id) }}" class="btn btn-primary">+ Add Quotation</a>
                    </div>
                    @include('quotation.new', [
                        'patient' => $patient,
                        'quotation_no' => $quotation_no,
                        'patientTreatments' => $patientTreatments,
                    ])

                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr no.</th>
                                    <th>Quotation No</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    {{-- <th>Discount</th> --}}
                                    {{-- <th>Net Amount</th> --}}
                                    {{-- <th>Paid Payment</th> --}}
                                    {{-- <th>Due Payment</th> --}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($qutations as $key => $qutation)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $qutation->quotation_no }}</td>
                                        <td>{{ $qutation->created_at->format('d-m-Y') }}</td>
                                        <td>{{ $qutation->amount }}</td>
                                        {{-- <td>{{ $qutation->discount }}</td> --}}
                                        {{-- <td>{{ $qutation->net_amount }}</td> --}}
                                        {{-- <td>{{ number_format($qutation->paid_amount, 2) }}</td> --}}
                                        {{-- <td>{{ number_format($qutation->due_amount, 2) }}</td> --}}
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-primary delete-order"
                                                    data-id="{{ $qutation->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#deleteRecordModal">
                                                    Delete
                                                </button>

                                                <a href="{{ route('quotation.invoice', $qutation->id) }}" target="_blank"
                                                    class="btn btn-primary btn-sm">
                                                    Download Quotation
                                                </a>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No invoices found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $qutations->links('pagination::bootstrap-4') }}
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
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this invoice?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn btn-primary" id="confirmDelete">Yes, Delete It!</button>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="quotation_id" id="deleteid" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Modal End -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".delete-order").on("click", function() {
                let id = $(this).data("id");
                $("#deleteid").val(id);
            });

            // Confirm Delete Button Click
            $("#confirmDelete").on("click", function() {
                let id = $("#deleteid").val();
                let actionUrl = "{{ route('quotation.destroy', ':id') }}".replace(':id', id);

                $("#deleteForm").attr("action", actionUrl);

                // Explicitly submit the form
                $("#deleteForm").submit();
            });
        });
    </script>
@endsection
