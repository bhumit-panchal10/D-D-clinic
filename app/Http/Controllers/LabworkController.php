<?php

namespace App\Http\Controllers;

use App\Models\Labwork;
use App\Models\Lab;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\PatientTreatment;
use Illuminate\Http\Request;

class LabworkController extends Controller
{
    public function index(Request $request)
    {
        $patient_id = $request->patient_id;
        $patient = $patient_id ? Patient::findOrFail($patient_id) : null;
        $labs = Lab::all();
        $treatments = Treatment::all();
        $patientTreatments = PatientTreatment::all();
        $labworks = Labwork::where('patient_id', $patient_id)->paginate(config('app.per_page'));

        return view('labworks.index', compact('patient', 'labs', 'treatments', 'patientTreatments', 'labworks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'lab_id' => 'required|exists:labs,id',
            'treatment_id' => 'nullable|exists:treatments,id',
            'patient_treatment_id' => 'nullable|exists:patient_treatments,id',
            'entry_date' => 'required|date',
            'comment' => 'nullable|string|max:255', // Now it's optional
        ]);

        // Create Labwork record
        Labwork::create([
            'patient_id' => $request->patient_id,
            'lab_id' => $request->lab_id,
            'treatment_id' => $request->treatment_id,
            'patient_treatment_id' => $request->patient_treatment_id,
            'entry_date' => $request->entry_date,
            'comment' => $request->comment,  // Save the comment
        ]);

        // Redirect back to the labwork index with success message
        return redirect()->route('labworks.index', ['patient_id' => $request->patient_id])
            ->with('success', 'Labwork added successfully.');
    }


    public function markCollected($id)
    {
        $labwork = Labwork::findOrFail($id);
        $labwork->collection_date = now();
        $labwork->save();

        return back()->with('success', 'Labwork marked as collected.');
    }

    public function markReceived($id)
    {
        $labwork = Labwork::findOrFail($id);
        $labwork->received_date = now();
        $labwork->save();

        return back()->with('success', 'Labwork marked as received.');
    }

    public function fullList(Request $request)
    {
        $labworks = Labwork::query();

        if ($request->filter == 'pending_collection') {
            $labworks->whereNull('collection_date'); // Only show labwork pending collection
        } elseif ($request->filter == 'pending_received') {
            $labworks->whereNotNull('collection_date')
                ->whereNull('received_date'); // Only show collected but not received labwork
        }

        $labworks = $labworks->paginate(10);

        return view('labworks.full_list', compact('labworks'));
    }

    public function destroy($id)
    {
        $labwork = Labwork::findOrFail($id);
        $patient_id = $labwork->patient_id;
        $labwork->delete();

        return redirect()->route('labworks.index', ['patient_id' => $patient_id])
            ->with('success', 'Labwork deleted successfully.');
    }
}
