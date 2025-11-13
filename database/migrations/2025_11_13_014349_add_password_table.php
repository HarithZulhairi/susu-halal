<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nurse', function (Blueprint $table) {
            $table->string('ns_Password')->after('ns_YearsOfExperience');
        });

        Schema::table('clinician', function (Blueprint $table) {
            $table->string('cn_Password')->after('cn_YearsOfExperience');
        });

        Schema::table('donor', function (Blueprint $table) {
            $table->string('dn_Password')->after('dn_DietaryAlerts');
        });

        Schema::table('hmmcadmin', function (Blueprint $table) {
            $table->string('ad_Password')->after('ad_Gender');
        });

        Schema::table('parent', function (Blueprint $table) {
            $table->string('pr_Password')->after('pr_BabyCurrentWeight');
        });

        Schema::table('shariahcomittee', function (Blueprint $table) {
            $table->string('sc_Password')->after('sc_YearsOfExperience');
        });
    }

    public function down(): void
    {
        Schema::table('nurse', function (Blueprint $table) {
            $table->dropColumn('ns_Password');
        });

        Schema::table('clinician', function (Blueprint $table) {
            $table->dropColumn('cn_Password');
        });

        Schema::table('donor', function (Blueprint $table) {
            $table->dropColumn('dn_Password');
        });

        Schema::table('hmmcadmin', function (Blueprint $table) {
            $table->dropColumn('ad_Password');
        });

        Schema::table('parent', function (Blueprint $table) {
            $table->dropColumn('pr_Password');
        });

        Schema::table('shariahcomittee', function (Blueprint $table) {
            $table->dropColumn('sc_Password');
        });
    }
};
