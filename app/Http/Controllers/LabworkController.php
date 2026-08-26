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
        $labworks = Labwork::with('lab')->where('patient_id', $patient_id)->paginate(config('app.per_page'));

        return view('labworks.index', compact('patient', 'labs', 'treatments', 'patientTreatments', 'labworks'));
    }

    public function edit($id)
    {
        $labworks = Labwork::with('lab')->where('id', $id)->first();
        return response()->json($labworks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'lab' => 'required|exists:labs,id',
            'given_date' => 'required|date',
            'given_by' => 'nullable|string',
            'work_code' => 'nullable|string',
        ]);

        Labwork::create([
            'patient_id' => $request->patient_id,
            'lab_id' => $request->lab,
            'collection_date' => $request->given_date,
            'entry_date' => $request->entry_date,
            'given_by' => $request->given_by,
            'work_code' => $request->work_code,
            'created_at' => now()
        ]);

        // Redirect back to the labwork index with success message
        return redirect()->route('labworks.index', ['patient_id' => $request->patient_id])
            ->with('success', 'Labwork added successfully.');
    }

    public function update(Request $request)
    {

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'lab' => 'required|exists:labs,id',
            'date' => 'required|date',
            'given_by' => 'nullable|string',
            'work_code' => 'nullable|string',
        ]);

        $data = [
            'lab_id' => $request->lab,
            'collection_date' => $request->date,
            'entry_date' => $request->entrydate,
            'given_by' => $request->given_by,
            'work_code' => $request->work_code,
            'updated_at' => now(),

        ];

        Labwork::where("id", $request->id)->update($data);

        return redirect()->route('labworks.index', $request->patient_id)->with('success', 'Labwork updated successfully.');
    }

    public function received(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'id' => 'required|exists:labworks,id',
            'received_date' => 'required|date',
            'received_by' => 'nullable|string',
            'job_work_code' => 'nullable|string',
        ]);
        $data = [
            'received_date' => $request->received_date,
            'received_by' => $request->received_by,
            'job_work_no' => $request->job_work_code,
            'updated_at' => now(),

        ];
        Labwork::where("id", $request->id)->update($data);

        // return redirect()->route('labworks.index', $request->patient_id)->with('success', 'Received Date updated successfully.');
        return redirect()->back()->with('success', 'Received Date updated successfully.');
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
       // dd($labworks);
        return view('labworks.full_list', compact('labworks'));
    }

    public function destroy($id)
    {
        $labwork = Labwork::findOrFail($id);
        $patient_id = $labwork->patient_id;
        $labwork->delete();

        // return redirect()->route('labworks.index', ['patient_id' => $patient_id])->with('success', 'Labwork deleted successfully.');
        return redirect()->back()->with('success', 'Labwork deleted successfully.');
    }
}
