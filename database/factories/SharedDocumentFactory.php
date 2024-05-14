<?php

namespace Database\Factories;
use App\Models\SharedDocument;
use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SharedDocument>
 */
class SharedDocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model=SharedDocument::class;
    public function definition()
    {   
        $privileges = ['-','r','w','rw'];
        $privilege= $privileges[rand(0, 3)];
        return [
            'privilege' => $privilege,
            'doctor_id' => null,
            'document_id' => Document::factory()->create()->id, // Use factory for DocumentType,

        ];
    }
}
