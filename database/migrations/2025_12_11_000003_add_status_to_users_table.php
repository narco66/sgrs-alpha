<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->string('status', 50)
                ->default('pending')
                ->after('is_active');
        });

        // Mise à niveau des données existantes :
        // - comptes actifs => status = active
        // - comptes inactifs => status = inactive
        DB::table('utilisateurs')
            ->where('is_active', true)
            ->update(['status' => 'active']);

        DB::table('utilisateurs')
            ->where('is_active', false)
            ->update(['status' => 'inactive']);
    }

    public function down(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};


