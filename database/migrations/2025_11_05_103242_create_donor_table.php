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
        Schema::create('donor', function (Blueprint $table) {
            $table->id('dn_ID')->primary();
            $table->string('dn_NRIC')->unique();
            $table->string('dn_FullName');
            $table->date('dn_DOB');
            $table->string('dn_Contact');
            $table->string('dn_Email')->unique();
            $table->string('dn_Address');
            $table->string('dn_InfectionDeseaseRisk')->nullable();
            $table->string('dn_Medication')->nullable();
            $table->string('dn_RecentIllness')->nullable();
            $table->boolean('dn_TobaccoAlcohol')->default(false);
            $table->string('dn_DietaryAlerts')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donor');
    }
};
