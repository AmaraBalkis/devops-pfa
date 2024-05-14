<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class WaitingRoom extends Model
{
    use HasFactory;
    protected $fillable = [
        'arrival_date',
        'departure_date',
        'patient_id',
        'doctor_id'
    ];
 
}