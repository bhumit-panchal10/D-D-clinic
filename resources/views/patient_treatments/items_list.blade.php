{{-- resources/views/patient_treatments/partials/items_list.blade.php --}}
@if ($items->isEmpty())
    <div class="text-muted small">No treatments added yet.</div>
@else
    <div class="table-responsive">
        <table class="table table-sm table-bordered align-middle mb-0">
            <thead>
                <tr>
                    <th style="width:60px">#</th>
                    <th>Treatment</th>
                    <th>SubTreatment</th>
                    <th>Rate</th>
                    <th>Qty</th>
                    <th>Amount</th>
                    <th>Notes</th>
                    <th style="width:100px">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row->treatment?->treatment_name }}</td>
                        <td>{{ $row->Subtreatment?->name }}</td>
                        <td>{{ $row->treatment_rate }}</td>
                        <td>{{ $row->treatment_qty }}</td>
                        <td>{{ $row->treatment_amount }}</td>
                        <td>{{ $row->notes }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger"
                                onclick="deleteTreatmentItem({{ $row->id }})">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
