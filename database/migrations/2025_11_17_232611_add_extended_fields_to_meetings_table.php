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

            // Durée en minutes
            if (!Schema::hasColumn('reunions', 'duration_minutes')) {
                $table->unsignedInteger('duration_minutes')->nullable()->after('end_at');
            }

            // Type de réunion
            if (!Schema::hasColumn('reunions', 'meeting_type_id')) {
                $table->foreignId('meeting_type_id')
                    ->nullable()
                    ->after('title')
                    ->constrained('types_reunions')
                    ->nullOnDelete();
            }

            // Comité
            if (!Schema::hasColumn('reunions', 'committee_id')) {
                $table->foreignId('committee_id')
                    ->nullable()
                    ->after('meeting_type_id')
                    ->constrained('comites')
                    ->nullOnDelete();
            }

            // Salle
            if (!Schema::hasColumn('reunions', 'room_id')) {
                $table->foreignId('room_id')
                    ->nullable()
                    ->after('committee_id')
                    ->constrained('salles')
                    ->nullOnDelete();
            }

            // Statut
            if (!Schema::hasColumn('reunions', 'status')) {
                $table->string('status', 50)
                    ->default('draft')
                    ->after('duration_minutes');
            }

            // Organisateur
            if (!Schema::hasColumn('reunions', 'organizer_id')) {
                $table->foreignId('organizer_id')
                    ->nullable()
                    ->after('status')
                    ->constrained('utilisateurs')
                    ->nullOnDelete();
            }

            // Rappel (minutes avant la réunion)
            if (!Schema::hasColumn('reunions', 'reminder_minutes_before')) {
                $table->unsignedInteger('reminder_minutes_before')
                    ->default(0)
                    ->after('organizer_id');
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
