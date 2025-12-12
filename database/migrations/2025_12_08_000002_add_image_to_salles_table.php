<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour ajouter le champ image aux salles.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salles', function (Blueprint $table) {
            $table->string('image')->nullable()->after('description')
                ->comment('Chemin vers l\'image de la salle');
        });
    }

    public function down(): void
    {
        Schema::table('salles', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};












