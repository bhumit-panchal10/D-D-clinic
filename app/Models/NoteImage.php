<?php

namespace App\Models;

use App\Models\Notes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'note_id',
        'filename',
        'file_path',
    ];

    public function note()
    {
        return $this->belongsTo(Notes::class, 'note_id');
    }
}
