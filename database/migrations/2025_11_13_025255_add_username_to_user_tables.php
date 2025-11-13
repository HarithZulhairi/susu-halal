<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nurse
        Schema::table('nurse', function (Blueprint $table) {
            $table->string('ns_Username')->unique()->after('ns_Email');
        });

        // Clinician
        Schema::table('clinician', function (Blueprint $table) {
            $table->string('cn_Username')->unique()->after('cn_Email');
        });

        // Donor
        Schema::table('donor', function (Blueprint $table) {
            $table->string('dn_Username')->unique()->after('dn_Email');
        });

        // HMMC Admin
        Schema::table('hmmcadmin', function (Blueprint $table) {
            $table->string('ad_Username')->unique()->after('ad_Email');
        });

        // Parent
        Schema::table('parent', function (Blueprint $table) {
            $table->string('pr_Username')->unique()->after('pr_Email');
        });

        // Shariah Committee
        Schema::table('shariahcomittee', function (Blueprint $table) {
            $table->string('sc_Username')->unique()->after('sc_Email');
        });

        // LabTech
        Schema::table('labtech', function (Blueprint $table) {
            $table->string('lt_Username')->unique()->after('lt_Email');
        });
    }

    public function down(): void
    {
        Schema::table('nurse', function (Blueprint $table) {
            $table->dropColumn('ns_Username');
        });
        Schema::table('clinician', function (Blueprint $table) {
            $table->dropColumn('cn_Username');
        });
        Schema::table('donor', function (Blueprint $table) {
            $table->dropColumn('dn_Username');
        });
        Schema::table('hmmcadmin', function (Blueprint $table) {
            $table->dropColumn('ad_Username');
        });
        Schema::table('parent', function (Blueprint $table) {
            $table->dropColumn('pr_Username');
        });
        Schema::table('shariahcomittee', function (Blueprint $table) {
            $table->dropColumn('sc_Username');
        });
        Schema::table('labtech', function (Blueprint $table) {
            $table->dropColumn('it_Username');
        });
    }
};
