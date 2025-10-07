<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Patient;
use App\Models\PatientTreatmentItem;
use Illuminate\Http\Request;
use PDF;
class PaymentsController extends Controller
{
    public function index($patient_id)
    {
        $Totalamount = PatientTreatmentItem::where(['patient_id' => $patient_id, 'treatment_start' => 1])->sum('treatment_amount');
        $Paidamount = Payment::where('patient_id', $patient_id)->sum('amount');
        $patient = Patient::findOrFail($patient_id);
        $payments = Payment::where('patient_id', $patient_id)->paginate(config('app.per_page'));
        return view('payments.index', compact('patient', 'payments', 'Totalamount', 'Paidamount','patient_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'mode' => 'required|in:Cash,UPI,Card swipe,Cheque',
            'comments' => 'nullable|string'
        ]);
       
        Payment::create([
            'patient_id' => $request->patient_id,
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'mode' => $request->mode,
            'comments' => $request->comments,       
        ]);

        return redirect()->back()->with('success', 'Payment added successfully.');
    }

    public function edit(Payment $payment,$patient_id=null)
    {
        $patient = Patient::findOrFail($payment->patient_id);
        $payment = Payment::findOrFail($payment->id);
        return json_encode($payment);
       
    }
     public function generateInvoice($id)
    {
        $payments = Payment::with('patient')->where('id', $id)->first();

        $pdf = PDF::loadView('payments.invoice', compact('payments'));

        return $pdf->stream("invoice_{$payments->id}.pdf"); // Opens in new tab
    }           

    public function update(Request $request)
    {
        $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'mode' => 'required|in:Cash,UPI,Card swipe,Cheque',
            'comments' => 'nullable|string'
        ]);
          $data = [
                'payment_date' => $request->payment_date,
                'amount' => $request->amount,
                'mode' => $request->mode,
                'comments' => $request->comments,
                'updated_at' => now(),
                
            ];

            Payment::where("id", $request->id)->update($data);

        return redirect()->route('payments.index', $request->patient_id)->with('success', 'Payment updated successfully.');
    }

    public function destroy($id)
    {
         $payment = Payment::findOrFail($id);
         $payment->delete();
        return redirect()->back()->with('success', 'Payment deleted successfully.');
    }
}
