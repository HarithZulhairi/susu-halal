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
        Schema::create('shariahcomittee', function (Blueprint $table) {
            $table->id('sc_ID')->primary();
            $table->string('sc_NRIC')->unique();
            $table->string('sc_Name');
            $table->string('sc_Address');
            $table->string('sc_Contact');
            $table->string('sc_Email')->unique();
            $table->string('sc_Qualification');
            $table->string('sc_Certification');
            $table->string('sc_Institution');
            $table->string('sc_Specialization');
            $table->integer('sc_YearsOfExperience');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shariahcomittee');
    }
};
