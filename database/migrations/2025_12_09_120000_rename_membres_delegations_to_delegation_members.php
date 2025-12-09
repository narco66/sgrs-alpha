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
        // Renommer la table pour standardiser les noms
        if (Schema::hasTable('membres_delegations')) {
            Schema::rename('membres_delegations', 'delegation_members');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('delegation_members')) {
            Schema::rename('delegation_members', 'membres_delegations');
        }
    }
};