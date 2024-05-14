<?php
 
namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class PatientRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'numero',
        'description',
        'created_by',
        'patient_id',
        'doctor_id',
    ];
 
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
    public function consultations()
{
    return $this->hasMany(Consultation::class, 'patient_record_id');
}
 
    public function allergies(): BelongsToMany
    {
        return $this->belongsToMany(Allergie::class)->using(PatientRecordAllergie::class);;
    }
 
 
}