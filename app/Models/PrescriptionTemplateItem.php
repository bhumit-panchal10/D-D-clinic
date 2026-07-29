<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionTemplateItem extends Model
{
    protected $fillable = [
        'prescription_template_id',
        'medicine_id',
        'dosage_id',
        'days',
        'medicine_qty',
        'comments',
        'sort_order',
    ];

    public function template()
    {
        return $this->belongsTo(PrescriptionTemplate::class);
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
