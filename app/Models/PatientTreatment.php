<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientTreatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'diagnosis_id',
        'tooth_selection',
        'is_billed',
        'quotation_give',
        'rate',
        'qty',
        'comment',
        'amount',
        'treatment_flag',
        'patient_treatment_id',
        'date'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function Diagnosis()
    {
        return $this->belongsTo(Diagnosis::class);
    }

    // PatientTreatment.php
    // PatientTreatment.php
    public function items()
    {
        return $this->hasMany(\App\Models\PatientTreatmentItem::class, 'diagnosis_id', 'id')
            ->with('treatment');
    }




    public function patientdocument()
    {
        return $this->belongsTo(PatientTreatmentDocument::class, 'id', 'patient_treatment_id');
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Calculate `qty` based on comma-separated `tooth_selection`
            $model->qty = $model->tooth_selection ? count(explode(',', $model->tooth_selection)) : 0;

            // Calculate `amount`
            $model->amount = $model->rate * $model->qty;
        });
    }
}
