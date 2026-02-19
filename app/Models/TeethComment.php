<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeethComment extends Model
{
    use HasFactory;
    public $table = 'teeth_comments';

    protected $fillable = ['type_id', 'comment', 'tooth_no', 'intraoralexamination_id', 'patient_id', 'created_at', 'updated_at'];

    public function examination()
    {
        return $this->belongsTo(IntraoralExamination::class, 'intraoralexamination_id');
    }
}
