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
        Schema::create('pumping_kit_appointments', function (Blueprint $table) {
            $table->id('pk_ID');

            //Foreign Key
            $table->unsignedBigInteger('dn_ID');

            //Table Attributes
            $table->string('reference_num')->nullable();
            $table->string('kit_type');
            $table->dateTime('appointment_datetime');
            $table->string('location');
            $table->text('reason')->nullable();
            $table->string('status')->default('pending'); // optional

            $table->timestamps();

            $table->foreign('dn_ID')->references('dn_ID')->on('donor')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pumping_kit_appointments');
    }
};
