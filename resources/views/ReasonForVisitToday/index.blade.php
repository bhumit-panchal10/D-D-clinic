@extends('layouts.app')

@section('title', 'Reason For Visit Today')

@section('content')
    <style>
        /* .tooth-search-highlight {
                                    box-shadow: 0 0 10px 4px rgba(34, 197, 94, 0.7);
                                    transform: scale(1.12);
                                    transition: all 0.2s ease;
                                } */

        .teeth_wrapper {
            width: 55px;
        }

        .teeth_wrapper img {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: optimizeQuality;
            transition: filter 0.2s ease;
        }

        /* Treatment done (green image) */
        .tooth-green {
            filter: drop-shadow(0 0 1px #15803d);
            /* green outline */
        }

        /* Diagnosis (yellow image) */
        .tooth-yellow {
            filter: drop-shadow(0 0 1px #ca8a04);
            /* yellow outline */
        }

        /* Neutral/other teeth – turn PNG into light gray */
        .tooth-neutral {
            filter: grayscale(100%) brightness(1.5) contrast(1.2) drop-shadow(0 0 1px #6b7280);
            opacity: 0.9;
        }

        .dx-card {
            border-radius: 14px;
            overflow: hidden
        }

        .dx-head .dx-title {
            font-weight: 700;
            letter-spacing: .2px
        }

        .dx-meta {
            color: #64748b;
            font-size: .9rem
        }

        .dx-list .list-group-item {
            border: 0;
            border-bottom: 1px solid #eef0f3;
            padding: .8rem 1rem
        }

        .dx-list .list-group-item:last-child {
            border-bottom: 0
        }

        .dx-pill {
            font-size: .85rem;
            padding: .35rem .6rem;
            border-radius: 20px;
            background: #f1f5ff;
            color: #1f6bff
        }

        .dx-chip {
            background: #f8fafc;
            border: 1px solid #eef0f3;
            border-radius: 12px;
            padding: .35rem .6rem;
            font-size: .9rem
        }
    </style>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">


                {{-- Alert Messages --}}
                @include('common.alert')

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li class="text-danger">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">


                    <!-- Notes List Section -->
                    {{-- @if ($notes->count() > 0) --}}
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Notes List</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr. No</th>
                                            <th>Treatment</th>
                                            <th>Tooth</th>
                                            <th>Note</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($notes as $key => $note)
                                            <tr>
                                                <td>{{ $notes->firstItem() + $key }}</td>
                                                <td>{{ $note->treatment->treatment_name ?? '' }}</td>
                                                <td>{{ $note->tooth_number }}</td>
                                                <td>{{ $note->notes }}</td>
                                                <td>{{ $note->date ? date('d-m-Y', strtotime($note->date)) : '-' }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('patient_notes.viewdocument', [$note->treatment_id, $patient->id]) }}"
                                                        class="btn btn-sm btn-primary" title="View Document">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-primary edit-btn"
                                                        data-id="{{ $note->id }}" data-notes="{{ $note->notes }}"
                                                        data-patient-id="{{ $patient->id }}"
                                                        data-date="{{ $note->date }}"
                                                        data-treatment-id="{{ $note->treatment_id }}"
                                                        data-bs-toggle="modal" data-bs-target="#editNoteModal">
                                                        Edit
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-primary delete-btn"
                                                        data-id="{{ $note->id }}"
                                                        data-patient-id="{{ $patient->id }}" data-toggle="modal"
                                                        data-target="#deleteRecordModal">
                                                        Delete
                                                    </button>
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
                    {{-- @endif --}}

                </div>

            </div>
        </div>
    </div>

    <!-- Edit Note Modal -->
    <div class="modal fade" id="editNoteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label">Treatment <span class="text-danger">*</span></label>
                            <select name="treatment_id" class="form-control" required>
                                <option value="">Select Treatment</option>
                                @foreach ($treatments as $t)
                                    <option value="{{ $t->id }}">{{ $t->treatment_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="date" id="editdate" required>
                        </div>
                        <div class="mb-3">
                            <label>Note <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="notes" id="editNotes" rows="3" required></textarea>
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
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this note?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="document_id" id="deleteid" value="">
                            <button type="submit" class="btn btn-primary">Yes, Delete It!</button>
                        </form>
                        <button type="button" class="btn w-sm btn-primary" data-bs-dismiss="modal">Close</button>
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
        $(function() {
            // ✅ Handle Treatment Dropdown → Load Teeth via AJAX
            $('#treatment_id').change(function() {
                var treatmentId = $(this).val();
                var patientTreatmentId = $('#treatment_id option:selected').data('patient_treatment_id');
                $('#patient_treatment_id').val(patientTreatmentId);

                if (treatmentId) {
                    $.ajax({
                        url: '/get-tooth-numbers/' + treatmentId,
                        type: 'GET',
                        success: function(data) {
                            const $tooth = $('#tooth_number');
                            $tooth.empty().append('<option value="">Select Tooth</option>');
                            if (data.tooth_numbers.length > 0) {
                                data.tooth_numbers.forEach(tooth =>
                                    $tooth.append(
                                        `<option value="${tooth}">Tooth ${tooth}</option>`)
                                );
                            } else {
                                $tooth.append('<option value="">No Tooth Available</option>');
                            }
                        },
                        error: function() {
                            alert('An error occurred while fetching tooth numbers.');
                        }
                    });
                } else {
                    $('#tooth_number').empty().append('<option value="">Select Tooth</option>');
                }
            });

            // ✅ Edit Modal setup
            $(".edit-btn").on("click", function() {
                let id = $(this).data("id"),
                    notes = $(this).data("notes"),
                    date = $(this).data("date"),
                    treatmentId = $(this).data("treatment-id"),
                    patientId = $(this).data("patient-id");

                $("#editNotes").val(notes);

                // normalize date to YYYY-MM-DD
                let iso = "";
                if (date) {
                    const dmy = /^(\d{2})[-\/](\d{2})[-\/](\d{4})$/.exec(date);
                    const ymd = /^(\d{4})[-\/](\d{2})[-\/](\d{2})$/.exec(date);
                    if (dmy) iso = `${dmy[3]}-${dmy[2]}-${dmy[1]}`;
                    else if (ymd) iso = `${ymd[1]}-${ymd[2]}-${ymd[3]}`;
                }
                $("#editdate").val(iso);

                $('#editNoteModal select[name="treatment_id"]').val(String(treatmentId)).trigger('change');

                let actionUrl = "{{ route('patient_notes.update', [':patient_id', ':id']) }}"
                    .replace(':patient_id', patientId)
                    .replace(':id', id);
                $("#editForm").attr("action", actionUrl);
            });

            // ✅ Delete Modal setup
            $(".delete-btn").on("click", function() {
                let id = $(this).data("id"),
                    patientId = $(this).data("patient-id");

                let actionUrl = "{{ route('patient_notes.destroy', [':patient_id', ':id']) }}"
                    .replace(':patient_id', patientId)
                    .replace(':id', id);

                $("#deleteForm").attr("action", actionUrl);
                $("#deleteRecordModal").modal("show");
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const adultBtn = document.getElementById("icon-adult");
            const childBtn = document.getElementById("icon-children");
            const toothSelectionInput = document.getElementById("tooth_selection");
            const toothSearchInput = document.getElementById("tooth_selection_search");

            const YELLOW_TEETH = @json($yellowTeeth ?? []);
            const GREEN_TEETH = @json($greenTeeth ?? []);

            // === Toggle Adult / Child ===
            adultBtn?.addEventListener("click", () => {
                adultBtn.classList.add("active");
                childBtn.classList.remove("active");
                document.querySelectorAll(".adult-teeth-group > .row").forEach(r => r.style.display =
                    "flex");
                document.querySelectorAll(".children-teeth-group").forEach(g => g.style.display = "none");
            });
            childBtn?.addEventListener("click", () => {
                childBtn.classList.add("active");
                adultBtn.classList.remove("active");
                document.querySelectorAll(".adult-teeth-group > .row").forEach(r => r.style.display =
                    "none");
                document.querySelectorAll(".children-teeth-group").forEach(g => g.style.display = "flex");
            });

            // === Helpers ===
            function baselineState(tooth) {
                if (GREEN_TEETH.includes(tooth)) return 'green';
                if (YELLOW_TEETH.includes(tooth)) return 'yellow';
                return 'white';
            }

            function setToothState(img, state, lock = false) {
                img.dataset.state = state;
                img.classList.remove('tooth-green', 'tooth-yellow', 'tooth-neutral');

                if (state === 'green') img.src = img.dataset.color, img.classList.add('tooth-green');
                else if (state === 'yellow') img.src = img.dataset.bw, img.classList.add('tooth-yellow');
                else img.src = img.dataset.bw, img.classList.add('tooth-neutral');

                img.dataset.lock = lock ? '1' : '';
                //img.style.pointerEvents = lock ? 'none' : '';
            }

            function paintAllFromDB() {
                document.querySelectorAll(".teeth_wrapper img").forEach(img => {
                    const tooth = img.alt;
                    if (GREEN_TEETH.includes(tooth)) setToothState(img, 'green', true);
                    else if (YELLOW_TEETH.includes(tooth)) setToothState(img, 'yellow', false);
                    else setToothState(img, 'white', false);
                });
            }

            function applySelectionFromString(str) {
                const teeth = String(str || "")
                    .split(",")
                    .map(t => t.trim())
                    .filter(Boolean);

                // Repaint baseline first (yellow/white/locked green)
                paintAllFromDB();

                // Then visually mark searched teeth
                teeth.forEach(tooth => {
                    const img = document.querySelector('.teeth_wrapper img[alt="' + tooth + '"]');
                    if (img) {
                        // If it's a locked green (done), make it glow stronger
                        if (img.dataset.lock === '1') {
                            img.classList.add('tooth-search-highlight');
                        } else {
                            setToothState(img, 'green', false);
                        }
                    }
                });
            }


            // Initial paint
            paintAllFromDB();
            applySelectionFromString(toothSearchInput?.value);

            // === Handle clicks ===
            document.querySelectorAll(".teeth_wrapper img").forEach(img => {
                img.style.cursor = "pointer";
                img.addEventListener("click", function() {
                    const toothNumber = this.alt;
                    let currentTeeth = (toothSelectionInput?.value || '')
                        .split(",").map(t => t.trim()).filter(t => t !== "");

                    if (!currentTeeth.includes(toothNumber)) {
                        currentTeeth.push(toothNumber);
                        this.classList.add('tooth-search-highlight'); // highlight for search
                    } else {
                        currentTeeth = currentTeeth.filter(t => t !== toothNumber);
                        this.classList.remove('tooth-search-highlight');
                    }

                    // sync inputs
                    const joined = currentTeeth.join(", ");
                    if (toothSelectionInput) toothSelectionInput.value = joined;
                    if (toothSearchInput) toothSearchInput.value = joined;
                });
            });

            // === Manual Search Sync ===
            toothSearchInput?.addEventListener("input", function() {
                const teeth = this.value.split(",").map(t => t.trim()).filter(Boolean);
                paintAllFromDB();
                teeth.forEach(tooth => {
                    const img = document.querySelector('.teeth_wrapper img[alt="' + tooth + '"]');
                    if (img && img.dataset.lock !== '1') setToothState(img, 'green', false);
                });
                toothSelectionInput.value = teeth.join(", ");
            });
        });
    </script>


@endsection
