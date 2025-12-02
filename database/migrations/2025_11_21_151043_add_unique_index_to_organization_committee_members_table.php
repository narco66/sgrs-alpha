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
        Schema::table('membres_comites_organisation', function (Blueprint $table) {
            $table->unique(['organization_committee_id', 'user_id'], 'org_committee_member_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('membres_comites_organisation', function (Blueprint $table) {
            $table->dropUnique('org_committee_member_unique');
        });
    }
};
