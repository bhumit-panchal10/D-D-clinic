<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubTreatment extends Model
{
    use HasFactory;
    public $table = 'sub_treatment';

    protected $fillable = ['sub_treatment_id', 'treatment_id', 'name', 'created_at', 'updated_at'];
    public function treatment()
    {
        return $this->belongsTo(Treatment::class, 'treatment_id', 'id');
    }
}
