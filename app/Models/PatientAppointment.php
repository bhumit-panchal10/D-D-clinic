<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientAppointment extends Model
{
    use HasFactory;

    public $table = 'patient_appointments';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'mobile_no',
        'duration',
        'treatment_id',
        'email',
        'appointment_date',
        'appointment_time',
        'rescheduled_date',
        'rescheduled_time',
        'status',
        'is_disrupted',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
