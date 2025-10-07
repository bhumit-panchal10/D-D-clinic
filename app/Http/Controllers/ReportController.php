<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Lab;
use App\Models\Labwork;
use App\Models\Payment;
use App\Models\Patient;
use App\Models\Order;
use App\Models\PayToDr;
use Illuminate\Http\Request;


class ReportController extends Controller
{

    public function report(Request $request)
    {
        // Get Date Range (Default: Current Month)
        $fromDate = $request->input('from_date', now()->startOfMonth()->toDateString());
        $toDate = $request->input('to_date', now()->endOfMonth()->toDateString());

        // Fetch Payments within the selected date range
        $payments = Payment::whereBetween('payment_date', [$fromDate, $toDate])
            ->orderBy('payment_date', 'desc')
            ->paginate(config('app.per_page'));

        // Get Total Amount
        $totalAmount = Payment::whereBetween('payment_date', [$fromDate, $toDate])->sum('amount');

        return view('reports.payments', compact('payments', 'fromDate', 'toDate', 'totalAmount'));
    }

    public function patient_report(Request $request)
    {
        // Get Date Range (Default: Current Month)
        $fromDate = $request->input('from_date', now()->startOfMonth()->toDateString());
        $toDate = $request->input('to_date', now()->endOfMonth()->toDateString());

        // Fetch Patients registered within the selected date range
        $patients = Patient::whereBetween('created_at', [$fromDate, $toDate])
            ->orderBy('created_at', 'desc')
            ->paginate(config('app.per_page'));

        return view('reports.patients', compact('patients', 'fromDate', 'toDate'));
    }


    public function duePaymentsReport(Request $request)
    {

        // Get Date Range (Default: Current Month)
        $fromDate = $request->input('from_date', now()->startOfMonth()->toDateString());
        $toDate = $request->input('to_date', now()->endOfMonth()->toDateString());

        // Fetch orders with payment details
        $orders = Order::with('patient')
            // ->where('due_amount', '>', '0')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->get()
            ->map(function ($order) {
                return [
                    'patient_name' => $order->patient->name,
                    'invoice_no' => $order->invoice_no,
                    'amount' => $order->amount,
                    'discount' => $order->discount,
                    'net_amount' => $order->net_amount,
                    'paid_amount' => $order->payments->sum('amount'), // Sum of all payments
                    'due_amount' => max($order->net_amount - $order->payments->sum('amount'), 0) // Calculate due amount
                ];
            })
            ->filter(function ($order) {
                return $order['due_amount'] > 0;
            })
            ->values();
        // dd($orders);

        // Calculate total amounts
        $totals = [
            'total_amount' => $orders->sum('amount'),
            'total_discount' => $orders->sum('discount'),
            'total_net' => $orders->sum('net_amount'),
            'total_paid' => $orders->sum('paid_amount'),
            'total_due' => $orders->sum('due_amount'),
        ];

        return view('reports.due_payments', compact('orders', 'fromDate', 'toDate', 'totals'));
    }
    
      public function pay_to_dr_Report(Request $request)
    {
        $doctors = Doctor::
            orderBy('doctor_name', 'asc')
            ->get(); // Show latest first

        $query = PayToDr::select(
            'pay_to_drs.*',
            'patients.name',
            'doctors.doctor_name'
        )
            //->where('pay_to_drs.clinic_id', auth()->user()->clinic_id)
            ->leftJoin('doctors', 'doctors.id', '=', 'pay_to_drs.doctor_id')
            ->leftJoin('patients', 'patients.id', '=', 'pay_to_drs.patient_id')
            ->orderBy('pay_to_drs.created_at', 'desc');

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $doctorId = $request->input('doctor_id');

        if ($fromDate) {
            $query->where('pay_to_drs.created_at', '>=', date('Y-m-d 00:00:00', strtotime($fromDate)));
        }

        if ($toDate) {
            $query->where('pay_to_drs.created_at', '<=', date('Y-m-d 23:59:59', strtotime($toDate)));
        }

        if ($doctorId) {
            $query->where('pay_to_drs.doctor_id', $doctorId);
        }

        // Load all if filters are applied, otherwise show recent 10
        if (!$fromDate && !$toDate && !$doctorId) {
            $query->take(10);
        }

        $datas = $query->get();

        // Get total amount only for filtered data
        $totalAmountQuery = PayToDr::query();

        if ($fromDate || $toDate || $doctorId) {
            $totalAmountQuery->when($request->from_date, fn($query, $FromDate) => $query
                ->where('pay_to_drs.created_at', '>=', date('Y-m-d 00:00:00', strtotime($FromDate))));
            $totalAmountQuery->when($request->to_date, fn($query, $ToDate) => $query
                ->where('pay_to_drs.created_at', '<=', date('Y-m-d 23:59:59', strtotime($ToDate))));
            $totalAmountQuery->when($request->doctor_id, fn($query, $doctorId) => $query
                ->where('pay_to_drs.doctor_id', '=', $doctorId));
        } else {
            $totalAmountQuery->orderBy('created_at', 'desc')->limit(10);
        }

        $totalAmount = $totalAmountQuery->sum('amount');
        

        return view('reports.pay_to_dr', compact('datas', 'fromDate', 'toDate', 'totalAmount', 'doctors', 'doctorId'));
    }

    public function lab_work_Report(Request $request)
    {
        $labs = Lab::get();
        $query = Labwork::select(
            'labworks.*',
            'labs.lab_name',
            'patients.name',
        )
            //->where('labworks.clinic_id', auth()->user()->clinic_id)
            ->leftJoin('labs', 'labs.id', '=', 'labworks.lab_id')
            ->leftJoin('patients', 'patients.id', '=', 'labworks.patient_id')
            ->orderBy('labworks.collection_date', 'desc');

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $labId = $request->input('lab_id');

        if ($fromDate) {
            $query->where('labworks.collection_date', '>=', date('Y-m-d 00:00:00', strtotime($fromDate)));
        }

        if ($toDate) {
            $query->where('labworks.collection_date', '<=', date('Y-m-d 23:59:59', strtotime($toDate)));
        }

        if ($labId) {
            $query->where('labworks.lab_id', $labId);
        }

        // Load all if filters are applied, otherwise show recent 10
        if (!$fromDate && !$toDate && !$labId) {
            $query->take(10);
        }

        $datas = $query->get();

        return view('reports.lab_work', compact('labs', 'datas', 'fromDate', 'toDate', 'labId'));
    }
}
