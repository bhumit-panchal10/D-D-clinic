<?php

namespace App\Models;

use App\Models\NoteImage;
use App\Models\Patient;
use App\Models\SubTreatment;
use App\Models\Treatment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'date', 'treatment_id', 'sub_treatment_id', 'comments', 'amount', 'tooth_no', 'next_appointment_date', 'discount', 'created_at', 'updated_at', 'Net_amount', 'type'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function images()
    {
        return $this->hasMany(NoteImage::class, 'note_id');
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    public function subTreatment()
    {
        return $this->belongsTo(SubTreatment::class, 'sub_treatment_id', 'sub_treatment_id');
    }
}
