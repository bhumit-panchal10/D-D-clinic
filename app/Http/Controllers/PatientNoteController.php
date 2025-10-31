<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientNote;
use App\Models\PatientTreatment;
use App\Models\PatientTreatmentItem;
use App\Models\PatientNoteDocument;
use Illuminate\Http\Request;

class PatientNoteController extends Controller
{
    public function index(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        // ✅ Fetch all available treatments for this patient
        $treatments = \App\Models\PatientTreatmentItem::query()
            ->select('treatments.id', 'treatments.treatment_name', 'patient_treatments.id as patient_treatment_id')
            ->join('patient_treatments', 'PatientTreatmentItem.patient_treatment_id', '=', 'patient_treatments.id')
            ->join('treatments', 'PatientTreatmentItem.treatment_id', '=', 'treatments.id')
            ->where('PatientTreatmentItem.treatment_start', 1)
            ->where('PatientTreatmentItem.patient_id', $id)
            ->distinct()
            ->get();

        // ✅ For edit mode (optional)
        $editNote = $request->has('edit') ? PatientNote::find($request->edit) : null;

        // ✅ Yellow teeth → Diagnosis (flag = 0)
        $yellowTeeth = \App\Models\PatientTreatment::where('patient_id', $id)
            ->where('treatment_flag', 0)
            ->pluck('tooth_selection')
            ->filter()
            ->map(fn($t) => array_map('strval', explode(',', $t)))
            ->collapse()
            ->unique()
            ->values()
            ->toArray();

        // ✅ Green teeth → Done (flag = 1)
        $greenTeeth = \App\Models\PatientTreatment::where('patient_id', $id)
            ->where('treatment_flag', 1)
            ->pluck('tooth_selection')
            ->filter()
            ->map(fn($t) => array_map('strval', explode(',', $t)))
            ->collapse()
            ->unique()
            ->values()
            ->toArray();

        // ✅ Read selected/search teeth (like “13,14,15”)
        $toothSelection = trim((string) $request->query('tooth_selection', ''));
        $teethFilter = collect(explode(',', $toothSelection))
            ->map(fn($t) => trim($t))
            ->filter()
            ->unique()
            ->values()
            ->all();

        // ✅ Filter patient notes by selected teeth (if any)
        $notesQuery = PatientNote::where('patient_id', $id)
            ->when(!empty($teethFilter), fn($q) => $q->whereIn('tooth_number', $teethFilter))
            ->orderByDesc('created_at');

        $notes = $notesQuery
            ->paginate(config('app.per_page', 10))
            ->appends(['tooth_selection' => $toothSelection]);

        return view('patient_notes.index', compact(
            'patient',
            'notes',
            'editNote',
            'treatments',
            'toothSelection',
            'greenTeeth',
            'yellowTeeth'
        ));
    }




    public function viewDocument($id, $patient_id)
    {

        $note = PatientNote::where('treatment_id', $id)->first();

        $note_id = $note->id;
        $documents = PatientNoteDocument::with('patientTreatment', 'patientNote')->where('patient_note_id', $note_id)->get();

        //$patienttreatmentdoc = PatientNoteDocument::with('patientTreatment')->where('patient_note_id', $note_id)->get();

        return view('patient_notes.document', compact('note', 'documents'));
    }

    public function getToothNumbers($treatmentId)
    {
        // All PTI rows for this treatment
        $ptiIds = PatientTreatmentItem::where('treatment_id', $treatmentId)
            ->pluck('patient_treatment_id')
            ->unique()
            ->values();

        if ($ptiIds->isEmpty()) {
            return response()->json(['tooth_numbers' => []]);
        }

        // Collect all tooth_selection strings, explode and de-duplicate
        $toothNumbers = PatientTreatment::whereIn('id', $ptiIds)
            ->pluck('tooth_selection')
            ->flatMap(function ($s) {
                return collect(explode(',', (string) $s))
                    ->map(fn($v) => trim($v))
                    ->filter();
            })
            ->unique()
            ->values()
            ->all();

        return response()->json(['tooth_numbers' => $toothNumbers]);
    }


    public function store(Request $request, $id)
    {


        $request->validate([
            'notes' => 'required|string|min:1',
            'treatment_id' => 'nullable|exists:treatments,id',
            'date' => 'required|date',
            'document.*' => 'nullable|file|mimes:jpeg,png,pdf,jpg' // each file up to 5MB
        ]);

        // 1️⃣ Create the Patient Note record
        $note = PatientNote::create([
            'patient_id' => $id,
            'notes' => $request->notes,
            'date' => $request->date,
            'treatment_id' => $request->treatment_id,
            'tooth_number' => $request->tooth_number,
        ]);
        $patientTreatmentitem = PatientTreatmentItem::where('treatment_id', $request->treatment_id)->first();
        $patientTreatment = PatientTreatment::where('id', $patientTreatmentitem->patient_treatment_id)->first();
        $date = $patientTreatment->created_at->format('Y/m/d'); // e.g. 2025/06/27


        // 2️⃣ Handle Multiple Document Uploads
        if ($request->hasFile('document')) {
            $root = $_SERVER['DOCUMENT_ROOT'];
            $destinationpath = $root . '/dental_clinic/patient_notes_documents/' . $date . '/' . $patientTreatment->id;
            if (!file_exists($destinationpath)) {
                mkdir($destinationpath, 0755, true);
            }

            foreach ($request->file('document') as $image) {
                $img = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($destinationpath, $img);

                PatientNoteDocument::create([
                    'treatment_id' => $request->filled('treatment_id') ? $request->treatment_id : null,
                    'document' => $img,
                    'patient_note_id' => $note->id,
                    'created_at' => now(),
                    'patient_treatment_id' => $request->patient_treatment_id,
                ]);
            }
        }

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



    // public function destroy($patient_id, $id)
    // {
    //     $note = PatientNote::where('patient_id', $patient_id)->findOrFail($id);
    //     $note->delete();

    //     return redirect()->route('patient_notes.index', $patient_id)->with('success', 'Note deleted successfully.');
    // }

    public function destroy($patient_id, $id)
    {
        $note = PatientNote::where('patient_id', $patient_id)->findOrFail($id);

        // Fetch related documents before deleting the note
        $documents = PatientNoteDocument::where('patient_note_id', $note->id)->get();

        foreach ($documents as $doc) {
            // Reconstruct file path — adapt based on your storage pattern
            $patientTreatmentItem = PatientTreatmentItem::where('treatment_id', $note->treatment_id)->first();
            $patientTreatment = null;

            if ($patientTreatmentItem) {
                $patientTreatment = PatientTreatment::find($patientTreatmentItem->patient_treatment_id);
            }

            // Determine date-based folder (fallback to note date if missing)
            $date = $patientTreatment && $patientTreatment->created_at
                ? $patientTreatment->created_at->format('Y/m/d')
                : $note->date->format('Y/m/d');

            // Full file path
            $root = $_SERVER['DOCUMENT_ROOT'];
            $filePath = $root . '/dental_clinic/patient_notes_documents/' . $date . '/' . ($patientTreatment->id ?? 'unknown') . '/' . $doc->document;

            // Delete file if it exists
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Delete document record from DB
            $doc->delete();
        }

        // Finally, delete the note
        $note->delete();

        return redirect()
            ->route('patient_notes.index', $patient_id)
            ->with('success', 'Note and related documents deleted successfully.');
    }
}
