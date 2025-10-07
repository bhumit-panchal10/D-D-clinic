<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Prescription;
use App\Models\PrescriptionDetail;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\Dosage;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of the prescriptions.
     */
    public function index($patient_id)
    {
        $patient = Patient::findOrFail($patient_id);
        $prescriptions = Prescription::where('patient_id', $patient_id)->latest()->paginate(config('app.per_page'));

        return view('prescriptions.index', compact('patient', 'prescriptions'));
    }

    /**
     * Show the form for creating a new prescription.
     */
    public function create($patient_id)
    {
        $patient = Patient::findOrFail($patient_id);
        $medicines = Medicine::all();
        $dosages = Dosage::all();

        return view('prescriptions.create', compact('patient', 'medicines', 'dosages'));
    }

    /**
     * Store a newly created prescription.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medicine_id' => 'required|array',
            'dosage_id' => 'required|array',
            'comments' => 'nullable|array', // Ensure comments is an array
        ]);

        // Create Prescription Entry
        $prescription = Prescription::create([
            'patient_id' => $request->patient_id,
            'date' => now(),
            'gu_id' => Str::uuid(), // Generate a unique UUID
        ]);

        // Store Prescription Details
        foreach ($request->medicine_id as $index => $medicine_id) {
            PrescriptionDetail::create([
                'prescription_id' => $prescription->id,
                'patient_id' => $request->patient_id,
                'medicine_id' => $medicine_id,
                'dosage_id' => $request->dosage_id[$index],
                'comments' => $request->comments[$index] ?? null,
                'days' => $request->days[$index] ?? null,
                'medicine_qty' => $request->qtys[$index] ?? null,


            ]);
        }

        return redirect()->route('prescriptions.index', $request->patient_id)->with('success', 'Prescription added successfully.');
    }


    public function edit($id)
    {
        $prescription = Prescription::findOrFail($id);
        $patient = $prescription->patient;
        $medicines = Medicine::all();
        $dosages = Dosage::all();
        $prescriptionDetails = PrescriptionDetail::where('prescription_id', $id)->get();

        return view('prescriptions.edit', compact('prescription', 'patient', 'medicines', 'dosages', 'prescriptionDetails'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'medicine_id' => 'required|array',
            'dosage_id' => 'required|array',
            'comments' => 'nullable|array',
            'detail_id' => 'nullable|array', // Track existing prescription details
            'delete_ids' => 'nullable|array', // Track items to be deleted
        ]);

        $prescription = Prescription::findOrFail($id);

        // Update Prescription Date
        $prescription->update([
            'date' => now(),
        ]);

        // Delete removed items
        if (!empty($request->delete_ids)) {
            PrescriptionDetail::whereIn('id', array_filter($request->delete_ids))->delete();
        }

        // Track updated IDs
        $updatedIds = [];

        foreach ($request->medicine_id as $index => $medicine_id) {
            $detailId = $request->detail_id[$index] ?? null;

            if ($detailId) {
                // Update existing line item
                PrescriptionDetail::where('id', $detailId)->update([
                    'medicine_id' => $medicine_id,
                    'dosage_id' => $request->dosage_id[$index],
                    'comments' => $request->comments[$index] ?? null,
                ]);
                $updatedIds[] = $detailId;
            } else {
                // Insert new line item
                $newDetail = PrescriptionDetail::create([
                    'prescription_id' => $prescription->id,
                    'patient_id' => $prescription->patient_id,
                    'medicine_id' => $medicine_id,
                    'dosage_id' => $request->dosage_id[$index],
                    'comments' => $request->comments[$index] ?? null,
                    'days' => $request->days[$index] ?? null,
                    'medicine_qty' => $request->qtys[$index] ?? null,
                ]);
                $updatedIds[] = $newDetail->id;
            }
        }



        return redirect()->route('prescriptions.index', $prescription->patient_id)
            ->with('success', 'Prescription updated successfully.');
    }


    public function downloadPDF($id)
    {
        $prescription = Prescription::with('patient', 'prescriptionDetails.medicine', 'prescriptionDetails.dosage')->findOrFail($id);

        // Load the PDF view
        $pdf = Pdf::loadView('prescriptions.pdf', compact('prescription'));

        // Open PDF in a new tab
        return $pdf->stream('Prescription_' . $prescription->id . '.pdf');
    }

    public function downloadPDFByGUID($gu_id)
    {
        // Find prescription by GUID instead of ID
        $prescription = Prescription::with('patient', 'prescriptionDetails.medicine', 'prescriptionDetails.dosage')
            ->where('gu_id', $gu_id)
            ->firstOrFail();

        // Load the PDF view
        $pdf = Pdf::loadView('prescriptions.pdf', compact('prescription'));

        // Stream the PDF
        return $pdf->stream('Prescription_' . $prescription->id . '.pdf');
    }


    /**
     * Remove the specified prescription.
     */
    public function destroy($id)
    {
        $prescription = Prescription::findOrFail($id);
        $patient_id = $prescription->patient_id;

        // Delete Prescription Details First
        $prescription->prescriptionDetails()->delete();
        $prescription->delete();

        return redirect()->route('prescriptions.index', $patient_id)->with('success', 'Prescription deleted successfully.');
    }

    public function get_dosages(Request $request, $id)
    {
        $medicine = Medicine::find($id);

        if (!$medicine) {
            return response()->json([]);
        }

        $selectedDosage = Dosage::find($medicine->dosage_id);

        $allDosages = Dosage::orderBy('dosage')->get(['id', 'dosage']); // get all

        return response()->json([
            'selected_dosage_id' => $selectedDosage->id ?? null,
            'comment' => $medicine->comment ?? '',
            'days' => $medicine->days ?? 1,
            'dosages' => $allDosages,
        ]);
    }
}
