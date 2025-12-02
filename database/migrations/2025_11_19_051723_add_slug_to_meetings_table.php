<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajouter la colonne slug à la table reunions.
     */
    public function up(): void
    {
        Schema::table('reunions', function (Blueprint $table) {
            if (! Schema::hasColumn('reunions', 'slug')) {
                $table->string('slug')
                    ->unique()
                    ->after('title');
            }
        });
    }

    /**
     * Suppression de la colonne slug si rollback.
     */
    public function down(): void
    {
        Schema::table('reunions', function (Blueprint $table) {
            if (Schema::hasColumn('reunions', 'slug')) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            }
        });
    }
};
