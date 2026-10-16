<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = ['dosage_id', 'medicine_name', 'comment', 'days', 'content', 'dosage_comment'];

    public function dosages()
    {
        return $this->hasMany(Dosage::class);
    }
}
