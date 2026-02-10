<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('post_bottles', function (Blueprint $table) {
            $table->boolean('is_disposed')->default(false)->after('post_micro_status');
        });
    }

    public function down()
    {
        Schema::table('post_bottles', function (Blueprint $table) {
            $table->dropColumn('is_disposed');
        });
    }
};
