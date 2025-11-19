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
        Schema::create('allocation', function (Blueprint $table) {
            $table->id('allocation_ID'); // Primary Key

            // Foreign Keys
            $table->unsignedBigInteger('request_ID');
            $table->unsignedBigInteger('milk_ID');

            // Milk attributes
            $table->integer('total_selected_milk');
            $table->string('storage_location');
            $table->date('allocation_date')->nullable();
            $table->time('allocation_time')->nullable();
            $table->timestamps();

            // Define Foreign Key Relationships
            $table->foreign('request_ID')->references('request_ID')->on('request')->onDelete('cascade');
            $table->foreign('milk_ID')->references('milk_ID')->on('milk')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allocation');
    }
};
