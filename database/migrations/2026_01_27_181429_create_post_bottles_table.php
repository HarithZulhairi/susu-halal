<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_bottles', function (Blueprint $table) {
            $table->id('post_ID'); 
            
            // Foreign Keys
            $table->unsignedBigInteger('milk_ID'); 
            $table->unsignedBigInteger('pr_ID')->nullable(); 
            
            // Stage 3, 4 & 5 Attributes (Prefixed with 'post_')
            $table->string('post_bottle_code'); // e.g., "M1-P1"
            $table->float('post_volume')->default(30); // Fixed 30ml
            
            // Pasteurization Details
            $table->date('post_pasteurization_date')->nullable();
            $table->date('post_expiry_date')->nullable();
            
            // Microbiology Details
            $table->integer('post_micro_total_viable')->nullable();
            $table->integer('post_micro_entero')->nullable();
            $table->integer('post_micro_staph')->nullable();
            $table->string('post_micro_status')->nullable(); // 'Passed', 'Failed', 'Pending'
            
            // Storage Details
            $table->string('post_storage_location')->nullable(); 
            
            $table->timestamps();

            // Constraints
            $table->foreign('milk_ID')->references('milk_ID')->on('milk')->onDelete('cascade');
            $table->foreign('pr_ID')->references('pr_ID')->on('parent')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_bottles');
    }
};