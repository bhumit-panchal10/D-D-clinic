<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    public $table = 'quotation';
    protected $fillable = ['patient_id', 'quotation_no', 'amount', 'discount', 'net_amount', 'date', 'clinic_id'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function qutationDetails()
    {
        return $this->hasMany(QuotationDetail::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getDueAmountAttribute()
    {
        return $this->amount - $this->paid_amount;
    }
}
