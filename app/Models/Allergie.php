<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Allergie extends Model
{
    use HasFactory;
    protected $fillable = [
        'label',
        
    ];
    public function patientRecords(): BelongsToMany
    {
        return $this->belongsToMany(PatientRecord::class)->using(PatientRecordAllergie::class);
    }

}
    
