<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\ReasonForVisitToday;
use App\Models\IntraoralExamination;
use App\Models\TreatmentPlan;
use App\Models\Notes;
use App\Models\ClinicCaseCounters;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\PatientsConcernForm;

class PatientController extends Controller
{
    public function overview(Request $request, $patient_id = null)
    {
        $patient = Patient::findOrFail($patient_id); // Fetch patient details
        $ReasonForVisitToday = ReasonForVisitToday::where('patient_id', $patient_id)->paginate(config('app.per_page'));
        $examinations = IntraoralExamination::where('patient_id', $patient_id)
            ->orderBy('exam_date', 'desc')
            ->paginate(config('app.per_page'));
        $examinationsplan = TreatmentPlan::where('patient_id', $patient_id)
            ->orderBy('date', 'desc')
            ->paginate(config('app.per_page'));
        $notes = Notes::with(['treatment', 'subTreatment', 'images'])
            ->where('patient_id', $patient_id)->paginate(config('app.per_page'));
        return view('patient.overview', compact('patient', 'ReasonForVisitToday', 'examinations', 'examinationsplan', 'notes'));
    }
    // Display patients list
    public function autocomplete(Request $request)
    {
        $search = trim($request->search);

        $results = Patient::select(
            'id',
            'name',
            'middle_name',
            'last_name',
            'mobile1',
            'case_no'
        )
            ->where(function ($query) use ($search) {

                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('middle_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('mobile1', 'LIKE', "%{$search}%")
                    ->orWhere('mobile2', 'LIKE', "%{$search}%")
                    ->orWhere('case_no', 'LIKE', "%{$search}%");

                $query->orWhereRaw("
            CONCAT(
                COALESCE(name, ''),
                ' ',
                COALESCE(middle_name, ''),
                ' ',
                COALESCE(last_name, '')
            ) LIKE ?
        ", ["%{$search}%"]);
            })

            // Exact match first
            ->orderByRaw("CASE WHEN case_no = ? THEN 0 ELSE 1 END", [$search])

            ->orderBy('case_no')

            ->limit(10)
            ->get();

        return response()->json($results);
    }

    // public function index(Request $request)
    // {
    //     $query = Patient::query();
    //     if ($request->items) {
    //         $items = $request->items ?? 100;
    //         $minutes = 30 * 24 * 60 * 60;
    //         Cookie::forget('Pagination');
    //         Cookie::queue('Pagination', $items, $minutes);
    //         $get_all_cookies = $items;
    //     } else {
    //         $get_all_cookies = Cookie::get('Pagination');
    //     }

    //     // Search logic (by name or mobile)
    //     if ($request->filled('search')) {
    //         $searchTerm = $request->search;
    //         $query->where('name', 'like', "%{$searchTerm}%")
    //             ->orWhere('mobile2', 'like', "%{$searchTerm}%")
    //             ->orWhere('mobile1', 'like', "%{$searchTerm}%");
    //     }

    //     // Maintain search term in pagination links
    //     $items = $get_all_cookies;
    //     $patients = $query->orderBy('id', 'desc')->paginate($get_all_cookies)->appends(['search' => $request->search]);

    //     return view('patient.index', compact('patients', 'items'));
    // }

    public function index(Request $request)
    {
        $query = Patient::query();

        // Pagination Cookie
        if ($request->items) {

            $items = $request->items ?? 100;

            $minutes = 30 * 24 * 60 * 60;

            Cookie::forget('Pagination');

            Cookie::queue('Pagination', $items, $minutes);

            $get_all_cookies = $items;
        } else {

            $get_all_cookies = Cookie::get('Pagination', 10);
        }

        // Search Logic
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                // Single Field Search
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('middle_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('mobile1', 'LIKE', "%{$search}%")
                    ->orWhere('mobile2', 'LIKE', "%{$search}%")
                    ->orWhere('case_no', 'LIKE', "%{$search}%");

                // Full Name Search
                $q->orWhereRaw("
                CONCAT(
                    COALESCE(name, ''),
                    ' ',
                    COALESCE(middle_name, ''),
                    ' ',
                    COALESCE(last_name, '')
                ) LIKE ?
            ", ["%{$search}%"]);
            });

            // Exact case_no first
            $query->orderByRaw(
                "CASE WHEN case_no = ? THEN 0 ELSE 1 END",
                [$search]
            );
        }

        $items = $get_all_cookies;

        $patients = $query
            ->orderBy('id', 'desc')
            ->paginate($items)
            ->appends([
                'search' => $request->search
            ]);

        return view('patient.index', compact('patients', 'items'));
    }

    public function consentForm(Patient $patient)
    {
        return view('patient.consent-form', compact('patient'));
    }

    public function saveConsentForm(Request $request, Patient $patient)
    {
        DB::beginTransaction();

        // try {

        if (empty($request->patient_signature)) {

            return back()->with('error', 'Please sign before saving.');
        }

        $caseNo = $patient->case_no;

        $patientName = preg_replace('/[^A-Za-z0-9]/', '_', $patient->name);

        /*
            |--------------------------------------------------------------------------
            | Save Signature
            |--------------------------------------------------------------------------
            */

        $signatureFolder = '/home1/getdemo/public_html/dental_clinic/patient_signature';

        if (!File::exists($signatureFolder)) {

            File::makeDirectory($signatureFolder, 0777, true);
        }

        $image_parts = explode(";base64,", $request->patient_signature);

        $image_type_aux = explode("image/", $image_parts[0]);

        $image_type = $image_type_aux[1];

        $image_base64 = base64_decode($image_parts[1]);

        $signatureName = $caseNo . "_" . $patientName . "." . $image_type;

        $signaturePath = $signatureFolder . "/" . $signatureName;

        file_put_contents($signaturePath, $image_base64);

        /*
            |--------------------------------------------------------------------------
            | Generate PDF
            |--------------------------------------------------------------------------
            */
        // dd($signaturePath, file_exists($signaturePath));
        $pdf = Pdf::loadView(
            'patient.consent-form-pdf',
            [
                'patient' => $patient,
                'signature' => $signaturePath
            ]
        );

        /*
            |--------------------------------------------------------------------------
            | Save PDF
            |--------------------------------------------------------------------------
            */

        $pdfFolder = '/home1/getdemo/public_html/dental_clinic/patinet_conser_form';

        if (!File::exists($pdfFolder)) {

            File::makeDirectory($pdfFolder, 0777, true);
        }

        $pdfName = $caseNo . "_" . $patientName . ".pdf";

        $pdf->save($pdfFolder . "/" . $pdfName);

        /*
            |--------------------------------------------------------------------------
            | Save Database
            |--------------------------------------------------------------------------
            */

        PatientsConcernForm::create([

            'iPatientId' => $patient->id,

            'strFileName' => $pdfName,

            'strIP' => $request->ip(),

        ]);

        DB::commit();

        return redirect()
            ->route('patient.index')
            ->with('success', 'Consent Form saved successfully.');

        // } catch (\Exception $e) {

        //     DB::rollBack();

        //     return back()->with('error', $e->getMessage());

        // }
    }

    // public function saveConsentForm(Request $request, Patient $patient)
    // {
    //     DB::beginTransaction();

    //     // try {

    //         // Create Folder
    //         $folder = public_path('patinet_conser_form');

    //         if (!File::exists($folder)) {
    //             File::makeDirectory($folder, 0777, true, true);
    //         }

    //         // File Name
    //         $patientName = preg_replace('/[^A-Za-z0-9]/', '_', $patient->name);

    //         $fileName = $patient->case_no . '_' . $patientName . '.pdf';

    //         // Generate PDF
    //         $pdf = Pdf::loadView(
    //             'patient.consent-form-pdf',
    //             compact('patient')
    //         );

    //         // Save PDF
    //         $pdf->save($folder . '/' . $fileName);

    //         // Store Record
    //         PatientsConcernForm::create([

    //             'iPatientId' => $patient->id,

    //             'strFileName' => $fileName,

    //             'strIP' => $request->ip(),

    //         ]);

    //         DB::commit();

    //         return redirect()
    //             ->route('patient.index')
    //             ->with('success', 'Consent form generated successfully.');

    //     // } catch (\Exception $e) {

    //     //     DB::rollBack();

    //     //     return back()->with('error', $e->getMessage());

    //     // }
    // }

    public function show($id)
    {
        $patient = Patient::findOrFail($id);
        return view('patient.show', compact('patient'));
    }

    public function complete(Request $request, Patient $patient)
    {
        $noteId = $request->input('note_id');
        $paymentId = $request->input('payment_id');

        // Debugging: Log the received noteId and paymentId
        \Log::info('Completing patient with noteId: ' . $noteId . ' and paymentId: ' . $paymentId);

        // Mark the patient as completed
        $patient->is_completed = true;
        $patient->save();

        // Mark the note as completed if noteId is provided
        if ($noteId) {
            $note = $patient->notes()->find($noteId);
            if ($note) {
                $note->is_completed = true;
                $note->save();
            }
        }

        // Mark the payment as completed if paymentId is provided
        if ($paymentId) {
            $payment = $patient->payments()->find($paymentId);
            if ($payment) {
                $payment->is_completed = true;
                $payment->save();
            }
        }

        return redirect()->back()->with('success', 'Patient marked as complete.');
    }

    public function getPatientDetails($id)
    {

        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }
        return response()->json([
            'contact_no' => $patient->mobile1,
            'email' => $patient->email,
        ]);
    }

    // Show create form
    public function create()
    {

        $caseMaster = ClinicCaseCounters::first();
        if (!$caseMaster) {
            return response()->json(['error' => 'Case master not configured.'], 400);
        }

        $caseno = ($caseMaster->prefix ?? '') . '-' .
            $caseMaster->last_number;
        if (isset($caseMaster->postfix) && $caseMaster->postfix != "") {
            $caseno .=  '-' . ($caseMaster->postfix ?? '');
        }
        return view('patient.create', compact('caseno'));
    }

    public function fetchByMobile(Request $request)
    {
        $mobile = $request->input('mobile');
        $clinicId = auth()->user()->clinic_id;

        $patient = Patient::where('mobile1', $mobile)
            ->where('clinic_id', $clinicId)
            ->first();

        if ($patient) {
            return response()->json(['exists' => true, 'patient' => $patient]);
        }

        return response()->json(['exists' => false]);
    }

    // Store patient data
    public function store(Request $request)
    {

        $clinicId = auth()->user()->clinic_id;

        $request->validate([
            'case_no' => 'required',
            'name' => 'nullable|string|max:30',
            'middle_name' => 'nullable',
            'last_name' => 'nullable',
            'blood_group' => 'nullable',
            'Occupation' => 'nullable',
            'company_name' => 'nullable',
            'mobile1' => 'required',
            'email' => 'nullable',
            'referred_name' => 'nullable',
            'relative_name' => 'nullable',
            'other_disease_comments' => 'nullable',
            //'mobile2' => 'required',
            'dob' => 'nullable|date',
            'weight' => 'nullable|numeric|min:0',
            'gender' => 'nullable',
            'Age' => 'nullable',
            'address' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|size:6',
            'reference_by' => 'nullable|string|max:30',
            'preferred_time_to_contact_you' => 'nullable',
            'emergency_contact_name' => 'nullable',
        ]);
        $caseMaster = ClinicCaseCounters::first();
        if (!$caseMaster) {
            return redirect()->back()->withErrors(['error' => 'Case master not found.']);
        }
        $caseno = ($caseMaster->prefix ?? '') . '-' .
            $caseMaster->last_number;
        if (isset($caseMaster->postfix) && $caseMaster->postfix != "") {
            $caseno .= '-' . ($caseMaster->postfix ?? '');
        }

        $data = $request->all();
        $data['clinic_id'] = auth()->user()->clinic_id;
        $data['case_no'] = $caseno;

        // Convert array to JSON
        $data['medical_history'] = json_encode($request->medical_history ?? []);
        $data['habit'] = json_encode($request->habit ?? []);
        $data['referred_by'] = json_encode($request->referred_by ?? []);
        $data['reminder'] = json_encode($request->reminder ?? []);

        $patient = Patient::create($data);

        $caseMaster->last_number += 1;
        $caseMaster->save();

        return redirect()->route('patient.consent.form', $patient->id)->with('success', 'Patient added successfully.');
    }

    // Show edit form
    public function edit(Patient $patient)
    {
        return view('patient.edit', compact('patient'));
    }

    // Update patient data
    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'case_no' => 'required',
            'name' => 'required|string|max:30',
            'middle_name' => 'nullable',
            'last_name' => 'nullable',
            'blood_group' => 'nullable',
            'Occupation' => 'nullable',
            'company_name' => 'nullable',
            'mobile1' => 'required',
            'mobile2' => 'nullable',
            'email' => 'nullable',
            'referred_name' => 'nullable',
            'relative_name' => 'nullable',
            'other_disease_comments' => 'nullable',
            'dob' => 'nullable|date',
            'weight' => 'nullable|numeric|min:0',
            'gender' => 'required',
            'Age' => 'nullable',
            'address' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|size:6',
            'reference_by' => 'nullable|string|max:30',
            'preferred_time_to_contact_you' => 'nullable',
            'emergency_contact_name' => 'nullable',
        ]);
        $data = $request->all();

        $data['medical_history'] = json_encode($request->medical_history ?? []);
        $data['habit'] = json_encode($request->habit ?? []);
        $data['referred_by'] = json_encode($request->referred_by ?? []);
        $data['reminder'] = json_encode($request->reminder ?? []);

        $patient->update($data);
        return redirect()->route('patient.index')->with('success', 'Patient updated successfully.');
    }

    // Delete patient data
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patient.index')->with('success', 'Patient deleted successfully.');
    }
}
