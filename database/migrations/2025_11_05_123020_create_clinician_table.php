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
        Schema::create('clinician', function (Blueprint $table) {
            $table->id('cn_ID')->primary();
            $table->string('cn_NRIC')->unique();
            $table->string('cn_Name');
            $table->string('cn_Address');
            $table->string('cn_Contact');
            $table->string('cn_Email')->unique();
            $table->string('cn_Qualification');
            $table->string('cn_Cerification'); // typo in diagram, use as-is or correct to Certification
            $table->string('cn_Institution');
            $table->string('cn_Specialization');
            $table->integer('cn_YearsOfExperience');
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
