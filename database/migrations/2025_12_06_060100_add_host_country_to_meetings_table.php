<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reunions', function (Blueprint $table) {
            if (! Schema::hasColumn('reunions', 'host_country')) {
                $table->string('host_country')->nullable()->after('configuration');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reunions', function (Blueprint $table) {
            if (Schema::hasColumn('reunions', 'host_country')) {
                $table->dropColumn('host_country');
            }
        });
    }
};

