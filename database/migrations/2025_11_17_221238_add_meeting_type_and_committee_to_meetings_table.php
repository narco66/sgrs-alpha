<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reunions', function (Blueprint $table) {
            if (!Schema::hasColumn('reunions', 'meeting_type_id')) {
                $table->foreignId('meeting_type_id')
                    ->nullable()
                    ->after('title')
                    ->constrained('types_reunions')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('reunions', 'committee_id')) {
                $table->foreignId('committee_id')
                    ->nullable()
                    ->after('meeting_type_id')
                    ->constrained('comites')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('reunions', function (Blueprint $table) {
            if (Schema::hasColumn('reunions', 'committee_id')) {
                $table->dropConstrainedForeignId('committee_id');
            }
            if (Schema::hasColumn('reunions', 'meeting_type_id')) {
                $table->dropConstrainedForeignId('meeting_type_id');
            }
        });
    }
};
