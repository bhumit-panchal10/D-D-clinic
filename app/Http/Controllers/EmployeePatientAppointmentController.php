<?php

namespace App\Http\Controllers;

use App\Models\PatientAppointment;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;

class EmployeePatientAppointmentController extends Controller
{
    // List Appointments
    public function index(Request $request)
{
    $query = PatientAppointment::query();

    // Apply date filter if a date is selected
    if ($request->has('appointment_date') && !empty($request->appointment_date)) {
        $query->whereDate('appointment_date', $request->appointment_date);
    }

    $appointments = $query->paginate(config('app.per_page'));

    return view('employee.patient_appointment.index', compact('appointments'));
}



    // Today's Appointments
//     public function todayAppointments()
//     {
//         $appointments = PatientAppointment::with(['patient', 'doctor'])
//             ->where('is_disrupted', 0) // Ignore disrupted appointments
//             ->where(function ($query) {
//                 $query->whereNull('rescheduled_date')
//                     ->where('appointment_date', today())
//                     ->orWhere('rescheduled_date', today());
//             })
//             ->orderBy('created_at', 'desc')
//             ->get();

//         return view('patient_appointment.today', compact('appointments'));
//     }


    public function reschedule(Request $request, PatientAppointment $appointment)
    {
        $request->validate([
            'rescheduled_date' => 'required|date|after_or_equal:today',
            'rescheduled_time' => 'required',
        ]);

        // Prevent duplicate rescheduling with same date & time
        if (
            $appointment->appointment_date == $request->rescheduled_date &&
            $appointment->appointment_time == $request->rescheduled_time
        ) {
            return redirect()->back()->with('error', 'New appointment date & time must be different.');
        }

        // Mark old appointment as disrupted
        $appointment->update(['is_disrupted' => 1]);

        // Create a new entry with updated details
        PatientAppointment::create([
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $appointment->doctor_id,
            'appointment_date' => $request->rescheduled_date,
            'appointment_time' => $request->rescheduled_time,
            'status' => 0, // Mark as unconfirmed
            'is_disrupted' => 0 // Ensure new entry is valid
        ]);

        return redirect()->route('employee.patient_appointment.index')
            ->with('success', 'Appointment rescheduled successfully.');
    }


    public function confirm(PatientAppointment $appointment)
    {
        if ($appointment->status == 1) {
            return redirect()->route('patient_appointment.today')->with('error', 'Appointment is already confirmed.');
        }
        $appointment->update(['status' => 1]);
        return redirect()->route('employee.patient_appointment.index')->with('success', 'Appointment confirmed successfully.');
    }

//     public function getAppointments(Request $request)
// {
//     $doctorId = $request->doctor_id;

//     if (!$doctorId) {
//         return response()->json(['error' => 'Doctor ID is required'], 400);
//     }

//     $appointments = PatientAppointment::where('doctor_id', $doctorId)
//         ->where('is_disrupted', 0) // Exclude disrupted appointments
//         ->with('patient:id,name')
//         ->get(['appointment_date', 'appointment_time', 'patient_id']);

//     return response()->json($appointments->map(function ($appointment) {
//         return [
//             'title' => 'Appointment with ' . ($appointment->patient->name ?? 'Unknown') . ' at ' . date('h:i A', strtotime($appointment->appointment_time)),
//             'start' => $appointment->appointment_date . 'T' . $appointment->appointment_time,
//         ];
//     }));
// }

}
