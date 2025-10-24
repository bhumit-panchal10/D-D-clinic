<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientNote extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'notes', 'treatment_id', 'date','tooth_number','patient_treatment_id'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

     public function patientTreatment()
    {
        return $this->belongsTo(PatientTreatment::class);
    }
}
