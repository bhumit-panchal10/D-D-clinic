<?php

namespace App\Http\Controllers;

use PDF; // Import PDF at the top
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\QuotationDetail;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\PatientTreatment;
use App\Models\PatientTreatmentItem;

class QuatationController extends Controller
{
    public function index(Request $request, $patient_id)
    {
        $qutations = Quotation::where(['patient_id' => $patient_id])->paginate(config('app.per_page'));
        $patient = Patient::findOrFail($patient_id);

        $lastQuotation = Quotation::orderBy('id', 'desc')->first();
        $lastNumber = 0;

        if ($lastQuotation && preg_match('/quota_(\d+)/', $lastQuotation->quotation_no, $matches)) {
            $lastNumber = (int)$matches[1];
        }

        $quotation_no = 'quota_' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $patientTreatments = PatientTreatmentItem::query()
            ->from('PatientTreatmentItem as pti')
            ->select([
                'pti.id as item_id',
                'pti.patient_id',
                'pti.treatment_id',
                'pti.is_billed',
                't.treatment_name',
                'pti.notes',
                'pti.treatment_qty',
                'pti.treatment_rate',
                'pti.treatment_amount',
                'pt.id as patient_treatment_id',
                'pt.diagnosis_id',
                'pt.tooth_selection',
                'pt.comment',
                'pt.treatment_flag',
            ])
            ->join('patient_treatments as pt', 'pti.patient_treatment_id', '=', 'pt.id')
            ->join('treatments as t', 'pti.treatment_id', '=', 't.id')
            ->where('pt.treatment_flag', 1)
            ->get();

        $prefillIds = collect($request->input('items', []))->map(fn($v) => (int)$v)->filter();
        $prefillRows = collect();
        if ($prefillIds->isNotEmpty()) {
            $prefillRows = PatientTreatmentItem::with(['treatment', 'Diagnosis'])
                ->whereIn('id', $prefillIds)
                ->get()
                ->map(function ($pti) {
                    return [
                        'item_id'          => $pti->id,
                        'treatment_name'   => $pti->treatment->treatment_name ?? '',
                        'tooth_selection'  => optional($pti->Diagnosis)->tooth_selection ?? '',
                        'treatment_rate'   => $pti->treatment_rate ?? 0,
                        'treatment_qty'    => $pti->treatment_qty ?? 0,
                        'treatment_amount' => $pti->treatment_amount ?? 0,
                    ];
                });
        }
        return view('quotation.index', compact('qutations', 'patient', 'quotation_no', 'patientTreatments', 'prefillRows'));
    }

    // SHOW CREATE FORM
    public function create($patient_id)
    {
        $patient = Patient::findOrFail($patient_id);
        $lastQuotation = Quotation::orderBy('id', 'desc')->first();
        $lastNumber = 0;

        if ($lastQuotation && preg_match('/quota_(\d+)/', $lastQuotation->quotation_no, $matches)) {
            $lastNumber = (int)$matches[1];
        }

        $quotation_no = 'quota_' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        // $patientTreatments = PatientTreatment::with('Diagnosis')->where('patient_id', $patient_id)
        //     ->where('is_billed', 0) // Show only unbilled treatments
        //     ->get();


        // $patientTreatments = PatientTreatmentItem::with('treatment')
        //     ->where('treatment_done', 1)
        //     ->get();

        $patientTreatments = PatientTreatmentItem::query()
            ->from('PatientTreatmentItem as pti')
            ->select([
                'pti.id as item_id',
                'pti.patient_id',
                'pti.treatment_id',
                'pti.is_billed',
                't.treatment_name',   // 👈 treatment name
                'pti.notes',
                'pti.treatment_qty',
                'pti.treatment_rate',
                'pti.treatment_amount',
                'pt.id as patient_treatment_id',
                'pt.diagnosis_id',
                'pt.tooth_selection',
                'pt.comment',
                'pt.treatment_flag',
            ])
            ->join('patient_treatments as pt', 'pti.patient_treatment_id', '=', 'pt.id')
            ->join('treatments as t', 'pti.treatment_id', '=', 't.id') // 👈 join treatments
            ->where('pt.treatment_flag', 1)
            ->get();
        //   dd($patientTreatments);

        return view('quotation.create', compact('patient', 'quotation_no', 'patientTreatments'));
    }

    // STORE ORDER & ORDER DETAILS
    public function store(Request $request)
    {
        // Validate request

        $request->validate([
            'patient_id' => 'required',
            'quotation_no' => 'required|unique:quotation,quotation_no',
            'date'       => 'required|date',
            'patient_treatment_id' => 'required|array',
            'qty'        => 'required|array',
            'rate'       => 'required|array',
            'amount'     => 'required|array',

        ]);
      
        // Calculate Order Total
            $total_amount = array_sum(array_map(function($value) {
              return floatval(str_replace(',', '', $value));
           }, $request->amount));
        // $total_discount = array_sum($request->discount);
        // $total_net_amount = array_sum($request->net_amount);
       
        // Create Order Entry
        $quotation = Quotation::create([
            'patient_id' => $request->patient_id,
            'quotation_no' => $request->quotation_no,
            'date'       => $request->date,
            'amount'     => $total_amount,
            'discount'   => 0,
            'net_amount' => 0
        ]);


        // Store OrderDetails
        foreach ($request->patient_treatment_id as $key => $pt_id) {
            
            $patientTreatment = PatientTreatmentItem::findOrFail($pt_id);

            QuotationDetail::create([
                'quotation_id'           => $quotation->id ?? 0,
                'patient_id'         => $request->patient_id,
                'treatment_id'       => $patientTreatment->treatment_id,
                'patient_treatment_id' => $patientTreatment->id,
                'qty'                => $request->qty[$key],
                'rate'               => $request->rate[$key],
                'amount'             => $request->amount[$key],
                'discount'           => 0,
                'net_amount'         => 0,
            ]);

            // Update `is_billed = 1` for each patient_treatment_id
            $patientTreatment->update(['is_billed' => 1]);
        }

        return redirect()->route('quotation.index', $request->patient_id)->with('success', 'Quotation created successfully.');
    }


    public function destroy($id)
    {

        $quotation = Quotation::findOrFail($id);

        // Get all related OrderDetails
        $QuotationDetails = QuotationDetail::where('quotation_id', $id)->get();

        // Restore patient treatments (set is_billed = 0)
        foreach ($QuotationDetails as $detail) {
            PatientTreatment::where('id', $detail->patient_treatment_id)->update(['is_billed' => 0]);
        }

        // Delete order details first (to avoid foreign key issues)
        QuotationDetail::where('quotation_id', $id)->delete();

        // Delete the order
        $quotation->delete();

        return redirect()->route('quotation.index', $quotation->patient_id)->with('success', 'Quotation deleted successfully, and treatments are available again.');
    }


    public function generateInvoice($id)
    {
        $qutation = Quotation::with('qutationDetails.patientTreatment.treatment')->findOrFail($id);
        $pdf = PDF::loadView('quotation.invoice', compact('qutation'));

        return $pdf->stream("invoice_{$qutation->quotation_no}.pdf"); // Opens in new tab
    }
}
