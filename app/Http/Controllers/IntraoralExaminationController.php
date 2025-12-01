<?php

namespace App\Http\Controllers;

use App\Models\IntraoralExamination;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntraoralExaminationController extends Controller
{
    public function index($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $examination = IntraoralExamination::where('patient_id', $patientId)->first();

        return view('IntraoralExamination.index', compact('patient', 'examination'));
    }

    public function store(Request $request, $patientId)
    {
        $request->validate([
            // 'exam_date' => 'required|date',
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

        // Map form field names to your database column names
        $formToDbMapping = [
            'caries_teeth' => 'caries',           // form => db
            'pain_op_teeth' => 'pain_op',         // form => db
            'missing_teeth' => 'missing',         // form => db
            'mobility_teeth' => 'mobility',       // form => db
            'prosthesis_teeth' => 'prosthesis',   // form => db
            'bop' => 'BOP',                       // form => db
            'pocket' => 'Pocket',                 // form => db
            'sensitivity' => 'Sensitivity',       // form => db
        ];

        // Start with basic data
        $data = [
            'patient_id' => $patientId,
            'doctor_id' => Auth::id(),
            'exam_date' => $request->exam_date,
            'plaque' => $request->plaque,
            'calculus' => $request->calculus,
            'stains' => $request->stains,
            'impacted' => $request->impacted,
            'vitality' => $request->vitality,
            'notes' => $request->notes,
        ];

        // Add mapped fields
        foreach ($formToDbMapping as $formField => $dbField) {
            if ($request->has($formField)) {
                if (str_contains($formField, '_teeth')) {
                    // Process teeth fields (caries, pain_op, etc.)
                    if (!empty($request->$formField)) {
                        $teeth = array_filter(
                            array_map('trim', explode(',', $request->$formField)),
                            function ($tooth) {
                                return !empty($tooth) && is_numeric($tooth);
                            }
                        );

                        // Sort teeth numerically for consistency
                        sort($teeth, SORT_NUMERIC);

                        // Store as JSON string (since your columns are likely VARCHAR/TEXT)
                        $data[$dbField] = implode(',', $teeth);
                    } else {
                        $data[$dbField] = null;
                    }
                } else {
                    // Process regular fields (BOP, Pocket, Sensitivity)
                    $data[$dbField] = $request->$formField;
                }
            }
        }

        // Check if examination already exists for this patient and date
        $existingExam = IntraoralExamination::where('patient_id', $patientId)
            ->whereDate('exam_date', $request->exam_date)
            ->first();

        if ($existingExam) {
            // Update existing record
            $existingExam->update($data);
            $examination = $existingExam;
        } else {
            // Create new record
            $examination = IntraoralExamination::create($data);
        }

        return redirect()->route('IntraoralExamination.index', ['patient' => $patientId, 'date' => $request->exam_date])
            ->with('success', 'Intraoral examination ' . ($existingExam ? 'updated' : 'saved') . ' successfully!');
    }

    public function destroy($id)
    {
        $examination = IntraoralExamination::findOrFail($id);
        $patientId = $examination->patient_id;
        $examination->delete();

        return redirect()->route('intraoral.index', $patientId)
            ->with('success', 'Examination deleted successfully!');
    }
}
