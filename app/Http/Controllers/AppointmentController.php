<?php

namespace App\Http\Controllers;

use App\Models\PatientAppointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Carbon\Carbon;


class AppointmentController extends Controller
{
    // List Appointments
    public function index($id = null)
    {
        $patient = Patient::findOrFail($id); // Fetch patient details
        $appointments = PatientAppointment::with(['patient', 'doctor'])
            ->where('patient_id', $id)
            ->orderBy('created_at', 'desc') // Show latest added first
            ->paginate(config('app.per_page'));

        return view('patient_appointment.index', compact('appointments', 'id', 'patient'));
    }

    // Create Form
    public function create()
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        $Treatments = Treatment::all();

        return view('appointment.create', compact('patients', 'doctors', 'Treatments'));
    }



    public function getAppointments(Request $request)
    {
        $doctorId = $request->doctor_id;

        $query = PatientAppointment::with('patient', 'doctor');

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        // Filter: Today to +30 days
        $query->whereBetween('appointment_date', [
            now()->toDateString(),
            now()->addDays(30)->toDateString()
        ]);

        $appointments = $query->get();


        return response()->json($appointments->map(function ($appointment) {
            $timeString = $appointment->appointment_time; // "9:00 AM"
            try {
                $time = Carbon::createFromFormat('g:i A', $timeString);
            } catch (\Exception $e) {
                $time = null;
            }

            $formattedTime = $time ? $time->format('h:i A') : $timeString;
            //dd($doctorColors[$appointment->doctor_id]);
            return [
                'title' => $appointment->patient->name . ' with Dr. ' . $appointment->doctor->doctor_name . ' at ' . $formattedTime,
                'start' => Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time)->format('Y-m-d\TH:i:s'),
                'color' => $appointment->doctor->color ?? '#6c757d', // default gray

            ];
        }));
    }


    public function search(Request $request)
    {
        $term = $request->input('term');

        $patients = Patient::where('name', 'like', '%' . $term . '%')
            ->orWhere('mobile1', 'like', '%' . $term . '%')
            ->limit(10)
            ->get();

        $results = $patients->map(function ($patient) {
            return [
                'id' => $patient->id,
                'label' => $patient->name,
                'value' => $patient->name
            ];
        });
        return response()->json($results);
    }


    // Store
    // Store
    public function appointmentsstore(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
        ]);

        $data = array(
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'treatment_id' => $request->treatment_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'mobile_no' => $request->contact_no ?? 0,
            'email' => $request->email,
            'duration' => $request->duration,
        );

        PatientAppointment::create($data);

        return redirect()->back();
    }

    // Today's Appointments
    public function todayAppointments()
    {
        $appointments = PatientAppointment::with(['patient', 'doctor'])
            ->where('is_disrupted', 0) // Ignore disrupted appointments
            ->where(function ($query) {
                $query->whereNull('rescheduled_date')
                    ->where('appointment_date', today())
                    ->orWhere('rescheduled_date', today());
            })
            ->orderBy('created_at', 'desc')
            ->paginate(config('app.per_page'));

        return view('patient_appointment.today', compact('appointments'));
    }


    // Reschedule Appointment
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

        return redirect()->route('patient_appointment.today')
            ->with('success', 'Appointment rescheduled successfully.');
    }


    public function confirm(PatientAppointment $appointment)
    {
        if ($appointment->status == 1) {
            return redirect()->route('patient_appointment.today')->with('error', 'Appointment is already confirmed.');
        }
        $appointment->update(['status' => 1]);
        return redirect()->route('patient_appointment.today')->with('success', 'Appointment confirmed successfully.');
    }

    // public function getAppointments(Request $request)
    // {
    //     $doctorId = $request->doctor_id;

    //     $query = PatientAppointment::query()
    //         ->where('is_disrupted', 0)
    //         ->with('patient:id,name');

    //     if ($doctorId) {
    //         $query->where('doctor_id', $doctorId);
    //     }

    //     $appointments = $query->get(['appointment_date', 'appointment_time', 'patient_id']);

    //     return response()->json($appointments->map(function ($appointment) {
    //         return [
    //             'title' => 'Appointment with ' . ($appointment->patient->name ?? 'Unknown') . ' at ' . date('h:i A', strtotime($appointment->appointment_time)),
    //             'start' => $appointment->appointment_date . 'T' . $appointment->appointment_time,
    //         ];
    //     }));
    // }
}
