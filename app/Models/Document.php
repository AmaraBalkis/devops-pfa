<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $fillable = [
        'label',
        'path',
        'document_type_id',
        'patient_record_id',
        'consultation_id',
        'created_by',
        
    ];

    public function documentTypes()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }
    public function prescriptionLines()
    {
        return $this->hasMany(PrescriptionLine::class);
    }
    public function patientRecords()
    {
        return $this->belongsTo(PatientRecord::class);
    }
    public function consultations()
    {
        return $this->belongsTo(Consultation::class);
    }
    public function sharedDocument()
    {
        return $this->hasMany(SharedDocuments::class);
    }
   
}
