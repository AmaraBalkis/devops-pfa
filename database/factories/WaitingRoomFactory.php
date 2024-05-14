<?php
 
namespace Database\Factories;
 
use App\Models\WaitingRoom;
use Illuminate\Database\Eloquent\Factories\Factory;
 
class WaitingRoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WaitingRoom::class;
 
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'arrival_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'departure_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'patient_id' => $this->faker->numberBetween(1, 20), // Remplacez 20 par l'ID maximum de votre table patients
            'doctor_id' => $this->faker->numberBetween(1, 10), // Remplacez 10 par l'ID maximum de votre table doctors
        ];
    }
}