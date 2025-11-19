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
            $table->string('dn_Username')->unique();
            $table->string('dn_Password');
            $table->boolean('first_login')->default(true);
            $table->date('dn_DOB');
            $table->string('dn_Contact');
            $table->string('dn_Email')->nullable();
            $table->string('dn_Address');
            $table->integer('dn_Parity')->default(0);
            $table->json('dn_DeliveryDetails')->nullable();
            $table->string('dn_InfectionDeseaseRisk')->nullable();
            $table->string('dn_Medication')->nullable();
            $table->string('dn_RecentIllness')->nullable();
            $table->boolean('dn_TobaccoAlcohol')->default(false);
            $table->string('dn_DietaryAlerts')->nullable();
            $table->json('dn_Availability')->nullable();
            $table->foreignId('user_id')->nullable();
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
