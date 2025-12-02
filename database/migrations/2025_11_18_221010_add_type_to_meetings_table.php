<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reunions', function (Blueprint $table) {
            if (! Schema::hasColumn('reunions', 'type')) {
                $table->string('type')->default('presentiel')->after('title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reunions', function (Blueprint $table) {
            if (Schema::hasColumn('reunions', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
