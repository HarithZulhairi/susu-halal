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
        $tables = ['parent', 'nurse', 'doctor', 'labtech', 'hmmcadmin', 'shariahcomittee'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    if (!Schema::hasColumn($table->getTable(), 'first_login')) {
                        $table->boolean('first_login')->default(true);
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['parent', 'nurse', 'doctor', 'labtech', 'hmmcadmin', 'shariahcomittee'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                     if (Schema::hasColumn($table->getTable(), 'first_login')) {
                        $table->dropColumn('first_login');
                     }
                });
            }
        }
    }
};
