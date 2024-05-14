<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Consultation extends Model
{
    use HasFactory;
    protected $fillable = [
        'illness',
        'weight',
        'height',
        'pressure',
        'observations',
        'diagnostic',
        'description',
        'visit_price',
        'payment_status',
        'payment_date',
        'consultation_type_id',
        'patient_record_id',
        'stared_at',
        'ended_at'
    ];
 
    public function consultationTypes()
    {
        return $this->belongsTo(ConsultationType::class);
    }
    public function patientRecords()
    {
        return $this->belongsTo(PatientRecord::class);
    }
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}