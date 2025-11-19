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
        Schema::create('nurse', function (Blueprint $table) {
            $table->id('ns_ID')->primary();
            $table->string('ns_NRIC')->unique();
            $table->string('ns_Name');
            $table->string('ns_Username')->unique();
            $table->string('ns_Password');
            $table->string('ns_Address');
            $table->string('ns_Contact');
            $table->string('ns_Email')->unique();
            $table->string('ns_Qualification');
            $table->string('ns_Certification'); // typo as in diagram, or correct to Certification
            $table->string('ns_Institution');
            $table->string('ns_Specialization');
            $table->integer('ns_YearsOfExperience');
            $table->foreignId('user_id')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nurse');
    }
};
