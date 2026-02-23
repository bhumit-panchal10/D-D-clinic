<?php

namespace App\Http\Controllers;

use App\Models\IntraoralExamination;
use App\Models\Patient;
use App\Models\TreatmentPlanDetail;
use App\Models\TreatmentPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatmentPlanController extends Controller
{

    // public function index(Request $request, $patientId)
    // {
    //     $patient = Patient::findOrFail($patientId);

    //     $selectedDate = $request->get('date');

    //     $query = TreatmentPlan::where('patient_id', $patientId);

    //     if ($selectedDate) {
    //         $query->whereDate('date', $selectedDate);
    //     }

    //     $examinations = $query->orderBy('date', 'desc')->get();

    //     // Default selected record
    //     if ($selectedDate) {
    //         $examination = $examinations->first();
    //     } else {
    //         $examination = TreatmentPlan::where('patient_id', $patientId)
    //             ->orderBy('date', 'desc')
    //             ->first();
    //     }

    //     return view('TreatmentPlan.index', compact(
    //         'patient',
    //         'examinations',
    //         'examination',
    //         'selectedDate'
    //     ));
    // }

    public function index(Request $request, $patientId)
    {
        $patient = Patient::findOrFail($patientId);

        $selectedDate = $request->get('date') ?? '';

        $conditions = collect(); // default empty
        $examination = null;

        if ($selectedDate) {

            // Selected date ka TreatmentPlan
            $examination = TreatmentPlan::where('patient_id', $patientId)
                ->whereDate('date', $selectedDate)
                ->orderBy('id', 'desc')
                ->first();

            if ($examination) {

                // Detail table se data fetch
                $conditions = TreatmentPlanDetail::where('patient_id', $patientId)
                    ->where('Treatment_plan_id', $examination->id)
                    ->get()
                    ->groupBy(['type_id', 'tooth_no']);
            }

            // Sidebar list ke liye sab records
            $examinations = TreatmentPlan::where('patient_id', $patientId)
                ->orderBy('date', 'desc')
                ->get();
        } else {

            // Agar date select nahi ki
            $examinations = TreatmentPlan::where('patient_id', $patientId)
                ->orderBy('date', 'desc')
                ->get();

            $examination = new TreatmentPlan();
        }

        return view('TreatmentPlan.index', compact(
            'patient',
            'examinations',
            'examination',
            'selectedDate',
            'conditions'
        ));
    }

    public function add(Request $request, $patientId)
    {
        $examination = '';
        $patient = Patient::findOrFail($patientId);
        $selectedTeeth = [];
        return view('TreatmentPlan.add', compact('patient', 'examination', 'selectedTeeth'));
    }

    public function saveCondition(Request $request)
    {

        foreach ($request->notes as $note) {

            if (!empty($note['comment'])) {

                TreatmentPlanDetail::where('Treatment_plan_id', $request->treatmentplan_id)
                    ->where('type_id', $request->type_id)
                    ->where('tooth_no', $note['tooth_no'])
                    ->update([
                        'comment' => $note['comment']
                    ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Saved Successfully'
        ]);
    }

    public function store(Request $request, $patientId)
    {

        $request->validate([
            'exam_date' => 'required|date',
            'impacted'  => 'nullable|string|max:500',
            'pocket'    => 'nullable|string|max:500',
            'vitality'  => 'nullable|string|max:500',
        ]);


        $csvFields = [
            'RCT_IPC',
            'Extraction',
            'Restoration',
            'Prosthesis'
        ];


        $data = [
            'patient_id'      => $patientId,
            'date'            => $request->exam_date,
            'Dentures'        => $request->impacted,
            'implants'        => $request->pocket,
            'other_treatment' => $request->vitality,
        ];


        foreach ($csvFields as $field) {

            if ($request->filled($field)) {

                $values = collect(explode(',', $request->$field))
                    ->map(fn($t) => trim($t))
                    ->filter(fn($t) => is_numeric($t))
                    ->map(fn($t) => intval($t))
                    ->sort()
                    ->values()
                    ->toArray();

                $data[$field] = implode(',', $values);
            } else {
                $data[$field] = null;
            }
        }


        $columnMapping = [
            'scaling'       => 'Scaling',
            'polishing'     => 'polishing',
            'grinding'      => 'Grinding',
            'bleaching'     => 'Bleaching',
            'smile_design'  => 'smile_design',
            'orthodontics'  => 'orthodontics',
            'surgery'       => 'surgery',
            'biopsy'        => 'biopsy',
        ];

        foreach ($columnMapping as $key => $column) {

            $data[$column] =
                isset($request->treatment[$key]['checked']) ? 1 : 0;

            $data[$column . '_desc'] =
                $request->treatment[$key]['note'] ?? null;
        }


        $treatmentPlan = TreatmentPlan::create($data);

        $typeMapping = [
            'RCT_IPC'     => 1,
            'Extraction'  => 2,
            'Restoration' => 3,
            'Prosthesis'  => 4,
        ];

        foreach ($csvFields as $field) {

            if (!empty($data[$field])) {

                $teethArray = explode(',', $data[$field]);

                foreach ($teethArray as $tooth) {

                    TreatmentPlanDetail::create([
                        'patient_id'        => $patientId,
                        'Treatment_plan_id' => $treatmentPlan->id,
                        'type_id'           => $typeMapping[$field],
                        'tooth_no'          => $tooth,
                        'comment'           => null,
                    ]);
                }
            }
        }


        return redirect()
            ->route('TreatmentPlan.index', ['patient' => $patientId])
            ->with('success', 'Treatment Plan saved successfully!');
    }

    public function destroy($id)
    {

        $examination = TreatmentPlan::findOrFail($id);
        $patientId = $examination->patient_id;
        TreatmentPlanDetail::where('Treatment_plan_id', $id)->delete();

        $examination->delete();

        return redirect()->route('TreatmentPlan.index', $patientId)
            ->with('success', 'Treatment Plan deleted successfully!');
    }
}
