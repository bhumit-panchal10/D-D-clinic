<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosage extends Model
{
    use HasFactory;

    protected $fillable = ['dosage'];


    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
