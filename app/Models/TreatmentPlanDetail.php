<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentPlanDetail extends Model
{
    use HasFactory;
    protected $table = 'Treatment_plan_detail';

    protected $fillable = [
        'id',
        'Treatment_plan_id',
        'patient_id',
        'tooth_no',
        'created_at',
        'updated_at'
    ];
}
