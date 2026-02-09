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
        Schema::create('feed_records', function (Blueprint $table) {
            $table->id('feed_id');

            // Link to the specific bottle (Allocation)
            $table->unsignedBigInteger('allocation_ID');
            
            // Who performed this specific feed? (The nurse ticking the box)
            $table->unsignedBigInteger('ns_ID');

            // How much was fed? (e.g., 7.50 for oral, or the full bottle amount for tube)
            $table->decimal('fed_volume', 8, 2);

            // When did it happen? (The timestamp of the tick)
            $table->timestamp('fed_at');

            // Relationships
            $table->foreign('allocation_ID')->references('allocation_ID')->on('allocation')->onDelete('cascade');
            $table->foreign('ns_ID')->references('ns_ID')->on('nurse')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_records');
    }
};
