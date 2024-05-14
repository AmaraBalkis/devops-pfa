<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharedDocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'privilege',
        'doctor_id',
        'document_id'
    ];
    public function documents()
    {
        return $this->belongsTo(Document::class);
    }
   
}
