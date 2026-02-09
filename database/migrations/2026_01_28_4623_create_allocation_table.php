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
            $table->unsignedBigInteger('post_ID');
            $table->unsignedBigInteger('ns_ID');

            // Milk attributes
            $table->integer('total_selected_milk');
            $table->string('storage_location')->nullable();
            $table->string('allocation_milk_date_time')->nullable();
            $table->string('feeding_method')->nullable();
            $table->boolean('is_consumed')->default(false);

            $table->timestamps();

            // Define Foreign Key Relationships
            $table->foreign('request_ID')->references('request_ID')->on('request')->onDelete('cascade');
            $table->foreign('post_ID')->references('post_ID')->on('post_bottles')->onDelete('cascade');
            $table->foreign('ns_ID')->references('ns_ID')->on('nurse')->onDelete('cascade');
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
