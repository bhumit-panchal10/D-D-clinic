<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Models\Patient;
use App\Models\PatientTreatment;
use App\Models\Treatment;
use App\Models\Lab;
use Illuminate\Http\Request;
use App\Models\PatientTreatmentDocument;
use App\Models\PatientTreatmentItem;

class PatientTreatmentController extends Controller
{

    public function search(Request $request, $patientId)
    {
        $patient = Patient::findOrFail($patientId);

        // base query for the Diagnosis list (patient_treatments table)
        $query = PatientTreatment::with(['Diagnosis', 'patientdocument'])
            ->where('patient_id', $patientId);

        // normalize incoming teeth string -> array of clean tokens
        $teeth = collect(explode(',', (string) $request->input('tooth_selection', '')))
            ->map(fn($t) => trim($t))
            ->filter(fn($t) => $t !== '')     // drop blanks
            ->values();

        if ($teeth->isNotEmpty()) {
            // match each tooth number against comma-separated column,
            // ignoring spaces stored in DB
            $query->where(function ($q) use ($teeth) {
                foreach ($teeth as $tooth) {
                    $q->orWhereRaw("FIND_IN_SET(?, REPLACE(tooth_selection,' ',''))", [$tooth]);
                }
            });
        }

        // paginate Diagnosis list; keep the filter across pages
        $patientTreatments = $query->orderBy('created_at', 'desc')
            ->paginate(config('app.per_page'), ['*'], 'diag_page')
            ->appends($request->only('tooth_selection'));

        // If you don’t need pagination here, use ->get() instead
        $diagnoses = PatientTreatmentItem::with(['treatment', 'Diagnosis', 'DiagnosisMaster'])
            ->where('patient_id', $patientId)
            ->paginate(config('app.per_page'), ['*'], 'tx_page'); // different page name

        $labs = Lab::all();
        $treatments = Treatment::all();

        // send back the normalized string for the search box (if you show it)
        $toothSelection = $teeth->implode(', ');

        return view(
            'patient_treatments.index',
            compact('diagnoses', 'toothSelection', 'patient', 'labs', 'patientTreatments', 'treatments')
        );
    }


    // public function search(Request $request, $patientId)
    // {
    //     $patient = Patient::findOrFail($patientId);
    //     $toothSelection = $request->get('tooth_selection'); // e.g., "11, 12"

    //     $query = PatientTreatment::with(['treatment', 'patientdocument'])
    //         ->where('patient_id', $patientId);

    //     $diagnoses = \App\Models\PatientTreatmentItem::with(['treatment', 'Diagnosis', 'DiagnosisMaster'])
    //         ->where('patient_id', $patientId)
    //         ->paginate();

    //     // 🔍 Filter by tooth numbers
    //     if ($request->filled('tooth_selection')) {
    //         $toothSelection = str_replace(' ', '', $request->get('tooth_selection'));
    //         $toothArray = explode(',', $toothSelection);

    //         $query->where(function ($q) use ($toothArray) {
    //             foreach ($toothArray as $tooth) {
    //                 $q->orWhereRaw("FIND_IN_SET(?, REPLACE(tooth_selection, ' ', ''))", [$tooth]);
    //             }
    //         });
    //     }


    //     $patientTreatments = $query->orderBy('created_at', 'desc')
    //         ->paginate(config('app.per_page'))
    //         ->appends($request->only('tooth_selection')); // keep filter on pagination


    //     $labs = Lab::all();
    //     $treatments = Treatment::all();

    //     return view('patient_treatments.index', compact('diagnoses', 'toothSelection', 'patient', 'labs', 'patientTreatments', 'treatments'));
    // }


    public function index($id)
    {
        $patient = Patient::findOrFail($id); // Fetch patient details
        $patientTreatments = PatientTreatment::with(['Diagnosis', 'patientdocument'])
            ->where('patient_id', $id)
            ->where('treatment_flag', 0)
            ->orderBy('created_at', 'desc') // Order by latest entry
            ->paginate(config('app.per_page'));

        $labs = Lab::all();
        $treatments = Treatment::all();
        $Diagnosis = Diagnosis::all();

        $usedTeeth = \App\Models\PatientTreatment::where('patient_id', $id)
            ->pluck('tooth_selection')      // e.g. ["11,12", "13"]
            ->toArray();

        $selectedTeeth = collect($usedTeeth)
            ->flatMap(fn($s) => array_filter(explode(',', str_replace(' ', '', (string)$s))))
            ->unique()
            ->values()
            ->toArray();

        $diagnoses = \App\Models\PatientTreatmentItem::with(['treatment', 'Diagnosis', 'DiagnosisMaster'])
            ->where('patient_id', $id)
            ->paginate();

        $yellowTeeth = \App\Models\PatientTreatment::where('patient_id', $id)
            ->where('treatment_flag', 0)
            ->pluck('tooth_selection')
            ->toArray();

        $yellowTeeth = collect($yellowTeeth)
            ->flatMap(fn($s) => array_filter(explode(',', str_replace(' ', '', (string)$s))))
            ->unique()
            ->values()
            ->toArray();

        // Teeth with finished treatment => GREEN
        $greenTeeth = \App\Models\PatientTreatment::where('patient_id', $id)
            ->where('treatment_flag', 1)
            ->pluck('tooth_selection')
            ->toArray();

        $greenTeeth = collect($greenTeeth)
            ->flatMap(fn($s) => array_filter(explode(',', str_replace(' ', '', (string)$s))))
            ->unique()
            ->values()
            ->toArray();


        return view('patient_treatments.index', compact(
            'patient',
            'labs',
            'treatments',
            'patientTreatments',
            'Diagnosis',
            'selectedTeeth',
            'diagnoses',
            'yellowTeeth',
            'greenTeeth'
        ));
    }


    public function create($id)
    {
        $patient = Patient::findOrFail($id);
        $treatments = Treatment::all();

        $usedTeeth = PatientTreatment::where('patient_id', $id)
            ->pluck('tooth_selection') // e.g. ["11,12,13", "14,15"]
            ->toArray();

        $selectedTeeth = collect($usedTeeth)
            ->flatMap(function ($teeth) {
                return explode(',', str_replace(' ', '', $teeth));
            })
            ->unique()
            ->values()
            ->toArray();

        return view('patient_treatments.create', compact('patient', 'treatments', 'selectedTeeth'));
    }

    /**
     * Store a newly created patient treatment in storage.
     */
    // public function store(Request $request, $id)
    // {
    //     $request->validate([
    //         'diagnosis_id' => 'required|exists:Diagnosis,id',
    //         'tooth_selection' => 'required|string',
    //         //'rate' => 'required|numeric',
    //     ]);



    //     $qty = $request->tooth_selection ? count(explode(',', $request->tooth_selection)) : 0;
    //     $amount = $request->rate * $qty;

    //     PatientTreatment::create([
    //         'patient_id' => $id,
    //         'diagnosis_id' => $request->diagnosis_id,
    //         'tooth_selection' => $request->tooth_selection,
    //         'rate' => $request->rate,
    //         'comment' => $request->comment,
    //         'qty' => $qty,
    //         'amount' => $amount,
    //     ]);

    //     return redirect()->route('patient_treatments.index', $id)->with('success', 'Patient Treatment added successfully.');
    // }


    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'diagnosis_type'       => 'required|in:1,2',                // 1 = General, 2 = Diagnosis(Local)
            'diagnosis_id'         => 'required|array|min:1',
            'diagnosis_id.*'       => 'required|exists:Diagnosis,id',
            'rate'                 => 'nullable|numeric',               // keep if you use rate
            'comment'              => 'nullable|string',
        ]);


        if ((int)$request->diagnosis_type === 2) {
            $request->validate([
                'tooth_selection' => 'required|string'
            ]);
        }
        $teeth = [];
        if ($request->filled('tooth_selection')) {
            $teeth = array_values(array_filter(array_map('trim', explode(',', $request->tooth_selection))));
        }
        $rate  = (float) ($request->rate ?? 0);

        // Prepare rows
        $rows = [];
        if ((int) $validated['diagnosis_type'] === 1) {
            // GENERAL: one row per diagnosis, full tooth list on each row
            $qty    = count($teeth);
            $amount = $rate * $qty;

            foreach ($validated['diagnosis_id'] as $diagId) {
                $rows[] = [
                    'patient_id'      => $id,
                    'diagnosis_id'    => $diagId,
                    'tooth_selection' => implode(', ', $teeth),
                    'rate'            => $rate,
                    'comment'         => $request->comment,
                    'date'         => $request->date,
                    'qty'             => $qty,
                    'amount'          => $amount,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];
            }
        } else {
            // DIAGNOSIS/LOCAL: one row per (diagnosis × tooth), qty=1 for each
            foreach ($validated['diagnosis_id'] as $diagId) {
                foreach ($teeth as $tooth) {
                    $rows[] = [
                        'patient_id'      => $id,
                        'diagnosis_id'    => $diagId,
                        'tooth_selection' => $tooth,   // single tooth per row
                        'rate'            => $rate,
                        'comment'         => $request->comment,
                        'date'         => $request->date,
                        'qty'             => 1,
                        'amount'          => $rate,    // rate * 1
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ];
                }
            }
        }
        //dd($rows);

        // Insert all rows
        \App\Models\PatientTreatment::insert($rows);

        return redirect()
            ->route('patient_treatments.index', $id)
            ->with('success', 'Patient Treatment(s) added successfully.');
    }


    /**
     * Remove the specified patient treatment from storage.
     */
    public function destroy($id)
    {

        $root = $_SERVER['DOCUMENT_ROOT'];

        $multiDocuments = PatientTreatmentDocument::with('patientTreatment')->where('patient_treatment_id', $id)->get();

        foreach ($multiDocuments as $multidoc) {
            $patientTreatment = $multidoc->patientTreatment;

            if ($patientTreatment) {
                $createdDate = $patientTreatment->created_at->format('Y/m/d'); // e.g. 2025/06/27
                $multiFilePath = $root . '/dental_clinic/patient_treatments/' . $createdDate . '/' . $patientTreatment->id . '/' . $multidoc->document;

                if (file_exists($multiFilePath)) {
                    unlink($multiFilePath);
                }
            }

            $multidoc->delete();
        }

        $treatment = PatientTreatment::findOrFail($id);
        $treatment->delete();

        return redirect()->back()->with('success', 'Patient treatment and documents deleted successfully.');
    }
}
