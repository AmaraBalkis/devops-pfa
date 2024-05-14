<?php

namespace Database\Factories;
use App\Models\PrescriptionLine;
use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PrescriptionLine>
 */
class PrescriptionLineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = Faker::create();

        return [
            'document_id' => Document::factory()->create()->id, // Use factory for Document
            'medcine_name' => fake()->name(),
            'dosage' => $faker->sentence(2),
            'times' => rand(1, 10), 
        ];
}
}
