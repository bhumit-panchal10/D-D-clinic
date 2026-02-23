<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntraoralExamination extends Model
{
    use HasFactory;
    protected $table = 'IntraoralExamination';

    protected $fillable = [
        'id',
        'caries',
        'pain_op',
        'missing',
        'mobility',
        'prosthesis',
        'impacted',
        'Pocket',
        'vitality',
        'Sensitivity',
        'plaque',
        'calculus',
        'stains',
        'BOP',
        'patient_id',
        'exam_date',
        'notes',
        'diagnosis',
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'caries'     => 'array',
        'pain_op'    => 'array',
        'missing'    => 'array',
        'mobility'   => 'array',
        'prosthesis' => 'array',
    ];

    public function teethComments()
    {
        return $this->hasMany(TeethComment::class, 'intraoralexamination_id');
    }
}
