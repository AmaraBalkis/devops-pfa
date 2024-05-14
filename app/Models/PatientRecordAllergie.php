<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientRecordAllergie extends Pivot
{
    use HasFactory;
    protected $fillable = [
        'patient_record_id',
        'allergie_id',
        
    ];
    protected $table = 'patient_record_allergies';
    public $incrementing = true;
   
}
