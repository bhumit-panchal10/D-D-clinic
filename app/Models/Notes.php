<?php

namespace App\Models;

use App\Models\NoteImage;
use App\Models\Patient;
use App\Models\Treatment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'date', 'treatment_id', 'comments', 'amount', 'tooth_no', 'discount', 'created_at', 'updated_at', 'Net_amount', 'type'];

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
}
