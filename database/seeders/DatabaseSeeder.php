<?php

namespace Database\Seeders;
use App\Models\User;
use App\Models\DocumentType;
use App\Models\Document;
use App\Models\SharedDocument;
use App\Models\PrescriptionLine;
use App\Models\Consultation;
use App\Models\ConsultationType;
use App\Models\PatientRecord;
use App\Models\WaitingRoom;
use App\Models\Allergie;
use App\Models\PatientRecordAllergie;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         User::factory(4)->create();
         DocumentType::factory(4)->create();
         ConsultationType::factory(4)->create();
         PatientRecord::factory(4)->create();
         Consultation::factory(4)->create();
         Document::factory(4)->create();
         SharedDocument::factory(4)->create();
         PrescriptionLine::factory(4)->create();
         WaitingRoom::factory(4)->create();
         Allergie::factory(4)->create();
         PatientRecordAllergie::factory(4)->create();
       /* User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/
    }
}
