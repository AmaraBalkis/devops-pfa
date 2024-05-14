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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_type_id')->nullable()->constrained('consultation_types', 'id')->onDelete('set null')->onUpdate('cascade');
            $table->string('illness')->nullable();
            $table->float('weight')->nullable();
            $table->float('height')->nullable();
            $table->float('pressure')->nullable();
            $table->text('observations')->nullable();
            $table->text('diagnostic')->nullable();
            $table->text('description')->nullable();
            $table->integer('visit_price')->default(20);
            $table->boolean('payment_status')->default(false);
            $table->date('payment_date')->nullable();
            $table->time('started_at')->nullable();
            $table->time('ended_at')->nullable();
            $table->timestamps();
            // Foreign key for consultation_type relationship
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};