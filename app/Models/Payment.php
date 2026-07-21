<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'order_id', 'discount', 'payment_date', 'amount', 'mode', 'comments', 'type', 'is_completed'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function notes()
    {
        return $this->hasMany(Notes::class, 'patient_id', 'patient_id');
    }
}
