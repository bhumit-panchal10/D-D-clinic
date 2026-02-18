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

    public function indexdemo(Request $request, $patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $selectedDate = $request->get('date') ?? '';

        // All examinations for the date (for the table/list)
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

        // Single examination record used to populate the form/chart (take latest)
        $examination = IntraoralExamination::where('patient_id', $patientId)
            ->whereDate('exam_date', $selectedDate)
            ->orderBy('id', 'desc')
            ->first() ?? new IntraoralExamination();

        return view('IntraoralExamination.indexdemo', compact(
            'patient',
            'examinations',
            'examination',
            'selectedDate'
        ));
    }


    // public function index(Request $request, $patientId)
    // {
    //     $patient = Patient::findOrFail($patientId);
    //     $selectedDate = $request->get('date') ?? '';
    //     $conditions = '';

    //     if ($selectedDate) {

    //         $examinations = IntraoralExamination::where('patient_id', $patientId)
    //             ->whereDate('exam_date', $selectedDate)
    //             ->orderBy('exam_date', 'desc')
    //             ->get();

    //         $conditions = TeethComment::where(['patient_id' => $patientId, 'intraoralexamination_id' => $examinations->id])
    //             ->get()
    //             ->keyBy('type_id');
    //     } else {

    //         $examinations = IntraoralExamination::where('patient_id', $patientId)
    //             ->orderBy('exam_date', 'desc')
    //             ->get();
    //     }

    //     // Single examination record used to populate the form/chart (take latest)
    //     $examination = IntraoralExamination::where('patient_id', $patientId)
    //         ->whereDate('exam_date', $selectedDate)
    //         ->orderBy('id', 'desc')
    //         ->first() ?? new IntraoralExamination();
    //     return view('IntraoralExamination.index', compact(
    //         'patient',
    //         'examinations',
    //         'examination',
    //         'selectedDate',
    //         'conditions'
    //     ));
    // }

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
                    ->keyBy('type_id');
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

    public function saveCondition(Request $request)
    {
        $request->validate([
            'patient_id' => 'required',
            'type_id'    => 'required',
            'intraoralexaminationid' => 'required',
            'comment'    => 'nullable|string|max:1000',
        ]);

        TeethComment::updateOrCreate(
            [
                'patient_id' => $request->patient_id,
                'type_id'    => $request->type_id,
                'intraoralexamination_id' => $request->intraoralexaminationid,
            ],
            [
                'comment' => $request->comment,
            ]
        );

        return response()->json([
            'status'  => true,
            'message' => 'Saved successfully'
        ]);
    }

    public function adddemo(Request $request, $patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $ReasonForVisitToday = ReasonForVisitToday::where('patient_id', $patientId)->paginate(config('app.per_page'));

        $selectedTeeth = [];
        return view('IntraoralExamination.demoadd', compact('patient', 'selectedTeeth', 'ReasonForVisitToday'));
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
        $examination->delete();

        return redirect()->route('IntraoralExamination.index', $patientId)
            ->with('success', 'Examination deleted successfully!');
    }
}
