<?php

namespace App\Http\Controllers;

use App\Models\IntraoralExamination;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntraoralExaminationController extends Controller
{
    public function index(Request $request, $patientId)
    {
        $patient = Patient::findOrFail($patientId);

        // Get the selected date from request, default to today
        $selectedDate = $request->get('date') ?? date('Y-m-d');

        // Find examination for the selected date
        $examination = IntraoralExamination::where('patient_id', $patientId)
            ->whereDate('exam_date', $selectedDate)
            ->first();

        if ($examination) {
            foreach (['caries', 'pain_op', 'missing', 'mobility', 'prosthesis'] as $field) {
                $examination->$field = !empty($examination->$field)
                    ? explode(',', $examination->$field)
                    : [];
            }
        }
        // Get all examination dates for this patient (for history navigation)
        $allExamDates = IntraoralExamination::where('patient_id', $patientId)
            ->orderBy('exam_date', 'desc')
            ->get()
            ->map(function ($exam) {
                return [
                    'date' => $exam->exam_date,
                    'formatted' => \Carbon\Carbon::parse($exam->exam_date)->format('d M Y')
                ];
            });

        return view('IntraoralExamination.index', compact('patient', 'examination', 'selectedDate', 'allExamDates'));
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

        // Map FORM → DB fields
        $fields = [
            'caries_teeth'      => 'caries',
            'pain_op_teeth'     => 'pain_op',
            'missing_teeth'     => 'missing',
            'mobility_teeth'    => 'mobility',
            'prosthesis_teeth'  => 'prosthesis',
            'bop'               => 'BOP',
            'pocket'            => 'Pocket',
            'sensitivity'       => 'Sensitivity',
        ];

        $data = [
            'patient_id' => $patientId,
            'doctor_id'  => Auth::id(),
            'exam_date'  => $request->exam_date,
            'plaque'     => $request->plaque,
            'calculus'   => $request->calculus,
            'stains'     => $request->stains,
            'impacted'   => $request->impacted,
            'vitality'   => $request->vitality,
            'notes'      => $request->notes,
        ];

        foreach ($fields as $formField => $dbField) {

            if (str_contains($formField, '_teeth')) {

                // Convert CSV string → array
                $values = collect(explode(',', $request->$formField))
                    ->map(fn($t) => trim($t))
                    ->filter(fn($t) => is_numeric($t))
                    ->map(fn($t) => intval($t))
                    ->sort()
                    ->values()
                    ->toArray();

                // Save JSON array
                $data[$dbField] = $values;
            } else {

                // For normal text fields
                $data[$dbField] = $request->$formField ?? null;
            }
        }

        // Check if exam already exists for this date
        $exam = IntraoralExamination::where('patient_id', $patientId)
            ->whereDate('exam_date', $request->exam_date)
            ->first();

        if ($exam) {
            $exam->update($data);
            $message = 'updated';
        } else {
            $exam = IntraoralExamination::create($data);
            $message = 'saved';
        }

        return redirect()->route('IntraoralExamination.index', [
            'patient' => $patientId,
            'date'    => $request->exam_date
        ])->with('success', "Intraoral examination {$message} successfully!");
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
