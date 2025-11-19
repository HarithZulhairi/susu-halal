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
            $table->string('milk_screeningStatus')->nullable();
            $table->string('milk_screeningResult')->nullable();
            $table->string('milk_homogenizeStatus')->nullable();
            $table->string('milk_homogenizeResult')->nullable();
            $table->string('milk_pasteurizeStatus')->nullable();
            $table->string('milk_pasteurizeResult')->nullable();
            $table->string('milk_labellingStatus')->nullable();
            $table->string('milk_collectingStatus')->nullable();
            $table->string('milk_storagingStatus')->nullable();
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
