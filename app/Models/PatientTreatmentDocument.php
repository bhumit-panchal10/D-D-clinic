<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientTreatmentDocument extends Model
{
    use HasFactory;

    public $table = 'patient_treatment_document';

    protected $fillable = [
        'id',
        'patient_id',
        'treatment_id',
        'patient_treatment_id',
        'document',
        'comment',
        'date',
        'created_at',
        'updated_at'
    ];

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
