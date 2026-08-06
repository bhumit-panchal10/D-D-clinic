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


    public function appointmentsUpdate(Request $request)
    {

        $appointment = PatientAppointment::findOrFail($request->appointment_id);

        $appointment->update([
            'doctor_id'        => $request->doctor_id,
            'treatment_id'     => $request->treatment_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'duration'         => $request->duration,
            'mobile_no'        => $request->contact_no,
            'email'            => $request->email,
        ]);

        return back()->with('success', 'Appointment Rescheduled Successfully.');
    }

    public function getAppointments(Request $request)
    {
        $doctorId = $request->doctor_id;

        $query = PatientAppointment::with(['patient', 'doctor', 'treatment']);

        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }

        // Today se next 30 days appointments
        // $query->whereBetween('appointment_date', [
        //     now()->toDateString(),
        //     now()->copy()->addDays(30)->toDateString(),
        // ]);

        $appointments = $query->get();

        return response()->json(
            $appointments->map(function ($appointment) {

                $patient = $appointment->patient;
                $treatmentName = $appointment->treatment->treatment_name ?? null;

                $patientName = trim(
                    ($patient?->name ?? '') . ' ' .
                        ($patient?->middle_name ?? '') . ' ' .
                        ($patient?->last_name ?? '')
                );

                if ($patientName === '') {
                    $patientName = 'Unknown Patient';
                }

                $caseNo = $patient?->case_no ?? '';
                $doctorName = $appointment->doctor?->doctor_name ?? 'Unknown Doctor';

                /*
             * Duration database mein minutes mein honi chahiye.
             * Example:
             * 30  = 30 minutes
             * 60  = 1 hour
             * 120 = 2 hours
             */
                $durationMinutes = (int) preg_replace(
                    '/[^0-9]/',
                    '',
                    (string) $appointment->duration
                );

                // Duration empty/zero ho to default 30 minutes
                if ($durationMinutes <= 0) {
                    $durationMinutes = 30;
                }

                try {
                    $startDateTime = Carbon::parse(
                        $appointment->appointment_date . ' ' .
                            $appointment->appointment_time
                    );

                    $endDateTime = $startDateTime
                        ->copy()
                        ->addMinutes($durationMinutes);

                    $formattedTime = $startDateTime->format('h:i A');
                } catch (\Exception $e) {
                    return null;
                }

                return [
                    'id' => $appointment->id,

                    'title' => $patientName
                        . ($caseNo ? " ({$caseNo})" : '')
                        . ' with Dr. '
                        . $doctorName
                        . ' at '
                        . $formattedTime
                        . ' for ' . $treatmentName,

                    // Appointment starting time
                    'start' => $startDateTime->format('Y-m-d\TH:i:s'),

                    // Important: duration ke according ending time
                    'end' => $endDateTime->format('Y-m-d\TH:i:s'),

                    'color' => $appointment->doctor?->color ?? '#6c757d',

                    'patient_id'   => $appointment->patient_id,
                    'patient_name' => $patientName,
                    'case_no'      => $caseNo,
                    'doctor_id'    => $appointment->doctor_id,
                    'treatment_id' => $appointment->treatment_id,
                    'mobile_no'    => $patient->mobile1,
                    'email'        => $patient->email,
                    'duration'     => $durationMinutes,
                ];
            })->filter()->values()
        );
    }

    // public function getAppointments(Request $request)
    // {
    //     $doctorId = $request->doctor_id;

    //     $query = PatientAppointment::with('patient', 'doctor');

    //     if ($doctorId) {
    //         $query->where('doctor_id', $doctorId);
    //     }

    //     // Filter: Today to +30 days
    //     $query->whereBetween('appointment_date', [
    //         now()->toDateString(),
    //         now()->addDays(30)->toDateString()
    //     ]);

    //     $appointments = $query->get();

    //     return response()->json($appointments->map(function ($appointment) {

    //         $timeString = $appointment->appointment_time;

    //         try {
    //             $time = Carbon::createFromFormat('g:i A', $timeString);
    //         } catch (\Exception $e) {
    //             $time = null;
    //         }

    //         $formattedTime = $time ? $time->format('h:i A') : $timeString;
    //         $patient = $appointment->patient;

    //         $patientName = trim(
    //             ($patient?->name ?? '') . ' ' .
    //                 ($patient?->middle_name ?? '') . ' ' .
    //                 ($patient?->last_name ?? '')
    //         );

    //         if ($patientName === '') {
    //             $patientName = 'Unknown Patient';
    //         }
    //         $caseNo      = $appointment->patient?->case_no ?? '';
    //         $doctorName  = $appointment->doctor?->doctor_name ?? 'Unknown Doctor';
    //         return [
    //             'id' => $appointment->id,

    //             'title' => $patientName
    //                 . ($caseNo ? " ({$caseNo})" : "")
    //                 . ' with Dr. '
    //                 . $doctorName
    //                 . ' at '
    //                 . $formattedTime,

    //             'start' => Carbon::parse(
    //                 $appointment->appointment_date . ' ' . $appointment->appointment_time
    //             )->format('Y-m-d\TH:i:s'),

    //             'color' => $appointment->doctor?->color ?? '#6c757d',

    //             'patient_id'   => $appointment->patient_id,
    //             'patient_name' => $patientName,
    //             'case_no'      => $caseNo,
    //             'doctor_id'    => $appointment->doctor_id,
    //             'treatment_id' => $appointment->treatment_id,
    //             'mobile_no'    => $appointment->mobile1,
    //             'email'        => $appointment->email,
    //             'duration'     => $appointment->duration,
    //         ];
    //     }));
    // }


    // public function getAppointments(Request $request)
    // {
    //     $doctorId = $request->doctor_id;

    //     $query = PatientAppointment::with('patient', 'doctor');

    //     if ($doctorId) {
    //         $query->where('doctor_id', $doctorId);
    //     }

    //     // Filter: Today to +30 days
    //     $query->whereBetween('appointment_date', [
    //         now()->toDateString(),
    //         now()->addDays(30)->toDateString()
    //     ]);

    //     $appointments = $query->get();


    //     return response()->json($appointments->map(function ($appointment) {
    //         $timeString = $appointment->appointment_time; // "9:00 AM"
    //         try {
    //             $time = Carbon::createFromFormat('g:i A', $timeString);
    //         } catch (\Exception $e) {
    //             $time = null;
    //         }

    //         $formattedTime = $time ? $time->format('h:i A') : $timeString;
    //         //dd($doctorColors[$appointment->doctor_id]);

    //         return [
    //             // 'title' => $appointment->patient->name . ' with Dr. ' . $appointment->doctor->doctor_name . ' at ' . $formattedTime,
    //             'title' => ($appointment->patient?->name ?? 'Unknown Patient')
    //     . ' with Dr. '
    //     . ($appointment->doctor?->doctor_name ?? 'Unknown Doctor')
    //     . ' at '
    //     . $formattedTime,
    //             'start' => Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time)->format('Y-m-d\TH:i:s'),
    //             'color' => $appointment->doctor->color ?? '#6c757d', // default gray

    //         ];
    //     }));
    // }


    public function search(Request $request)
    {
        $term = $request->get('term');

        $patients = Patient::where(function ($q) use ($term) {
            $q->where('case_no', 'LIKE', "%{$term}%")
                ->orWhere('name', 'LIKE', "%{$term}%")
                ->orWhere('middle_name', 'LIKE', "%{$term}%")
                ->orWhere('last_name', 'LIKE', "%{$term}%");
        })
            ->limit(10)
            ->get();

        $result = [];
        foreach ($patients as $patient) {

            $fullName = trim(
                $patient->name . ' ' .
                    $patient->middle_name . ' ' .
                    $patient->last_name
            );

            $result[] = [
                'id'    => $patient->id,
                'label' => $patient->case_no . ' - ' . $fullName,
                'value' => $patient->case_no . ' - ' . $fullName,
            ];
        }


        return response()->json($result);
    }


    public function appointmentsDelete($id)
    {
        $appointment = PatientAppointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route('appointment.create');
    }    // Store
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
