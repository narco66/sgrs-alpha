<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participants_reunions', function (Blueprint $table) {
            if (!Schema::hasColumn('participants_reunions', 'participant_id')) {
                $table->foreignId('participant_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('participants')
                    ->cascadeOnUpdate()
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('participants_reunions', function (Blueprint $table) {
            if (Schema::hasColumn('participants_reunions', 'participant_id')) {
                $table->dropForeign(['participant_id']);
                $table->dropColumn('participant_id');
            }
        });
    }
};
