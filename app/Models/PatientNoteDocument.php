<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientNoteDocument extends Model
{
    use HasFactory;
    protected $table = 'PatientNoteDocument';

    protected $fillable = [
        'id',
        'document',
        'treatment_id',
        'patient_note_id',
        'patient_treatment_id',
        'created_at',
        'updated_at'
    ];

    public function patientTreatment()
    {
        return $this->belongsTo(PatientTreatment::class, 'patient_treatment_id', 'id');
    }
    public function patientNote()
    {
        return $this->belongsTo(PatientNote::class, 'patient_note_id', 'id');
    }

}
