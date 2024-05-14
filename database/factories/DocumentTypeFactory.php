<?php

namespace Database\Factories;
use App\Models\DocumentType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DocumentType>
 */
class DocumentTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model=DocumentType::class;
    public function definition()
    {
        $labels = ['Certificate', 'Prescription','IRM','Radio','Analysis','Note'];
        $label= $labels[rand(0, 5)];
        return [
            'label' => $label,
        ];
    }
}
