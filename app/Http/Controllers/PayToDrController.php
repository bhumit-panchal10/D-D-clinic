<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\PayToDr;
use Illuminate\Validation\Rule;

class PayToDrController extends Controller
{

    public function index(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        $doctors = Doctor::get();
        $datas =  PayToDr::select(
            'pay_to_drs.*',
            'doctors.doctor_name'
        )
            ->leftjoin('doctors', 'doctors.id', '=', 'pay_to_drs.doctor_id')
            //->where(['pay_to_drs.clinic_id' => auth()->user()->clinic_id])
            ->latest()
            ->paginate(config('app.per_page'));
        // dd($datas);

        return view('pay_to_dr.index', compact('datas', 'patient', 'doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([

            'doctor_id' => 'required|exists:doctors,id',
            'amount' => 'required',
            'mode' => 'required',
        ]);

        PayToDr::create([
            //'clinic_id' => auth()->user()->clinic_id,
            'doctor_id' => $request->doctor_id,
            'patient_id' => $request->patient_id,
            'amount' => $request->amount,
            'mode' => $request->mode,
            'created_at' => now(),
            'strIP' => $request->ip()
        ]);

        return back()->with('success', 'Pay To Dr added successfully.');
    }

    public function edit($id)
    {
        $data = PayToDr::findOrFail($id);

        echo json_encode($data);
    }

    public function update(Request $request)
    {
        $request->validate([

            'doctor_id' => 'required|exists:doctors,id',
            'amount' => 'required',
            'mode' => 'required',
        ]);

        $data = PayToDr::findOrFail($request->id);
        $data->update([
            'doctor_id' => $request->doctor_id,
            'amount' => $request->amount,
            'mode' => $request->mode,
        ]);

        return back()->with('success', 'Pay To Dr updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $data = PayToDr::findOrFail($request->id);
        $data->delete();

        return back()->with('success', 'Pay To Dr deleted successfully.');
    }
}
