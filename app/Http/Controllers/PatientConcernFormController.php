<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConcernForm;
use App\Models\PatientConcerform;
use App\Models\Patient;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


class PatientConcernFormController extends Controller
{
    public function index($patient_id)
    {
        $patientconcernforms = PatientConcerform::with('concerform')->where(['patient_id' => $patient_id])->paginate(config('app.per_page'));
        // dd($patientconcernforms);
        $patient = Patient::findOrFail($patient_id);
        $Concerforms = ConcernForm::get();

        $clinic_id = auth()->user()->clinic_id;

        return view('patient_concerform.index', compact('patientconcernforms', 'patient', 'Concerforms', 'clinic_id'));
    }


    public function store(Request $request)
    {

        PatientConcerform::create([
            'patient_id' => $request->patient_id,
            'concern_form_id' => $request->concerform_id,
            'created_at' => now(),
        ]);

        return redirect()->route('patientconcernform.index', $request->patient_id)->with('success', 'concerform Add successfully.');
    }

    public function concentform(Request $request, $patient_id, $iConcernFormId, $PatientsConcernFormId)
    {
        // dd([$patient_id, $iConcernFormId, $PatientsConcernFormId]);
        $patientData = Patient::where('id', $patient_id)->first();
        $ConcernForm = ConcernForm::where('id', $iConcernFormId)->first();

        $patientNamePrefix = $patientData->name_prefix ?? "";
        $patientName = $patientData->name ?? "";
        $patientAddress = $patientData->address ?? "";
        $patientEmail = $patientData->email ?? "";
        $dateOfBirth = $patientData->dob ?? "";

        // ðŸ§® Age Calculation using Carbon
        $from = new \DateTime($dateOfBirth);
        $to   = new \DateTime('today');
        $ageYear =  $from->diff($to)->y;
        $ageMonth =  $from->diff($to)->m;
        $today = date('d-m-Y');
        $time = date('H:i');

        $age = $ageYear . " years";
        if ($ageYear == 0) {

            $age = $ageMonth . " months";
        }


        $patient[] = [
            "name_prefix" => $patientNamePrefix,
            "name" => $patientName,
            "address" => $patientAddress,
            "email" => $patientEmail,
            "date_of_birth" => $dateOfBirth,
            "age" => $age,
            "today" => $today,
            "time" => $time,
            "patient_id" => $patient_id
        ];

        $patient = compact('patient');

        // Make sure $PatientsConcernFormId exists or is handled properly
        return view('patient_concerform.Concentform', compact('patient', 'ConcernForm', 'PatientsConcernFormId'));
    }

    public function upload(Request $request)
    {
        $patient_id = $request->patient_id;
        $iConcernFormId = $request->iConcernFormId;
        $patientData = Patient::where(['id' => $patient_id])->first();
        $ConcernForm = ConcernForm::where(['id' => $iConcernFormId])->first();

        $case_no = $patientData->case_no ?? "";
        $patientName = $patientData->name ?? "";
        $patientNamePrefix = $patientData->name_prefix ?? "";
        $patientAddress = $patientData->address ?? "";
        $patientEmail = $patientData->email ?? "";
        $dateOfBirth = $patientData->dob ?? "";
        $mobile_no = $patientData->mobile_no ?? "";

        $from = new \DateTime($dateOfBirth);
        $to   = new \DateTime('today');
        $ageYear =  $from->diff($to)->y;
        $ageMonth =  $from->diff($to)->m;
        $today = date('m-d-Y');
        $time = date('H:i');

        $age = $ageYear . " years";
        if ($ageYear == 0) {
            $age = $ageMonth . " months";
        }

        $patient[] = array(
            "name_prefix" => $patientNamePrefix,
            "name" => $patientName,
            "address" => $patientAddress,
            "email" => $patientEmail,
            "date_of_birth" => $dateOfBirth,
            "age" => $age,
            "today" => $today,
            "time" => $time,
            "patient_id" => $patient_id
        );
        $patient = compact('patient');

        set_time_limit(300);
        $root = $_SERVER['DOCUMENT_ROOT'];
        if ($_SERVER['SERVER_NAME'] === "127.0.0.1") {
            $folderPath = $root . '/signature/';
        } else {
            $folderPath = $root . '/dental_clinic/signature/';
        }

        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, $mode = 0777, true, true);
        }
        $image_parts = explode(";base64,", $request->signed);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . $patientName . '.' . $image_type;
        $imageName = $patientName . '.' . $image_type;

        file_put_contents($file, $image_base64);
        $fileName = $request->PatientsConcernFormId . "_" . $case_no . "_" . str_replace(' ', '_', $patientName);

        $arr = array(
            "strFileName" => $fileName . '.pdf',
            "submitedDateTime" => date('Y-m-d H:i:s'),
            "isSubmit" => 1,
        );
        PatientConcerform::where('patient_concern_form_id', $request->PatientsConcernFormId)->update($arr);

        $pdf = PDF::loadView('patient_concerform/Savedconcentform', ['patient' => $patient, 'fileName' => $imageName, 'ConcernForm' => $ConcernForm]);

        $content = $pdf->download()->getOriginalContent();

        if ($_SERVER['SERVER_NAME'] === "127.0.0.1") {
            $pdfDirectory = $root . '/signatureform/';
        } else {
            $pdfDirectory = $root . '/dental_clinic/signatureform/';
        }

        // Define the path where you want to save the PDF
        $pdfPath = $pdfDirectory . $fileName . '.pdf';

        // Ensure the directory exists
        if (!File::exists($pdfDirectory)) {
            File::makeDirectory($pdfDirectory, 0777, true);
        }

        // Save the PDF content to the specified path
        file_put_contents($pdfPath, $content);

        return redirect()->route('patientconcernform.index', $patient_id)->with('success', 'Signature uploaded successfully.');
    }
}
