<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mobile1',
        'mobile2',
        'dob',
        'address',
        'pincode',
        'reference_by',
        'case_no',
        'gender',

        'medical_history',
        'medications',
        'previous_surgery',
        'allergy',
        'habit',
        'referred_by',
        'reminder',
        'email',
        'other_disease_comments',
        'referred_name'
    ];
}
