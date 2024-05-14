<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class ConsultationType extends Model
{
    use HasFactory;
    protected $fillable = [
       'label'
    ];
 
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }
}
