<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientTreatmentItem extends Model
{
    use HasFactory;
    public $table = 'PatientTreatmentItem';

    protected $fillable = [
        'id',
        'patient_id',
        'diagnosis_id',
        'treatment_id',
        'sub_treatment_id',
        'notes',
        'treatment_qty',
        'treatment_rate',
        'treatment_amount',
        'patient_treatment_id',
        'treatment_done',
        'is_billed',
        'treatment_date',
        'treatment_start',
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

    public function Subtreatment()
    {
        return $this->belongsTo(SubTreatment::class, 'sub_treatment_id', 'sub_treatment_id');
    }

    public function Diagnosis()
    {
        return $this->belongsTo(PatientTreatment::class, 'patient_treatment_id', 'id');
    }

    public function PatientTreatment()
    {
        return $this->belongsTo(PatientTreatment::class, 'patient_treatment_id', 'id');
    }

    public function DiagnosisMaster()
    {
        return $this->belongsTo(Diagnosis::class, 'diagnosis_id', 'id');
    }

    public function patientdocument()
    {
        return $this->belongsTo(PatientTreatmentDocument::class, 'id', 'patient_treatment_id');
    }
}
