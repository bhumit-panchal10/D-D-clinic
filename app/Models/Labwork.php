<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Labwork extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'lab_id',
        'treatment_id',
        'patient_treatment_id',
        'entry_date',
        'collection_date',
        'received_date',
        'comment',
        'given_by',
        'work_code',
        'job_work_no',
        'received_by',
        'type'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function lab()
    {
        return $this->belongsTo(Lab::class);
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
