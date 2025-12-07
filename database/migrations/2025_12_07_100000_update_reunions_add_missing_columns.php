<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reunions', function (Blueprint $table) {
            if (!Schema::hasColumn('reunions', 'slug')) {
                $table->string('slug')->nullable()->after('title');
            }

            if (!Schema::hasColumn('reunions', 'committee_id')) {
                $table->foreignId('committee_id')
                    ->nullable()
                    ->after('meeting_type_id')
                    ->constrained('comites')
                    ->cascadeOnUpdate()
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('reunions', 'host_country')) {
                $table->string('host_country')->nullable()->after('configuration');
            }

            if (!Schema::hasColumn('reunions', 'agenda')) {
                $table->text('agenda')->nullable()->after('description');
            }

            if (!Schema::hasColumn('reunions', 'end_at')) {
                $table->timestamp('end_at')->nullable()->after('start_at');
            }

            if (!Schema::hasColumn('reunions', 'organizer_id')) {
                $table->foreignId('organizer_id')
                    ->nullable()
                    ->after('created_by')
                    ->constrained('utilisateurs')
                    ->cascadeOnUpdate()
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('reunions', function (Blueprint $table) {
            if (Schema::hasColumn('reunions', 'organizer_id')) {
                $table->dropConstrainedForeignId('organizer_id');
            }
            if (Schema::hasColumn('reunions', 'end_at')) {
                $table->dropColumn('end_at');
            }
            if (Schema::hasColumn('reunions', 'agenda')) {
                $table->dropColumn('agenda');
            }
            if (Schema::hasColumn('reunions', 'host_country')) {
                $table->dropColumn('host_country');
            }
            if (Schema::hasColumn('reunions', 'committee_id')) {
                $table->dropConstrainedForeignId('committee_id');
            }
            if (Schema::hasColumn('reunions', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
};
