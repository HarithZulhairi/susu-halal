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
        $table->id('allocation_ID');

        // Relationships
        $table->unsignedBigInteger('request_ID');
        $table->unsignedBigInteger('postBottle_ID');

        // Allocation details
        $table->integer('allocated_volume'); // bottle volume
        $table->string('storage_location');

        // Tracking
        $table->timestamp('allocated_at')->nullable();
        $table->timestamp('dispensed_at')->nullable();
        $table->unsignedBigInteger('dispensed_by')->nullable();

        $table->timestamps();

        // Foreign keys
        $table->foreign('request_ID')
            ->references('request_ID')
            ->on('request')
            ->onDelete('cascade');

        $table->foreign('postBottle_ID')
            ->references('id')
            ->on('post_bottles')
            ->onDelete('restrict');

        $table->foreign('dispensed_by')
            ->references('id')
            ->on('users')
            ->nullOnDelete();
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
