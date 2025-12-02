<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reunions', function (Blueprint $table) {
            // S'assurer que 'description' existe bien (optionnel mais prudent)
            if (!Schema::hasColumn('reunions', 'description')) {
                $table->text('description')->nullable()->after('status');
            }

            // Ajouter la colonne 'agenda' si elle n'existe pas
            if (!Schema::hasColumn('reunions', 'agenda')) {
                $table->text('agenda')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reunions', function (Blueprint $table) {
            if (Schema::hasColumn('reunions', 'agenda')) {
                $table->dropColumn('agenda');
            }

            // Ne pas forcément supprimer 'description' si elle existait déjà avant,
            // donc on ne la touche pas dans le down().
        });
    }
};
