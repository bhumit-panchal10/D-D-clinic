<?php

namespace App\Http\Controllers;

use App\Models\ReasonForVisitToday;
use App\Models\Patient;
use Illuminate\Http\Request;

class ReasonForVisitTodayController extends Controller
{
    public function index(Request $request, $patient_id)
    {
        //dd($patient_id);
        $patient = Patient::findOrFail($patient_id); // Fetch patient details

        $ReasonForVisitToday = ReasonForVisitToday::where('patient_id', $patient_id)->paginate(config('app.per_page'));
        return view('ReasonForVisitToday.index', compact('ReasonForVisitToday', 'patient'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'facial_asymmetry' => 'nullable',
            'TMJ' => 'nullable',
            'Lymphadenopathy' => 'nullable',
            'date' => 'required|date',
            'comment' => 'nullable|string', // Now it's optional
        ]);

        // Create Labwork record
        ReasonForVisitToday::create([
            'patient_id' => $request->patient_id,
            'facial_asymmetry' => $request->facial_asymmetry,
            'TMJ' => $request->TMJ,
            'Lymphadenopathy' => $request->Lymphadenopathy,
            'date' => $request->date,
            'comment' => $request->comments,  // Save the comment
        ]);

        // Redirect back to the labwork index with success message
        return redirect()->route('ReasonForVisitToday.index', ['patient_id' => $request->patient_id])
            ->with('success', 'ReasonForVisitToday added successfully.');
    }

    public function edit($id)
    {
        $ReasonForVisit = ReasonForVisitToday::findOrFail($id);

        return response()->json($ReasonForVisit);
    }

    public function update(Request $request)
    {
        $request->validate([
            'edit_date' => 'required|date',
            'edit_facial_asymmetry' => 'required',
            'edit_TMJ' => 'required',
            'edit_Lymphadenopathy' => 'required',
            'comments' => 'nullable|string'
        ]);

        $data = [
            'date' => $request->edit_date,
            'facial_asymmetry' => $request->edit_facial_asymmetry,
            'TMJ' => $request->edit_TMJ,
            'Lymphadenopathy' => $request->edit_Lymphadenopathy,
            'comment' => $request->comments,
            'updated_at' => now(),

        ];

        ReasonForVisitToday::where("id", $request->id)->update($data);

        return redirect()->route('ReasonForVisitToday.index', $request->patient_id)->with('success', 'Reason For Visit Today updated successfully.');
    }

    public function destroy($id)
    {

        $ReasonForVisit = ReasonForVisitToday::findOrFail($id);
        $patient_id = $ReasonForVisit->patient_id;
        $ReasonForVisit->delete();

        return redirect()->route('ReasonForVisitToday.index', ['patient_id' => $patient_id])
            ->with('success', 'Reason For Visit Today deleted successfully.');
    }
}
