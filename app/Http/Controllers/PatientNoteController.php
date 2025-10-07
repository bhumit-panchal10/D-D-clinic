<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientNote;
use App\Models\PatientTreatmentItem;
use Illuminate\Http\Request;

class PatientNoteController extends Controller
{
    public function index(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        $treatments = \App\Models\PatientTreatmentItem::query()
            ->select('treatments.id', 'treatments.treatment_name')
            ->join('patient_treatments', 'PatientTreatmentItem.patient_treatment_id', '=', 'patient_treatments.id')
            ->join('treatments', 'PatientTreatmentItem.treatment_id', '=', 'treatments.id')
            ->where('patient_treatments.treatment_flag', 1)
            ->distinct()
            ->get();

        $notes = PatientNote::where('patient_id', $id)->orderBy('created_at', 'desc')->paginate(config('app.per_page'));

        // Edit Mode - Fetch the note if 'edit' parameter is present
        $editNote = null;
        if ($request->has('edit')) {
            $editNote = PatientNote::find($request->edit);
        }

        return view('patient_notes.index', compact('patient', 'notes', 'editNote', 'treatments'));
    }

    public function store(Request $request, $id)
    {

        $request->validate([
            'notes' => 'required|string|min:1',
            'treatment_id' => 'nullable',
            'date' => 'required',

        ]);

        PatientNote::create([
            'patient_id' => $id,
            'notes' => $request->notes,
            'date' => $request->date,
            'treatment_id' => $request->treatment_id,
        ]);

        return redirect()->route('patient_notes.index', $id)->with('success', 'Note added successfully.');
    }

    public function update(Request $request, $patient_id, $id)
    {
        $request->validate([
            'notes' => 'required|string|min:1',
            'treatment_id' => 'nullable',
            'date' => 'required',
        ]);

        $note = PatientNote::where('patient_id', $patient_id)->findOrFail($id);
        $note->update(['notes' => $request->notes, 'date' => $request->date, 'treatment_id' => $request->treatment_id]);

        return redirect()->route('patient_notes.index', $patient_id)->with('success', 'Note updated successfully.');
    }

    public function destroy($patient_id, $id)
    {
        $note = PatientNote::where('patient_id', $patient_id)->findOrFail($id);
        $note->delete();

        return redirect()->route('patient_notes.index', $patient_id)->with('success', 'Note deleted successfully.');
    }
}
