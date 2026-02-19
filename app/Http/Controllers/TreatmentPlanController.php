<?php

namespace App\Http\Controllers;

use App\Models\IntraoralExamination;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatmentPlanController extends Controller
{
    public function index(Request $request, $patientId)
    {

        $patient = Patient::findOrFail($patientId);
        $selectedDate = $request->get('date') ?? '';

        // // All examinations for the date (for the table/list)
        if ($selectedDate) {

            $examinations = IntraoralExamination::where('patient_id', $patientId)
                ->whereDate('exam_date', $selectedDate)
                ->orderBy('exam_date', 'desc')
                ->get();
        } else {

            $examinations = IntraoralExamination::where('patient_id', $patientId)
                ->orderBy('exam_date', 'desc')
                ->get();
        }

        // // Single examination record used to populate the form/chart (take latest)
        $examination = IntraoralExamination::where('patient_id', $patientId)
            ->whereDate('exam_date', $selectedDate)
            ->orderBy('id', 'desc')
            ->first() ?? new IntraoralExamination();

        return view('TreatmentPlan.index', compact(
            'patient',
            'examinations',
            'examination',
            'selectedDate'
        ));
        // return view('TreatmentPlan.index', compact('patient'));
    }

    public function add(Request $request, $patientId)
    {

        $patient = Patient::findOrFail($patientId);
        $selectedTeeth = [];
        return view('TreatmentPlan.add', compact('patient', 'selectedTeeth'));
    }

    public function store(Request $request, $patientId)
    {
        $request->validate([
            'exam_date' => 'required|date',
            'plaque' => 'nullable|string|in:+,++,+++',
            'calculus' => 'nullable|string|in:+,++,+++',
            'stains' => 'nullable|string|in:+,++,+++',
            'bop' => 'nullable|string|in:Present,Absent,Localized,Generalized',
            'impacted' => 'nullable|string|max:500',
            'pocket' => 'nullable|string|max:500',
            'vitality' => 'nullable|string|max:500',
            'sensitivity' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        // These form fields will contain CSV (11,12,13 etc)
        $csvFields = [
            'caries',
            'pain_op',
            'missing',
            'mobility',
            'prosthesis'
        ];

        $data = [
            'patient_id'  => $patientId,
            'doctor_id'   => Auth::id(),
            'exam_date'   => $request->exam_date,
            'plaque'      => $request->plaque,
            'calculus'    => $request->calculus,
            'stains'      => $request->stains,
            'impacted'    => $request->impacted,
            'Pocket'      => $request->pocket,
            'vitality'    => $request->vitality,
            'Sensitivity' => $request->sensitivity,
            'BOP'         => $request->bop,
            'notes'       => $request->notes,
        ];


        // Convert CSV → JSON
        foreach ($csvFields as $field) {

            if ($request->$field) {
                $values = collect(explode(',', $request->$field))
                    ->map(fn($t) => trim($t))
                    ->filter(fn($t) => is_numeric($t))
                    ->map(fn($t) => intval($t))
                    ->sort()
                    ->values()
                    ->toArray();

                // ✔ Save as "21,22"
                $data[$field] = implode(',', $values);
            } else {
                $data[$field] = null;
            }
        }


        // ALWAYS INSERT NEW RECORD — no update
        IntraoralExamination::create($data);

        return redirect()->route('IntraoralExamination.index', [
            'patient' => $patientId
            //'date' => $request->exam_date
        ])->with('success', "Intraoral examination saved successfully!");
    }

    public function destroy($id)
    {
        $examination = IntraoralExamination::findOrFail($id);
        $patientId = $examination->patient_id;
        $examination->delete();

        return redirect()->route('IntraoralExamination.index', $patientId)
            ->with('success', 'Examination deleted successfully!');
    }
}
