@extends('layouts.app')
@section('title', 'Patients List')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <!-- Notes List Section -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Reason For Visit Today List</h5>
                            <div class="d-flex justify-content-between align-items-center m-3">
                                <h5 class="mb-0">


                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Sr. No</th>
                                        {{-- <th>Facial Asymmetry</th>
                                            <th>TMJ</th>
                                            <th>Lymphadenopathy</th> --}}
                                        <th>Date</th>
                                        <th>Reason For Visit Today</th>
                                        {{-- <th>Actions</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ReasonForVisitToday as $key => $ReasonForVisit)
                                        <tr>
                                            <td class="text-center">{{ $ReasonForVisitToday->firstItem() + $key }}</td>
                                            {{-- <td>{{ $ReasonForVisit->facial_asymmetry ?? '-' }}</td>
                                                <td>{{ $ReasonForVisit->TMJ ?? '-' }}</td>
                                                <td>{{ $ReasonForVisit->Lymphadenopathy ?? '-' }}</td> --}}
                                            <td>{{ date('d-m-Y', strtotime($ReasonForVisit->date)) }}</td>
                                            <td>{{ Str::limit($ReasonForVisit->comment ?? '-', 50) }}</td>
                                            {{-- <td>
                                                <button type="button" class="btn btn-sm btn-primary edit-btn"
                                                    onclick="getEditData(<?= $ReasonForVisit->id ?>)" data-bs-toggle="modal"
                                                    data-bs-target="#editNoteModal">
                                                    View
                                                </button>
                                                <button type="button" class="btn btn-sm btn-primary delete-btn"
                                                    data-id="{{ $ReasonForVisit->id }}"
                                                    data-patient-id="{{ $ReasonForVisit->patient_id }}" data-toggle="modal"
                                                    data-target="#deleteRecordModal">
                                                    Delete
                                                </button>

                                            </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $ReasonForVisitToday->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Intraoral Examination Records</h5>
                            <div class="d-flex justify-content-between align-items-center m-3">
                                <h5 class="mb-0">


                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>Date</th>
                                        <th>Caries</th>
                                        <th>Pain OP</th>
                                        <th>Missing</th>
                                        <th>Mobility</th>
                                        <th>Prosthesis</th>
                                        <th>BOP</th>
                                        <th>Notes</th>
                                        {{-- <th width="120">Action</th> --}}
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($examinations as $exam)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($exam->exam_date)->format('d M Y') }}</td>

                                            <td>
                                                @foreach (explode(',', $exam->caries ?? '') as $t)
                                                    @if ($t != '')
                                                        <span
                                                            class="badge badge-success text-black fs-6">{{ $t }}</span>
                                                    @endif
                                                @endforeach
                                            </td>

                                            <td>
                                                @foreach (explode(',', $exam->pain_op ?? '') as $t)
                                                    @if ($t != '')
                                                        <span
                                                            class="badge badge-warning text-black fs-6">{{ $t }}</span>
                                                    @endif
                                                @endforeach
                                            </td>

                                            <td>
                                                @foreach (explode(',', $exam->missing ?? '') as $t)
                                                    @if ($t != '')
                                                        <span
                                                            class="badge badge-dark text-black fs-6">{{ $t }}</span>
                                                    @endif
                                                @endforeach
                                            </td>

                                            <td>
                                                @foreach (explode(',', $exam->mobility ?? '') as $t)
                                                    @if ($t != '')
                                                        <span
                                                            class="badge badge-info text-black fs-6">{{ $t }}</span>
                                                    @endif
                                                @endforeach
                                            </td>

                                            <td>
                                                @foreach (explode(',', $exam->prosthesis ?? '') as $t)
                                                    @if ($t != '')
                                                        <span
                                                            class="badge badge-primary text-black fs-6">{{ $t }}</span>
                                                    @endif
                                                @endforeach
                                            </td>

                                            <td>{{ $exam->BOP ?? '-' }}</td>
                                            <td>{{ $exam->notes ?? '-' }}</td>

                                            {{-- <td>
                                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                    data-id="{{ $exam->id }}">
                                                    Delete
                                                </button>
                                            </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $examinations->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Treatment Plan Records</h5>
                            <div class="d-flex justify-content-between align-items-center m-3">
                                <h5 class="mb-0">


                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>Date</th>
                                        <th>RCT</th>
                                        <th>Extraction</th>
                                        <th>Restoration</th>
                                        <th>Prosthesis</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($examinationsplan as $exam)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($exam->date)->format('d M Y') }}</td>

                                            <td>{{ $exam->RCT_IPC }}</td>
                                            <td>{{ $exam->Extraction }}</td>
                                            <td>{{ $exam->Restoration }}</td>
                                            <td>{{ $exam->Prosthesis }}</td>

                                            {{-- <td>
                                                <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                    data-id="{{ $exam->id }}">
                                                    Delete
                                                </button>
                                            </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $examinationsplan->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Treatment List</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <!--<th>Sr. No</th>-->
                                        <th>Payment Date</th>
                                        {{-- <th>Time</th> --}}
                                        <th>Treatment</th>
                                        <th>Sub Treatment</th>
                                        <th>Tooth No</th>
                                        <th>Next Appt.</th>
                                        <th>Amount</th>
                                        <th>Discount</th>
                                        <th>Comment</th>
                                        {{-- <th>Images</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notes as $key => $note)
                                        <tr>
                                            <!--<td class="text-center">{{ $notes->firstItem() + $key }}</td>-->
                                            <td>{{ date('d-m-Y', strtotime($note->date)) }}</td>
                                            {{-- <td>{{ $note->time ?? '' }}</td> --}}
                                            <td>{{ $note->treatment->treatment_name ?? '' }}</td>
                                            <td>
                                                @if (!empty($note->sub_treatment_id))
                                                    {{ \App\Models\SubTreatment::whereIn('sub_treatment_id', array_filter(explode(',', $note->sub_treatment_id)))->pluck('name')->implode(', ') }}
                                                @endif
                                            </td>
                                            <td>{{ $note->tooth_no }}</td>
                                            <td>{{ $note->next_appointment_date ?? '' }}
                                            </td>
                                            <td>{{ $note->amount }}</td>
                                            <td>{{ $note->discount ?? '' }}</td>
                                            <td>
                                                @php
                                                    $comment = $note->comments ?? '';
                                                @endphp
                                                @if (strlen($comment) > 80)
                                                    <span
                                                        class="comment-preview">{{ Illuminate\Support\Str::limit($comment, 80) }}</span>
                                                    <span class="comment-full d-none">{{ $comment }}</span>
                                                    <button type="button"
                                                        class="btn btn-link p-0 small toggle-comment">Show
                                                        more</button>
                                                @else
                                                    {{ $comment }}
                                                @endif
                                            </td>
                                            {{-- <td>{{ $note->images->count() }}</td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $notes->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- container-fluid -->
        </div> <!-- page-content -->
    </div> <!-- main-content -->




@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
