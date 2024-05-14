<?php
 
namespace Database\Factories;
 
use App\Models\PatientRecord;
use Illuminate\Database\Eloquent\Factories\Factory;
 
class PatientRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PatientRecord::class;
 
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'created_by' => null, // N7ot NULL 5ater les tables hedhom teb3in microservice o5ra ma3andich menhom
            'patient_id' => null, // Remplacez par la logique appropriée
            'doctor_id' => null, // Remplacez par la logique appropriée
            'numero' => $this->faker->randomNumber(),
            'description' => $this->faker->sentence(),
            // Ajoutez d'autres champs et leurs valeurs par défaut si nécessaire
        ];
    }
}