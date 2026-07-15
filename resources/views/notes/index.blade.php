@extends('layouts.app')

@section('title', 'Notes')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="d-flex justify-content-between align-items-center m-3">
                    <h5 class="mb-0">
                        Name: {{ $patient->name }} {{ $patient->middle_name }} {{ $patient->last_name }} | Mobile No 1:
                        {{ $patient->mobile1 }} |
                        Age: @php
                            $age = $patient->Age ?? null;
                            $dob = $patient->dob ?? null;

                            if (!$age && $dob && $dob !== '0000-00-00') {
                                $age = \Carbon\Carbon::parse($dob)->age;
                            }
                        @endphp
                        {{ $age ? $age : '-' }}
                        {{-- @if ($patient->mobile2 != '')
                            | Mobile No 2: {{ $patient->mobile2 }}
                        @endif --}}
                        | Case No: {{ $patient->case_no }}
                    </h5>
                    <a href="{{ route('patient.index') }}" class="btn btn-sm btn-primary shadow-sm">
                        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
                    </a>
                </div>

                @include('common.alert')
                @include('patient.show', ['id' => $patient->id])

                <div class="row">
                    <!-- Add Note Section -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Add Treatment</h5>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#treatmentPlanModal">
                                    Treatment Plan
                                </button>
                            </div>

                            <div class="card-body">

                                <form action="{{ route('notes.store', $patient->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                    <div class="row">
                                        <div class="mb-3 col-md-3">
                                            <label>Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="payment_date" name="date"
                                                rows="3" required>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label for="mode" class="form-label">Treatment<span
                                                    class="text-danger">*</span></label>
                                            <select name="treatment" id="treatment" class="form-select" required>
                                                <option value="">--Please Select--</option>
                                                @foreach ($Treatment as $treat)
                                                    <option value="{{ $treat->id }}">{{ $treat->treatment_name }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label for="mode" class="form-label">Sub Treatment</label>
                                            <select name="sub_treatment[]" id="sub_treatment" class="form-select" multiple>
                                                <option value="">--Please Select--</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label for="tooth_no" class="form-label">Tooth No</label>
                                            <input type="text" name="tooth_no" id="tooth_no" class="form-control"
                                                oninput="this.value = this.value.replace(/[^0-9,]/g, '')" maxlength="50"
                                                value="">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label for="next_appointment_date" class="form-label">Next Appointment</label>
                                            <input type="text" name="next_appointment_date" id="next_appointment_date"
                                                class="form-control" value="">
                                        </div>
                                        <div class="mb-3 col-md-3">
                                            <label for="images" class="form-label">Upload Images</label>
                                            <input type="file" name="images[]" id="images" class="form-control"
                                                multiple accept="image/jpeg,image/jpg,image/png">
                                            <small class="text-muted">You can select multiple JPG/PNG images.</small>
                                        </div>
                                        <div class="mb-3 col-md-5">
                                            <label for="comments" class="form-label">Comments</label>
                                            <textarea name="comments" id="comments" class="form-control"></textarea>
                                        </div>
                                        <div class="mb-3 col-md-2">
                                            <label for="amount" class="form-label">Amount</label>
                                            <input type="text" name="amount" id="amount" class="form-control"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '')" maxlength="10"
                                                value="0">
                                        </div>

                                        <div class="mb-3 col-md-2">
                                            <label for="discount" class="form-label">Discount</label>
                                            <input type="text" name="discount" id="discount" class="form-control"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '')" maxlength="10"
                                                value="0">
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                        <button type="reset" class="btn btn-primary">Clear</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Notes List Section -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <button type="button" class="btn btn-sm btn-info" id="toggleAmountBtn">
                                    Treatment List | Total Amount: <span id="amountDisplay"
                                        class="d-none">{{ $NetAmount }}</span>
                                </button>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('notes.index', $patient->id) }}" method="GET"
                                    class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-4">
                                        <label for="search_tooth" class="form-label">Search Tooth No</label>
                                        <input type="text" name="tooth_no" id="search_tooth" class="form-control"
                                            placeholder="Search by tooth number or comma-separated list"
                                            value="{{ request('tooth_no') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="{{ route('notes.index', $patient->id) }}"
                                            class="btn btn-secondary">Reset</a>
                                    </div>
                                </form>
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
                                            <th>Actions</th>
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
                                                <td>
                                                    @if ($note->images->count() > 0)
                                                        <a href="{{ route('notes.images', $note->id) }}"
                                                            class="btn btn-sm btn-secondary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif

                                                    <button type="button" class="btn btn-sm btn-primary edit-btn"
                                                        onclick="getEditData(<?= $note->id ?>)" data-bs-toggle="modal"
                                                        data-bs-target="#editNoteModal">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                        data-id="{{ $note->id }}"
                                                        data-patient-id="{{ $patient->id }}" data-toggle="modal"
                                                        data-target="#deleteRecordModal">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    {{-- <a href="{{ route('payments.invoice', $note->id) }}" target="_blank"
                                                        class="btn btn-primary btn-sm">
                                                        Download Payment
                                                    </a> --}}
                                                </td>
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

                </div>

            </div>
        </div>
    </div>

    <!-- Edit Note Modal -->
    <div class="modal fade" id="editNoteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Treatment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" action="{{ route('notes.update') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                        <input type="hidden" name="id" id="edit_note_id" value="">

                        <div class="mb-3">
                            <label for="payment_date" class="form-label">Date<span class="text-danger">*</span></label>
                            <input type="date" name="date" id="edit_date" class="form-control"
                                value="{{ old('date') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="mode" class="form-label">Treatment<span class="text-danger">*</span></label>
                            <select name="treatment" id="edit_treatment" class="form-select" required>
                                <option value="">--Please Select--</option>
                                @foreach ($Treatment as $treat)
                                    <option value="{{ $treat->id }}">{{ $treat->treatment_name }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_sub_treatment" class="form-label">Sub Treatment</label>
                            <select name="sub_treatment[]" id="edit_sub_treatment" class="form-select" multiple>
                                <option value="">--Please Select--</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tooth_no" class="form-label">Tooth No</label>
                            <input type="text" name="tooth_no" id="edit_tooth_no" class="form-control"
                                oninput="this.value = this.value.replace(/[^0-9,]/g, '')" maxlength="50" value="">
                        </div>

                        <div class="mb-3">
                            <label for="edit_next_appointment_date" class="form-label">Next Appointment</label>
                            <input type="text" name="next_appointment_date" id="edit_next_appointment_date"
                                class="form-control" value="">
                        </div>

                        <div class="mb-3">
                            <label for="edit_comments" class="form-label">Comments</label>
                            <textarea name="comments" id="edit_comments" class="form-control"></textarea>
                        </div>

                        <div class="mb-3" id="edit_images_preview_container">
                            <label class="form-label">Existing Images</label>
                            <div id="edit_images_preview" class="row gy-2"></div>
                            <small class="text-muted">You can delete existing images or upload new ones below.</small>
                        </div>

                        <div class="mb-3">
                            <label for="edit_images" class="form-label">Upload Images</label>
                            <input type="file" name="images[]" id="edit_images" class="form-control" multiple
                                accept="image/jpeg,image/jpg,image/png">
                            <small class="text-muted">You can select multiple JPG/PNG images to add to this note.</small>
                        </div>

                        <div class="mb-3">
                            <label for="edit_amount" class="form-label">Amount<span class="text-danger">*</span></label>
                            <input type="text" name="amount" id="edit_amount" class="form-control"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '')" maxlength="10" value=""
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_discount" class="form-label">Discount</label>
                            <input type="text" name="discount" id="edit_discount" class="form-control"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '')" maxlength="10" value="">
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
                            colors="primary:#f7b84b,secondary:#f06548" style="width: 100px; height: 100px">
                        </lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you Sure?</h4>
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this payment?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <!-- Hidden input for the payment ID -->
                            <input type="hidden" name="id" id="deleteid" value="">
                            <button type="submit" class="btn btn-primary">Yes, Delete It!</button>
                        </form>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal End -->

    <!-- Treatment Plan Modal -->
    <div class="modal fade" id="treatmentPlanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Treatment Plan List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($treatmentPlans->isNotEmpty())
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>RCT</th>
                                    <th>Extraction</th>
                                    <th>Restoration</th>
                                    <th>Prosthesis</th>
                                    <th>Other Treatments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($treatmentPlans as $plan)
                                    @php
                                        // Get tooth-wise comments for each treatment type
                                        $rctDetails = $plan->details
                                            ->where('type_id', 1)
                                            ->filter(fn($d) => !empty($d->comment));
                                        $extractionDetails = $plan->details
                                            ->where('type_id', 2)
                                            ->filter(fn($d) => !empty($d->comment));
                                        $restorationDetails = $plan->details
                                            ->where('type_id', 3)
                                            ->filter(fn($d) => !empty($d->comment));
                                        $prosthesisDetails = $plan->details
                                            ->where('type_id', 4)
                                            ->filter(fn($d) => !empty($d->comment));

                                        // Format tooth-wise comments
                                        $formatToothComments = fn($details) => $details
                                            ->map(fn($d) => 'Tooth ' . $d->tooth_no . ': ' . $d->comment)
                                            ->implode('<br>');
                                    @endphp

                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($plan->date)->format('d M Y') }}</td>

                                        <td>
                                            {{ $plan->RCT_IPC ?? '-' }}
                                            @if ($rctDetails->isNotEmpty())
                                                <div style="font-size: 0.85rem; margin-top: 4px; color: #555;">
                                                    {!! $formatToothComments($rctDetails) !!}
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            {{ $plan->Extraction ?? '-' }}
                                            @if ($extractionDetails->isNotEmpty())
                                                <div style="font-size: 0.85rem; margin-top: 4px; color: #555;">
                                                    {!! $formatToothComments($extractionDetails) !!}
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            {{ $plan->Restoration ?? '-' }}
                                            @if ($restorationDetails->isNotEmpty())
                                                <div style="font-size: 0.85rem; margin-top: 4px; color: #555;">
                                                    {!! $formatToothComments($restorationDetails) !!}
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            {{ $plan->Prosthesis ?? '-' }}
                                            @if ($prosthesisDetails->isNotEmpty())
                                                <div style="font-size: 0.85rem; margin-top: 4px; color: #555;">
                                                    {!! $formatToothComments($prosthesisDetails) !!}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($plan->Scaling)
                                                <div><strong>Scaling:</strong> {{ $plan->Scaling_desc }}</div>
                                            @endif

                                            @if ($plan->polishing)
                                                <div><strong>Polishing:</strong> {{ $plan->polishing_desc }}</div>
                                            @endif

                                            @if ($plan->Grinding)
                                                <div><strong>Grinding:</strong> {{ $plan->Grinding_desc }}</div>
                                            @endif

                                            @if ($plan->Bleaching)
                                                <div><strong>Bleaching:</strong> {{ $plan->Bleaching_desc }}</div>
                                            @endif

                                            @if ($plan->smile_design)
                                                <div><strong>Smile Design:</strong> {{ $plan->smile_design_desc }}</div>
                                            @endif

                                            @if ($plan->orthodontics)
                                                <div><strong>Orthodontics:</strong> {{ $plan->orthodontics_desc }}</div>
                                            @endif

                                            @if ($plan->surgery)
                                                <div><strong>Surgery:</strong> {{ $plan->surgery_desc }}</div>
                                            @endif

                                            @if ($plan->biopsy)
                                                <div><strong>Biopsy:</strong> {{ $plan->biopsy_desc }}</div>
                                            @endif

                                            @if (!empty($plan->Dentures))
                                                <div><strong>Dentures:</strong> {{ $plan->Dentures }}</div>
                                            @endif

                                            @if (!empty($plan->implants))
                                                <div><strong>Implants:</strong> {{ $plan->implants }}</div>
                                            @endif

                                            @if (!empty($plan->other_treatment))
                                                <div><strong>Other Treatment:</strong> {{ $plan->other_treatment }}</div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-muted">No treatment plan data found for this patient.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Toggle Total Amount Display
        $(document).ready(function() {
            $('#toggleAmountBtn').on('click', function() {
                $('#amountDisplay').toggleClass('d-none');
            });
        });
    </script>
    <script>
        function getEditData(id) {

            var url = "{{ route('notes.edit', ':id') }}";
            url = url.replace(":id", id);
            if (id) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        id
                    },
                    success: function(obj) {
                        $("#edit_date").val(obj.date);
                        $("#edit_time").val(obj.time ?? '');
                        $("#edit_amount").val(obj.amount);
                        $("#edit_discount").val(obj.discount);
                        $("#edit_treatment").val(obj.treatment_id);
                        $("#edit_next_appointment_date").val(obj.next_appointment_date);
                        $("#edit_comments").val(obj.comments);
                        $("#edit_tooth_no").val(obj.tooth_no); // missing field

                        loadSubTreatments(obj.treatment_id, '#edit_sub_treatment', obj.sub_treatment_id);
                        renderEditImages(obj.images || []);
                        $('#edit_note_id').val(id);
                    },
                    error: function(xhr) {
                        alert('Failed to load data');
                    }
                });
            }
        }

        function renderEditImages(images) {
            const container = $('#edit_images_preview');
            container.empty();

            if (!images || images.length === 0) {
                container.append(
                    '<div id="edit_images_preview_empty" class="col-12 text-muted">No images uploaded for this note.</div>'
                );
                return;
            }

            const baseUrl = "{{ asset('/') }}";

            images.forEach(function(image) {
                const imgSrc = image.file_path ?
                    (image.file_path.startsWith('http') ? image.file_path : baseUrl + image.file_path.replace(
                        /^\/+/, '')) :
                    '';

                const card = $(
                    '<div class="col-6 col-md-4">' +
                    '<div class="card">' +
                    '<img src="' + imgSrc +
                    '" class="card-img-top" alt="Note Image">' +
                    '<div class="card-body p-2 text-center">' +
                    '<button type="button" class="btn btn-sm btn-danger delete-edit-image" data-image-id="' +
                    image.id + '">Delete</button>' +
                    '</div>' +
                    '</div>' +
                    '</div>'
                );
                container.append(card);
            });
        }
    </script>
    <script>
        function loadSubTreatments(treatmentId, dropdownSelector, selectedValue = null) {
            const dropdown = $(dropdownSelector);
            dropdown.empty().append('<option value="">--Please Select--</option>');

            if (!treatmentId) {
                return;
            }

            const routeUrl = "{{ route('subtreatment.getByTreatment', ['treatment_id' => 'TREATMENT_ID']) }}";
            const url = routeUrl.replace('TREATMENT_ID', treatmentId);

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    if (response && response.length) {
                        // Normalize selectedValue into array of strings for reliable comparison
                        let selectedArr = [];
                        if (selectedValue) {
                            if (Array.isArray(selectedValue)) {
                                selectedArr = selectedValue.map(String);
                            } else {
                                selectedArr = [String(selectedValue)];
                            }
                        }

                        response.forEach(function(item) {
                            const valStr = String(item.sub_treatment_id);
                            const option = $('<option></option>')
                                .attr('value', item.sub_treatment_id)
                                .text(item.name);

                            if (selectedArr.length && selectedArr.indexOf(valStr) !== -1) {
                                option.prop('selected', true);
                            }

                            dropdown.append(option);
                        });

                        // If dropdown is a multi-select, ensure browser reflects selection
                        if (selectedArr.length) {
                            dropdown.val(selectedArr);
                        }
                    }
                },
                error: function() {
                    dropdown.empty().append('<option value="">Unable to load sub treatments</option>');
                }
            });
        }

        $(document).ready(function() {
            $("#treatment").on('change', function() {
                const treatmentId = $(this).val();
                loadSubTreatments(treatmentId, '#sub_treatment');
            });

            $("#edit_treatment").on('change', function() {
                const treatmentId = $(this).val();
                loadSubTreatments(treatmentId, '#edit_sub_treatment');
            });

            $(".delete-btn").on("click", function() {
                let id = $(this).data("id");
                let patientId = $(this).data("patient-id");

                // Set the delete form action to the notes.destroy route
                let actionUrl = "{{ route('notes.destroy', ':id') }}".replace(':id', id);

                // Set the form action dynamically
                $("#deleteForm").attr("action", actionUrl);

                // Open the modal
                $("#deleteRecordModal").modal("show");
            });

            $(document).on('click', '.toggle-comment', function() {
                let $btn = $(this);
                let $cell = $btn.closest('td');

                $cell.find('.comment-preview, .comment-full').toggleClass('d-none');

                if ($btn.hasClass('expanded')) {
                    $btn.removeClass('expanded').text('Show more');
                } else {
                    $btn.addClass('expanded').text('Show less');
                }
            });

            $(document).on('click', '.delete-edit-image', function() {
                const imageId = $(this).data('image-id');
                const noteId = $('#edit_note_id').val();
                if (!imageId || !noteId) {
                    return;
                }

                if (!confirm('Delete this image?')) {
                    return;
                }

                const deleteUrl = "{{ route('notes.images.delete', ':id') }}".replace(':id', imageId);

                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            getEditData(noteId);
                        } else {
                            alert('Unable to delete image.');
                        }
                    },
                    error: function() {
                        alert('Unable to delete image.');
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
            document.getElementById('payment_date').value = today; // Set today's date as the value
        });
    </script>
@endsection
