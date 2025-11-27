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
        Schema::create('milk', function (Blueprint $table) {
            $table->id('milk_ID'); // Primary Key

            // Foreign Keys
            $table->unsignedBigInteger('dn_ID');
            $table->unsignedBigInteger('pr_ID')->nullable();

            // Milk attributes
            $table->integer('milk_volume');
            $table->date('milk_expiryDate')->nullable();
            $table->boolean('milk_shariahApproval')->nullable();
            $table->string('milk_shariahRemarks')->nullable();
            $table->date('milk_shariahApprovalDate')->nullable();
            $table->string('milk_Status')->nullable();
            $table->date('milk_stage1StartDate')->nullable();
            $table->date('milk_stage1EndDate')->nullable();
            $table->time('milk_stage1StartTime')->nullable();
            $table->time('milk_stage1EndTime')->nullable();
            $table->string('milk_stage1Result')->nullable();
            $table->date('milk_stage2StartDate')->nullable();
            $table->date('milk_stage2EndDate')->nullable();
            $table->time('milk_stage2StartTime')->nullable();
            $table->time('milk_stage2EndTime')->nullable();
            $table->date('milk_stage3StartDate')->nullable();
            $table->date('milk_stage3EndDate')->nullable();
            $table->time('milk_stage3StartTime')->nullable();
            $table->time('milk_stage3EndTime')->nullable();
            $table->timestamps();

            // Define Foreign Key Relationships
            $table->foreign('dn_ID')->references('dn_ID')->on('donor')->onDelete('cascade');
            $table->foreign('pr_ID')->references('pr_ID')->on('parent')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milk');
    }
};
