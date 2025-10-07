<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicCaseCounters extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'case_type',
        'last_number',
        'case_no',
        'prefix',
        'postfix',
        'iStatus',
        'iSDelete',
        'created_at',
        'updated_at',

    ];
    /*public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id', 'clinic_id');
    }*/
}
