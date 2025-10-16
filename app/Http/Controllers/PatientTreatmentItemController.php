<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PatientTreatmentItem;
use App\Models\PatientTreatment;


class PatientTreatmentItemController extends Controller
{

    public function markDone($id)
    {
        $item = PatientTreatmentItem::findOrFail($id);
        $item->update(['treatment_done' => 1]);

        return response()->json([
            'status' => 'success',
            'message' => 'Treatment marked as done',
        ]);
    }

    public function markStart($id)
    {

        $item = PatientTreatmentItem::findOrFail($id);
        $item->update(['treatment_start' => 1]);
        return response()->json([
            'status' => 'success',
            'message' => 'Treatment marked as start',
        ]);
    }


    public function store(Request $request)
    {

        $data = $request->validate([
            'patient_id'           => 'required|integer',
            'diagnosis_id' => 'required|integer',
            'treatment_id'         => 'required|integer',
            'sub_treatment_id'         => 'nullable|integer',
            'notes'        => 'nullable|string',
            'treatment_rate'        => 'nullable',
            'treatment_qty'        => 'nullable',
            'treatment_date'        => 'nullable',
            'treatment_amount'        => 'nullable',
            'patient_treatment_id' => 'nullable',
        ]);
        // normalize values
        $data['treatment_rate']   = (int) ($data['treatment_rate'] ?? 0);
        $data['treatment_qty']    = (int) ($data['treatment_qty'] ?? 0);
        $data['treatment_amount'] = (float) ($data['treatment_amount'] ?? 0);
        $data['treatment_date'] = ($data['treatment_date'] ?? 0);

        // prevent duplicates for the same patient + diagnosis row (optional)
        $exists = PatientTreatmentItem::where($data)->exists();

        if (!$exists) {
            PatientTreatmentItem::create($data);

            PatientTreatment::where('id', $data['patient_treatment_id'])
                ->update(['treatment_flag' => 1]);
        }

        return response()->json(['status' => 'success']);
    }

    public function list(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|integer',
            'diagnosis_id' => 'required',
        ]);
        $items = PatientTreatmentItem::with('treatment', 'Subtreatment')
            ->where('patient_id', $request->patient_id)
            ->where('diagnosis_id', $request->diagnosis_id)
            ->latest('id')
            ->get();

        return view('patient_treatments.items_list', compact('items'))->render();
    }

    public function destroy($id)
    {

        PatientTreatmentItem::where('id', $id)->delete();
        return response()->json(['status' => 'success']);
    }
}
