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
        Schema::create('milk_appointments', function (Blueprint $table) {
            $table->id('ma_ID');
            $table->string('reference_num')->nullable();
            //Foreign Key
            $table->unsignedBigInteger('dn_id');

            //Table Attributes
            $table->integer('milk_amount');
            $table->string('delivery_method');
            $table->string('location')->nullable();
            $table->string('collection_address')->nullable();
            $table->dateTime('appointment_datetime');
            $table->text('remarks')->nullable();
            
            $table->string('status')->default('pending'); // optional

            $table->timestamps();

            $table->foreign('dn_ID')->references('dn_ID')->on('donor')->onDelete('cascade');

            // future relation
            // $table->foreign('donor_id')->references('id')->on('donors')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milk_appointments');
    }
};
