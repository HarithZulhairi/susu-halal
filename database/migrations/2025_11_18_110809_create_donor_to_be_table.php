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
        Schema::create('donor_to_be', function (Blueprint $table) {
            $table->id('dtb_id');
            
            // Foreign keys
            $table->unsignedBigInteger('dn_ID'); // Reference to donor table
            $table->unsignedBigInteger('ns_ID')->nullable(); // Nurse who screened (optional)
            $table->unsignedBigInteger('dr_ID')->nullable(); // Nurse who screened (optional)
            
            // Health Screening Fields
            $table->enum('dtb_ScreeningStatus', ['pending', 'passed', 'failed'])->default('pending');
            $table->text('dtb_ScreeningRemark')->nullable();
            $table->timestamp('dtb_ScreenedAt')->nullable();
            $table->text('dtb_ScreeningNotes')->nullable(); // Internal notes
            
            // Health & Lifestyle (from registration - could be duplicated or reference donor table)
            $table->text('dtb_InfectionDiseaseRisk')->nullable();
            $table->text('dtb_Medication')->nullable();
            $table->text('dtb_RecentIllness')->nullable();
            $table->boolean('dtb_TobaccoAlcohol')->default(false);
            $table->text('dtb_DietaryAlerts')->nullable();
            
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('dn_ID')->references('dn_ID')->on('donor')->onDelete('cascade');
            $table->foreign('ns_ID')->references('ns_ID')->on('nurse')->onDelete('set null');
            $table->foreign('dr_ID')->references('dr_ID')->on('doctor')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donor_to_be');
    }
};