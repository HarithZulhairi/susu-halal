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
        Schema::create('hmmcadmin', function (Blueprint $table) {
            $table->id('ad_Admin')->primary();
            $table->string('ad_NRIC')->unique();
            $table->string('ad_Name');
            $table->string('ad_Username')->unique();
            $table->string('ad_Password');
            $table->string('ad_Address');
            $table->string('ad_Contact');
            $table->string('ad_Email')->unique();
            $table->string('ad_Gender');
            $table->foreignId('user_id')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hmmcdmin');
    }
};
