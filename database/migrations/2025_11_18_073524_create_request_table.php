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
        Schema::create('request', function (Blueprint $table) {
            $table->id('request_ID'); // Primary Key

            // Foreign Keys
            $table->unsignedBigInteger('dr_ID'); // Doctor creating the request
            $table->unsignedBigInteger('pr_ID'); // Patient/Parent ID

            // Clinical Information
            $table->double('current_weight');       // form: weight
            $table->integer('total_daily_volume');  // form: entered_volume
            
            // Age Information
            $table->integer('baby_age');            // form: baby_age
            $table->string('age_unit');             // form: age_unit (days/months) - NEW
            $table->integer('gestational_age')->nullable(); // form: gestational_age (optional in HTML?)

            // Dispensing Method
            $table->string('kinship_method');       // form: kinship_method (yes/no)
            $table->string('feeding_tube')->nullable(); // form: feeding_tube (might be empty if oral used)
            $table->string('oral_feeding')->nullable(); // form: oral_feeding (might be empty if tube used)

            // Schedule
            $table->date('feeding_start_date')->nullable(); // form: feeding_date
            $table->time('feeding_start_time');             // form: start_time
            $table->integer('feeding_perday')->default(12); // form: feeds_per_day
            $table->integer('feeding_interval')->default(2); // form: interval_hours

            // Status
            $table->string('status')->default('Pending');   // Default status
            $table->timestamps();

            // Relationships
            $table->foreign('dr_ID')->references('dr_ID')->on('doctor')->onDelete('cascade');
            $table->foreign('pr_ID')->references('pr_ID')->on('parent')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request');
    }
};
