<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionDetail extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'prescription_id', 'medicine_id', 'dosage_id', 'comments', 'days', 'medicine_qty'];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function dosage()
    {
        return $this->belongsTo(Dosage::class);
    }
}
