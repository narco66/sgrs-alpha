<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reunions', function (Blueprint $table) {
            if (! Schema::hasColumn('reunions', 'reminder_minutes_before')) {
                $table->unsignedInteger('reminder_minutes_before')->default(0)->after('status');
            }

            if (! Schema::hasColumn('reunions', 'reminder_sent_at')) {
                $table->timestamp('reminder_sent_at')->nullable()->after('reminder_minutes_before');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reunions', function (Blueprint $table) {
            if (Schema::hasColumn('reunions', 'reminder_sent_at')) {
                $table->dropColumn('reminder_sent_at');
            }
            // On peut laisser reminder_minutes_before si déjà utilisé dans le reste de l’appli
        });
    }
};
