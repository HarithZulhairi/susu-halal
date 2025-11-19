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
        Schema::create('labtech', function (Blueprint $table) {
            $table->id('lt_ID'); // primary key
            $table->string('lt_Name');
            $table->string('lt_Username')->unique();
            $table->string('lt_Password'); // for login credentials
            $table->string('lt_Email')->unique();
            $table->string('lt_Contact');
            $table->string('lt_NRIC')->unique();
            $table->string('lt_Address');
            $table->string('lt_Institution')->nullable();
            $table->string('lt_Qualification')->nullable();
            $table->string('lt_Certification')->nullable();
            $table->string('lt_Specialization')->nullable();
            $table->integer('lt_YearsOfExperience')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labtech');
    }
};
