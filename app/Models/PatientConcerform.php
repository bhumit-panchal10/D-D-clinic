<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientConcerform extends Model
{
    use HasFactory;

    public $table = 'patient_concern_form';

    protected $fillable = ['patient_concern_form_id', 'strFileName', 'isSubmit', 'submitedDateTime', 'concern_form_id', 'patient_id', 'clinic_id', 'created_at', 'updated_at', 'iStatus', 'isDelete'];


    public function concerform()
    {
        return $this->belongsTo(ConcernForm::class, 'concern_form_id', 'id');
    }
}
