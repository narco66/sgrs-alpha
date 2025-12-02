<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->foreignId('delegation_id')
                ->nullable()
                ->after('email')
                ->constrained('delegations')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('service')->nullable()->after('delegation_id');
            $table->boolean('is_active')->default(true)->after('service');
        });
    }

    public function down(): void
    {
        Schema::table('utilisateurs', function (Blueprint $table) {
            $table->dropForeign(['delegation_id']);
            $table->dropColumn(['delegation_id', 'first_name', 'last_name', 'service', 'is_active']);
        });
    }
};

