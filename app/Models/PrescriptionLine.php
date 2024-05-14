<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionLine extends Model
{
    use HasFactory;
    protected $fillable = [
        'medcine_name',
        'dosage',
        'document_id',
        'times',
    ];

    public function documents()
    {
        return $this->belongsTo(Document::class);
    }
}
