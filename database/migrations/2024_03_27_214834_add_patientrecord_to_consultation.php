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
        Schema::table('consultations', function (Blueprint $table) {
            //
            $table->foreignId('patient_record_id')->nullable()->constrained('patient_records', 'id')->onDelete('cascade')->onUpdate('cascade');
          //bech ki nafsa5 patienttRecord yetna7aw les consultations eli teb3inou lkol
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropForeign(['patient_records_id']);
            $table->dropColumn('patient_records_id');
        });
    }
};