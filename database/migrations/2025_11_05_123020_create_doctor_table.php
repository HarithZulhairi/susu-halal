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
        Schema::create('doctor', function (Blueprint $table) {
            $table->id('dr_ID')->primary();
            $table->string('dr_NRIC')->unique();
            $table->string('dr_Name');
            $table->string('dr_Username')->unique();
            $table->string('dr_Password');
            $table->string('dr_Address');
            $table->string('dr_Contact');
            $table->string('dr_Email')->unique();
            $table->string('dr_Qualification');
            $table->string('dr_Certification'); // typo in diagram, use as-is or correct to Certification
            $table->string('dr_Institution');
            $table->string('dr_Specialization');
            $table->integer('dr_YearsOfExperience');
            $table->foreignId('user_id')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinician');
    }
};
