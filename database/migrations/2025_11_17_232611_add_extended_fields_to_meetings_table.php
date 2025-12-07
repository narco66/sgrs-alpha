<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reunions', function (Blueprint $table) {
            // Date de fin de la réunion
            if (!Schema::hasColumn('reunions', 'end_at')) {
                $table->dateTime('end_at')->nullable()->after('start_at');
            }

            // Note: meeting_type_id, room_id, duration_minutes, status, reminder_minutes_before
            // et created_by existent déjà dans la migration create_meetings_table
            // On ne les ajoute pas ici pour éviter les conflits

            // Comité (peut ne pas exister si la migration add_meeting_type_and_committee n'a pas été exécutée)
            if (!Schema::hasColumn('reunions', 'committee_id')) {
                $table->foreignId('committee_id')
                    ->nullable()
                    ->after('meeting_type_id')
                    ->constrained('comites')
                    ->nullOnDelete();
            }

            // Organisateur (alias de created_by, peut être ajouté pour compatibilité)
            if (!Schema::hasColumn('reunions', 'organizer_id')) {
                $table->foreignId('organizer_id')
                    ->nullable()
                    ->after('status')
                    ->constrained('utilisateurs')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('reunions', function (Blueprint $table) {
            if (Schema::hasColumn('reunions', 'reminder_minutes_before')) {
                $table->dropColumn('reminder_minutes_before');
            }
            if (Schema::hasColumn('reunions', 'organizer_id')) {
                $table->dropConstrainedForeignId('organizer_id');
            }
            if (Schema::hasColumn('reunions', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('reunions', 'room_id')) {
                $table->dropConstrainedForeignId('room_id');
            }
            if (Schema::hasColumn('reunions', 'committee_id')) {
                $table->dropConstrainedForeignId('committee_id');
            }
            if (Schema::hasColumn('reunions', 'meeting_type_id')) {
                $table->dropConstrainedForeignId('meeting_type_id');
            }
            if (Schema::hasColumn('reunions', 'duration_minutes')) {
                $table->dropColumn('duration_minutes');
            }
            if (Schema::hasColumn('reunions', 'end_at')) {
                $table->dropColumn('end_at');
            }
        });
    }
};
