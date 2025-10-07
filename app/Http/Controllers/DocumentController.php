<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\PatientTreatment;
use App\Models\PatientTreatmentDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $patient_id = $request->patient_id;
        $treatment_id = $request->treatment_id;
        $patient_treatment_id = $request->patient_treatment_id;

        $patient = $patient_id ? Patient::findOrFail($patient_id) : null;
        $documents = Document::where('patient_id', $patient_id)->paginate(config('app.per_page'));
        $treatments = Treatment::all();
        $patientTreatments = PatientTreatment::all();

        return view('documents.index', compact('patient', 'documents', 'treatments', 'patientTreatments', 'treatment_id', 'patient_treatment_id'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'document' => [
                'required',
                'file',
                'mimes:jpg,png,pdf',
                'max:5120'
            ],
            'comment' => 'nullable|string',
            'treatment_id' => 'nullable|exists:treatments,id',
            'patient_treatment_id' => 'nullable|exists:patient_treatments,id',
        ], [
            'document.mimes' => 'Allowed extensions are only .jpg, .png, .pdf',
            'document.max' => 'The file size should not exceed 5MB.',
        ]);


        $img = "";
        if ($request->hasFile('document')) {
            $root = $_SERVER['DOCUMENT_ROOT'];
            $image = $request->file('document');
            $img =  $image->getClientOriginalName();
            $destinationpath = $root . '/dental_clinic/D&D_DENTAL_CLINIC/documents/';
            if (!file_exists($destinationpath)) {
                mkdir($destinationpath, 0755, true);
            }
            $image->move($destinationpath, $img);
        }


        Document::create([
            'patient_id' => $request->patient_id,
            'treatment_id' => $request->filled('treatment_id') ? $request->treatment_id : null, // Store NULL instead of 0
            'patient_treatment_id' => $request->filled('patient_treatment_id') ? $request->patient_treatment_id : null, // Store NULL instead of 0
            'document' => $img, // Store only the filename in the database
            'comment' => $request->comment,
            'date' => $request->date,
        ]);

        return redirect()->route('document.index', $request->patient_id)->with('success', 'Document added successfully.');
    }

    public function multipleDocstore(Request $request, $id)
    {
        $request->validate([
            'document.*' => [
                'required',
                'file',
                'mimes:jpg,png,pdf',
                'max:5120'
            ],
            'comment' => 'nullable|string',
            'treatment_id' => 'nullable|exists:treatments,id',
            'patient_treatment_id' => 'nullable|exists:patient_treatments,id',
        ], [
            'document.*.mimes' => 'Allowed extensions are only .jpg, .png, .pdf',
            'document.*.max' => 'Each file size should not exceed 5MB.',
        ]);
        //dd($request);

        $PatientTreatment = PatientTreatment::where('id', $request->patient_treatment_id)->first();
        $date = $PatientTreatment->created_at->format('Y/m/d'); // e.g. 2025/06/27
      
        if ($request->hasFile('document')) {
            $root = $_SERVER['DOCUMENT_ROOT'];
            $destinationpath = $root . '/dental_clinic/patient_treatments/' . $date . '/' . $PatientTreatment->id;
            if (!file_exists($destinationpath)) {
                mkdir($destinationpath, 0755, true);
            }

            foreach ($request->file('document') as $image) {
                $img = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($destinationpath, $img);



                PatientTreatmentDocument::create([
                    'patient_id' => $request->patient_id,
                    'treatment_id' => $request->filled('treatment_id') ? $request->treatment_id : null,
                    'patient_treatment_id' => $request->filled('patient_treatment_id') ? $request->patient_treatment_id : null,
                    'document' => $img,
                    'comment' => $request->comment,
                    'date' => $request->date,
                ]);
            }
        }
        return redirect()->back()->with('success', 'Document added successfully.');
    }


    public function multidocview(Request $request, $id)
    {

        $patienttreatmentdoc = PatientTreatmentDocument::with('patientTreatment')->where('patient_treatment_id', $id)->get();
       // dd($patienttreatmentdoc);
        return view('documents.document', compact('patienttreatmentdoc'));
    }

    public function destroy($patient_id, $id)
    {
        $document = Document::findOrFail($id);

        $root = $_SERVER['DOCUMENT_ROOT'];
        $destinationpath = $root . '/dental_clinic/D&D_DENTAL_CLINIC/documents/';
        if ($document->document) {
            unlink($destinationpath  . $document->document);
        }

        // Delete record from database
        $document->delete();

        return redirect()->route('document.index', $patient_id)->with('success', 'Document deleted successfully.');
    }
}
