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
        Schema::create('parent', function (Blueprint $table) {
            $table->id('pr_ID')->primary();
            $table->string('pr_Name');
            $table->string('pr_Address');
            $table->string('pr_Contact');
            $table->string('pr_Email')->unique();
            $table->string('pr_BabyName');
            $table->date('pr_BabyDOB');
            $table->string('pr_BabyGender');
            $table->float('pr_BabyBirthWeight');
            $table->float('pr_BabyCurrentWeight');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent');
    }
};
