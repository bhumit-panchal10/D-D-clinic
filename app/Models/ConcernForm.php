<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConcernForm extends Model
{
    use HasFactory;

    public $table = 'concern_form_master';

    protected $fillable = [
        'id',
        'title',
        'description',
        'iStatus',
        'isDelete',
        'created_at',
        'updated_at',
        'strIP'
    ];
}
