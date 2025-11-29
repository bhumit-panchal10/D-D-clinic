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
        'created_at'
    ];
}
