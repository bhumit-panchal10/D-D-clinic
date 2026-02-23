<?php

namespace App\Http\Controllers;

use App\Models\IntraoralExamination;
use App\Models\Patient;
use App\Models\ReasonForVisitToday;
use App\Models\TeethComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntraoralExaminationController extends Controller
{

    public function index(Request $request, $patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $selectedDate = $request->get('date') ?? '';

        $conditions = collect(); // default empty collection
        $examination = null;

        if ($selectedDate) {

            $examination = IntraoralExamination::where('patient_id', $patientId)
                ->whereDate('exam_date', $selectedDate)
                ->orderBy('id', 'desc')
                ->first();

            if ($examination) {

                $conditions = TeethComment::where('patient_id', $patientId)
                    ->where('intraoralexamination_id', $examination->id)
                    ->get()
                    ->groupBy(['type_id', 'tooth_no']);
            }

            $examinations = IntraoralExamination::where('patient_id', $patientId)
                ->orderBy('exam_date', 'desc')
                ->get();
        } else {

            $examinations = IntraoralExamination::where('patient_id', $patientId)
                ->orderBy('exam_date', 'desc')
                ->get();

            $examination = new IntraoralExamination();
        }

        return view('IntraoralExamination.index', compact(
            'patient',
            'examinations',
            'examination',
            'selectedDate',
            'conditions'
        ));
    }

    public function add(Request $request, $patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $selectedTeeth = [];
        return view('IntraoralExamination.add', compact('patient', 'selectedTeeth'));
    }

    // public function saveCondition(Request $request)
    // {
    //     $request->validate([
    //         'patient_id' => 'required',
    //         'type_id'    => 'required',
    //         'intraoralexaminationid' => 'required',
    //         'comment'    => 'nullable|string|max:1000',
    //     ]);

    //     TeethComment::updateOrCreate(
    //         [
    //             'patient_id' => $request->patient_id,
    //             'type_id'    => $request->type_id,
    //             'intraoralexamination_id' => $request->intraoralexaminationid,
    //         ],
    //         [
    //             'comment' => $request->comment,
    //         ]
    //     );

    //     return response()->json([
    //         'status'  => true,
    //         'message' => 'Saved successfully'
    //     ]);
    // }

    public function saveCondition(Request $request)
    {
        foreach ($request->notes as $note) {

            if (!empty($note['comment'])) {

                TeethComment::where('intraoralexamination_id', $request->intraoralexaminationid)
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
            'plaque' => 'nullable|string|in:+,++,+++',
            'calculus' => 'nullable|string|in:+,++,+++',
            'stains' => 'nullable|string|in:+,++,+++',
            'bop' => 'nullable|string|in:Present,Absent,Localized,Generalized',
            'impacted' => 'nullable|string|max:500',
            'pocket' => 'nullable|string|max:500',
            'vitality' => 'nullable|string|max:500',
            'sensitivity' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'diagnosis' => 'nullable|string|max:1000',
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
            'diagnosis'       => $request->diagnosis,
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
        $IntraoralExamination =  IntraoralExamination::create($data);
        $typeMapping = [
            'caries'     => 1,
            'pain_op'    => 2,
            'missing'    => 3,
            'mobility'   => 4,
            'prosthesis' => 5,
        ];
        foreach ($csvFields as $field) {

            if (!empty($data[$field])) {

                $teethArray = explode(',', $data[$field]);

                foreach ($teethArray as $tooth) {

                    TeethComment::create([
                        'patient_id'               => $patientId,
                        'intraoralexamination_id' => $IntraoralExamination->id,
                        'type_id'                  => $typeMapping[$field],
                        'tooth_no'                 => $tooth,
                        'comment'                  => null, // comment baad me update hoga
                    ]);
                }
            }
        }

        return redirect()->route('IntraoralExamination.index', [
            'patient' => $patientId
            //'date' => $request->exam_date
        ])->with('success', "Intraoral examination saved successfully!");
    }

    public function edit($patientId, $id)
    {
        $patient = Patient::findOrFail($patientId);
        $examination = IntraoralExamination::findOrFail($id);

        return view('IntraoralExamination.edit', compact(
            'patient',
            'examination'
        ));
    }

    public function update(Request $request, $patientId, $id)
    {
        $request->validate([
            'exam_date' => 'required|date',
        ]);

        $examination = IntraoralExamination::findOrFail($id);

        $csvFields = [
            'caries',
            'pain_op',
            'missing',
            'mobility',
            'prosthesis'
        ];

        $data = [
            'exam_date'   => $request->exam_date,
            'plaque'      => $request->plaque,
            'calculus'    => $request->calculus,
            'stains'      => $request->stains,
            'impacted'    => $request->impacted,
            'pocket'      => $request->pocket,
            'vitality'    => $request->vitality,
            'sensitivity' => $request->sensitivity,
            'bop'         => $request->bop,
            'notes'       => $request->notes,
        ];

        foreach ($csvFields as $field) {
            if ($request->$field) {
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

        $examination->update($data);

        return redirect()->route('IntraoralExamination.index', $patientId)
            ->with('success', 'Examination updated successfully!');
    }

    public function destroy($id)
    {
        $examination = IntraoralExamination::findOrFail($id);
        $patientId = $examination->patient_id;
        TeethComment::where('intraoralexamination_id', $id)->delete();

        $examination->delete();

        return redirect()->route('IntraoralExamination.index', $patientId)
            ->with('success', 'Examination deleted successfully!');
    }
}
