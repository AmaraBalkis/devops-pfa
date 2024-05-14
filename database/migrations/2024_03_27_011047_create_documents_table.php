<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_type_id')->nullable()->constrained('document_types', 'id')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('patient_record_id')->nullable()->constrained('patient_records', 'id')->onDelete('cascade')->onUpdate('cascade');//brch kinfsakh patien record el document zeda ytfaskh
            $table->foreignId('consultation_id')->nullable()->constrained('consultations', 'id')->onDelete('set null')->onUpdate('cascade');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('label')->nullable();
            $table->string('path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
