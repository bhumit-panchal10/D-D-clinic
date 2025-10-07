<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRegister extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'complain_details',
        'repair_person_name',
        'repair_given_date',
        'quotation_amount',
        'payment_paid_amount',
        'repair_received_date',
    ];
}
