<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Patient;
use App\Models\PatientTreatmentItem;
use App\Models\Treatment;
use App\Models\Notes;

use Illuminate\Http\Request;
use PDF;

class NoteController extends Controller
{
    public function index($patient_id)
    {
        $Totalamount = Notes::where(['patient_id' => $patient_id])->sum('amount');
        $Paidamount = Payment::where('patient_id', $patient_id)->sum('amount');
        $patient = Patient::findOrFail($patient_id);
        $Treatment = Treatment::get();
        $notes = Notes::with('treatment')->where('patient_id', $patient_id)->orderBy('id', 'desc')->paginate(config('app.per_page'));
        // dd($notes);
        return view('notes.index', compact('Treatment', 'patient', 'notes', 'Totalamount', 'Paidamount', 'patient_id'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'treatment' => 'required',
            'comments' => 'nullable|string',
            'tooth_no' => 'nullable'
        ]);

        Notes::create([
            'patient_id' => $request->patient_id,
            'date' => $request->date,
            'amount' => $request->amount,
            'treatment_id' => $request->treatment,
            'comments' => $request->comments,
            'tooth_no' => $request->tooth_no,
        ]);

        return redirect()->back()->with('success', 'Notes added successfully.');
    }

    public function edit($id)
    {

        // $patient = Patient::findOrFail($Notes->patient_id);
        $Notes = Notes::findOrFail($id);
        return response()->json($Notes);
    }

    public function update(Request $request)
    {

        $request->validate([
            'date' => 'required|date',
            'treatment' => 'nullable',
            'comments' => 'nullable|string',
            'amount' => 'required|numeric|min:1',
            'tooth_no' => 'nullable',
        ]);
        $data = [
            'date' => $request->date,
            'amount' => $request->amount,
            'treatment_id' => $request->treatment,
            'comments' => $request->comments,
            'tooth_no' => $request->tooth_no,
            'updated_at' => now(),

        ];

        Notes::where("id", $request->id)->update($data);

        return redirect()->route('notes.index', $request->patient_id)->with('success', 'Notes updated successfully.');
    }

    public function destroy($id)
    {
        $Notes = Notes::findOrFail($id);
        $Notes->delete();
        return redirect()->back()->with('success', 'Notes deleted successfully.');
    }
    public function generateInvoice($id)
    {
        $payments = Payment::with('patient')->where('id', $id)->first();

        $pdf = PDF::loadView('payments.invoice', compact('payments'));

        return $pdf->stream("invoice_{$payments->id}.pdf"); // Opens in new tab
    }
}
