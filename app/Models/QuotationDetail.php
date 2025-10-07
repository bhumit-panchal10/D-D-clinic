<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationDetail extends Model
{
    use HasFactory;

    public $table = 'quotation_details';
    protected $fillable = ['quotation_id', 'patient_id', 'treatment_id', 'patient_treatment_id', 'qty', 'rate', 'amount', 'discount', 'net_amount', 'clinic_id'];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    public function patientTreatment()
    {
        return $this->belongsTo(PatientTreatmentItem::class);
    }
}
