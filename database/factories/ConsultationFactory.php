<?php
 
namespace Database\Factories;
 
use App\Models\Consultation;
use App\Models\ConsultationType; // Importez la classe ConsultationType
use App\Models\PatientRecord;
use Illuminate\Database\Eloquent\Factories\Factory;
 
class ConsultationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Consultation::class;
 
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'illness' => $this->faker->word,
            'weight' => $this->faker->numberBetween(50, 100),
            'height' => $this->faker->numberBetween(150, 200),
            'pressure' => $this->faker->numberBetween(80, 120),
            'observations' => $this->faker->paragraph(),
            'diagnostic' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'visit_price' => $this->faker->numberBetween(50, 200),
            'payment_status' => $this->faker->boolean(),
            'payment_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'consultation_type_id' => ConsultationType::factory()->create()->id,
            'patient_record_id' => PatientRecord::factory()->create()->id , // ou NULL si vous avez besoin d'un patient_record_id valide
            'started_at' => $this->faker->time(),
            'ended_at' => $this->faker->time(),
        ];
    }
}