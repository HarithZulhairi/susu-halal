<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pre_bottles', function (Blueprint $table) {
            $table->id('id'); 
            
            // Foreign Keys
            $table->unsignedBigInteger('milk_ID');

            // Stage 1 & 2 Attributes (Prefixed with 'pre_')
            $table->string('pre_bottle_code'); // e.g., "M1-B1"
            $table->float('pre_volume');       // Variable volume
            $table->boolean('pre_is_thawed')->default(false);
            
            $table->timestamps();

            // Constraints
            $table->foreign('milk_ID')->references('milk_ID')->on('milk')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pre_bottles');
    }
};