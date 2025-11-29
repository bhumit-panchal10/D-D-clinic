<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReasonForVisitToday extends Model
{
    use HasFactory;
    protected $table = 'ReasonForVisitToday';

    protected $fillable = ['id', 'patient_id', 'comment', 'facial_asymmetry', 'TMJ', 'Lymphadenopathy', 'date'];
}
