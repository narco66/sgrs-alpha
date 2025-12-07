<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migration pour marquer les tables participants comme obsolètes
     * Note: On ne supprime pas les données pour permettre une migration progressive
     * Les relations sont conservées mais dépréciées dans le code
     */
    public function up(): void
    {
        // Ajouter une colonne pour marquer les enregistrements comme obsolètes
        if (Schema::hasTable('participants_reunions')) {
            Schema::table('participants_reunions', function (Blueprint $table) {
                if (!Schema::hasColumn('participants_reunions', 'is_deprecated')) {
                    $table->boolean('is_deprecated')->default(true)->after('checked_in_at');
                    $table->text('deprecation_note')->nullable()->after('is_deprecated');
                }
            });
        }

        // Ajouter un index pour faciliter les requêtes de migration
        if (Schema::hasTable('participants_reunions')) {
            Schema::table('participants_reunions', function (Blueprint $table) {
                $table->index(['meeting_id', 'is_deprecated']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('participants_reunions')) {
            Schema::table('participants_reunions', function (Blueprint $table) {
                if (Schema::hasColumn('participants_reunions', 'is_deprecated')) {
                    $table->dropIndex(['meeting_id', 'is_deprecated']);
                    $table->dropColumn(['is_deprecated', 'deprecation_note']);
                }
            });
        }
    }
};










