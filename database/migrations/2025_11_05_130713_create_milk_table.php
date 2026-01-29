<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('milk', function (Blueprint $table) {
            $table->id('milk_ID'); 

            // Foreign Keys
            $table->unsignedBigInteger('dn_ID');

            // Batch Attributes
            $table->integer('milk_volume'); // Total Raw Volume from Donor
            $table->string('milk_Status')->nullable(); // e.g. "Screening", "Pasteurization"
            $table->date('milk_expiryDate')->nullable();

            // Shariah Info (Applies to the whole batch)
            $table->boolean('milk_shariahApproval')->nullable();
            $table->string('milk_shariahRemarks')->nullable();
            $table->date('milk_shariahApprovalDate')->nullable();

            // Stage Dates (Keep these to track the Batch timeline)
            $table->date('milk_stage1StartDate')->nullable(); // Labelling Start
            $table->date('milk_stage1EndDate')->nullable();
            $table->time('milk_stage1StartTime')->nullable();
            $table->time('milk_stage1EndTime')->nullable();
            
            $table->date('milk_stage2StartDate')->nullable(); // Thawing Start
            $table->date('milk_stage2EndDate')->nullable();
            
            $table->date('milk_stage3StartDate')->nullable(); // Pasteurization Start
            $table->date('milk_stage3EndDate')->nullable();
            
            $table->date('milk_stage4StartDate')->nullable(); // Micro Start
            $table->date('milk_stage4EndDate')->nullable();
            
            $table->date('milk_stage5StartDate')->nullable(); // Storage Start
            $table->date('milk_stage5EndDate')->nullable();

            $table->timestamps();

            // Constraints
            $table->foreign('dn_ID')->references('dn_ID')->on('donor')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('milk');
    }
};