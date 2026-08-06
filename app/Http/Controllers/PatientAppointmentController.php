<?php

namespace App\Http\Controllers;

use App\Models\PatientAppointment;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;

class PatientAppointmentController extends Controller
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
    public function create($id)
    {
        $patient = Patient::findOrFail($id);
        $doctors = Doctor::all();
        return view('patient_appointment.create', compact('patient', 'doctors', 'id'));
    }

    // Store
    // Store
    public function store(Request $request)
    {


        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'contact_no' => 'nullable',
            'email' => 'nullable',
        ]);

        // dd($request->all());

        $data = array(
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'mobile_no' => $request->contact_no ?? 0,
            'email' => $request->email,
        );
        // dd($data);
        // Create new appointment
        PatientAppointment::create($data);

        // dd($data);
        // Mark any existing future appointment for this patient as disrupted
        PatientAppointment::where('patient_id', $request->patient_id)
            ->where('doctor_id', $request->doctor_id)
            ->where('appointment_date', '>=', today())
            ->where('is_disrupted', 0) // Only affect active appointments
            ->update(['is_disrupted' => 1]);


        return redirect()->route('patient_appointment.index', $request->patient_id)
            ->with('success', 'Appointment added successfully.');
    }


    // Edit Form
    public function edit(PatientAppointment $appointment)
    {
        $patient = Patient::findOrFail($appointment->patient_id);
        $doctors = Doctor::all();
        return view('patient_appointment.edit', compact('appointment', 'patient', 'doctors'));
    }

    // Update
    public function update(Request $request, PatientAppointment $appointment)
    {
        $request->validate([
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
        ]);

        $appointment->update($request->all());

        return redirect()->route('patient_appointment.index', $request->patient_id)->with('success', 'Appointment updated successfully.');
    }

    // Delete
    public function destroy(PatientAppointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('patient_appointment.index', $appointment->patient_id)->with('success', 'Appointment deleted successfully.');
    }

    // Today's Appointments
    // public function todayAppointments()
    // {
    //     $appointments = PatientAppointment::with(['patient', 'doctor', 'treatment'])
    //         ->where('is_disrupted', 0) // Ignore disrupted appointments
    //         ->where(function ($query) {
    //             $query->whereNull('rescheduled_date')
    //                 ->where('appointment_date', today())
    //                 ->orWhere('rescheduled_date', today());
    //         })
    //         ->orderBy('appointment_time', 'asc')
    //         ->paginate(config('app.per_page'));
    //     //dd($appointments);

    //     return view('patient_appointment.today', compact('appointments'));
    // }

    public function todayAppointments(Request $request)
    {
        $appointments = PatientAppointment::with(['patient', 'doctor', 'treatment'])
            ->where('is_disrupted', 0);

        // Date Filter
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $appointments->where(function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->whereNull('rescheduled_date')
                        ->whereBetween('appointment_date', [
                            $request->from_date,
                            $request->to_date
                        ]);
                })
                    ->orWhereBetween('rescheduled_date', [
                        $request->from_date,
                        $request->to_date
                    ]);
            });
        } else {
            // Default: Today's Appointments
            $appointments->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereNull('rescheduled_date')
                        ->whereDate('appointment_date', today());
                })
                    ->orWhereDate('rescheduled_date', today());
            });
        }

        $appointments = $appointments
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->paginate(config('app.per_page'))
            ->appends($request->all());

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

    public function getAppointments(Request $request)
    {
        $doctorId = $request->doctor_id;

        $query = PatientAppointment::query()
            ->where('is_disrupted', 0)
            ->with('patient:id,name');

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        $appointments = $query->get(['appointment_date', 'appointment_time', 'patient_id']);

        return response()->json($appointments->map(function ($appointment) {
            return [
                'title' => 'Appointment with ' . ($appointment->patient->name ?? 'Unknown') . ' at ' . date('h:i A', strtotime($appointment->appointment_time)),
                'start' => $appointment->appointment_date . 'T' . $appointment->appointment_time,
            ];
        }));
    }
}
