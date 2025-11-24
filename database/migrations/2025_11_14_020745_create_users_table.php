<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('password'); // Not used for actual auth
            $table->string('role'); // hmmc_admin, doctor, nurse, etc.
            $table->unsignedBigInteger('role_id'); // ID from role-specific table
            $table->string('username')->nullable()->unique();
            $table->string('ic_number')->nullable()->unique();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};