<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'invoice_no', 'amount', 'discount', 'net_amount', 'date'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }


    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getPaidAmountAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function getDueAmountAttribute()
    {
        return $this->amount - $this->paid_amount;
    }
}
