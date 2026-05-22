<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Patient;
use App\Models\Notes;
use App\Models\PatientTreatmentItem;
use Illuminate\Http\Request;
use PDF;

class PaymentsReceivedController extends Controller
{
    public function index($patient_id = null)
    {
        $Totalamount = Notes::where(['type' => 'out'])->sum('Net_amount');
        $Paidamount = Payment::where('type', 'out')->sum('amount');
        $discount = Payment::where('type', 'out')->sum('discount');

        $patient = Patient::all();
        $payments = Payment::where('type', 'out')->paginate(config('app.per_page'));
        return view('payments_Received.index', compact('patient', 'payments', 'discount', 'Totalamount', 'Paidamount', 'patient_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'mode' => 'required|in:Cash,UPI,Card swipe,Cheque',
            'comments' => 'nullable|string'
        ]);

        Payment::create([
            'patient_id' => $request->patient_id,
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'discount' => $request->discount,
            'mode' => $request->mode,
            'comments' => $request->comments,
            'type' => 'out'
        ]);

        return redirect()->back()->with('success', 'Payment Received added successfully.');
    }

    public function edit(Payment $payment, $patient_id = null)
    {
        $patient = Patient::findOrFail($payment->patient_id);
        $payment = Payment::findOrFail($payment->id);
        return json_encode($payment);
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
            'patient_id' => $request->patient_id,
            'amount' => $request->amount,
            'mode' => $request->mode,
            'discount' => $request->discount,
            'comments' => $request->comments,
            'updated_at' => now(),

        ];

        Payment::where("id", $request->id)->update($data);

        return redirect()->route('paymentsreceived.index', $request->patient_id)->with('success', 'Payment updated successfully.');
    }
    public function generateInvoice($id)
    {
        $payments = Payment::with('patient')->where('id', $id)->first();

        $pdf = PDF::loadView('payments.invoice', compact('payments'));

        return $pdf->stream("invoice_{$payments->id}.pdf"); // Opens in new tab
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return redirect()->back()->with('success', 'Payment deleted successfully.');
    }
}
