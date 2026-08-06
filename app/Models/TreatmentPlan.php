<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentPlan extends Model
{
    use HasFactory;
    protected $table = 'Treatment_plan';

    protected $fillable = [
        'id',
        'Dentures',
        'implants',
        'other_treatment',

        'RCT_IPC',
        'Extraction',
        'Restoration',
        'Prosthesis',

        'Scaling',
        'polishing',
        'Grinding',
        'Bleaching',
        'smile_design',
        'orthodontics',
        'surgery',
        'biopsy',
        'Scaling_desc',
        'polishing_desc',
        'Grinding_desc',
        'Bleaching_desc',
        'smile_design_desc',
        'orthodontics_desc',
        'surgery_desc',
        'biopsy_desc',
        'created_at',
        'updated_at',
        'date',
        'patient_id',
        'scaling_completed',
        'polishing_completed',
        'grinding_completed',
        'bleaching_completed',
        'smile_design_completed',
        'orthodontics_completed',
        'surgery_completed',
        'biopsy_completed',
        'dentures_completed',
        'implants_completed',
        'other_treatment_completed',
    ];

    public function details()
    {
        return $this->hasMany(TreatmentPlanDetail::class, 'Treatment_plan_id');
    }
}
