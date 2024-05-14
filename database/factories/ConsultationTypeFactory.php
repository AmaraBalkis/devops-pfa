<?php
 
namespace Database\Factories;
 
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ConsultationType;
 
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConsultationType>
 */
class ConsultationTypeFactory extends Factory
{
    protected $model = ConsultationType::class;
 
   
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'label' => $this->faker->word, // Génère un libellé aléatoire a partir de la biblio word PHP
        ];
    }
}