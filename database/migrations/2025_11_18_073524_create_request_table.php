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
            $table->id('request_ID');

            // Foreign Keys
            $table->unsignedBigInteger('dr_ID');
            $table->unsignedBigInteger('pr_ID');

            // Clinical Information
            $table->double('current_weight');
            $table->integer('total_daily_volume');
            
            // Age Information (Renamed)
            $table->string('current_baby_age');
            $table->integer('gestational_age')->nullable();

            // Dispensing Method
            $table->string('kinship_method');

            // Calculated Volume Fields
            $table->decimal('volume_per_feed', 8, 2)->nullable();
            $table->decimal('drip_total', 8, 2)->nullable();
            $table->decimal('oral_total', 8, 2)->nullable();
            $table->decimal('oral_per_feed', 8, 2)->nullable();

            // Methods
            $table->string('feeding_tube')->nullable();
            $table->string('oral_feeding')->nullable();

            // Schedule
            $table->date('feeding_start_date')->nullable();
            $table->time('feeding_start_time');
            $table->integer('feeding_perday')->default(12);
            $table->integer('feeding_interval')->default(2);

            $table->string('status')->default('Pending');
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
