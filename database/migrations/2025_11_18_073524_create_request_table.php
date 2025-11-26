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
            $table->unsignedBigInteger('dr_ID');
            $table->unsignedBigInteger('pr_ID');

            // Milk attributes
            $table->double('current_weight');
            $table->integer('recommended_volume');
            $table->date('feeding_start_date');
            $table->time('feeding_start_time');
            $table->integer('feeding_perday');
            $table->integer('feeding_interval');
            $table->string('status')->nullable();
            $table->timestamps();

            // Define Foreign Key Relationships
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
