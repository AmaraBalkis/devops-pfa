<?php

namespace Database\Factories;
use App\Models\PatientRecordAllergie;
use App\Models\Allergie;
use App\Models\PatientRecord;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PatientRecordAllergie>
 */
class PatientRecordAllergieFactory extends Factory
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
            'patient_record_id' => PatientRecord::factory()->create()->id,
            'allergie_id' => Allergie::factory()->create()->id,
        ];
    }
}
