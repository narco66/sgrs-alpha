<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delegations', function (Blueprint $table) {
            $table->foreignId('meeting_id')
                ->nullable()
                ->after('address')
                ->constrained('reunions')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });

        Schema::create('delegation_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delegation_id')->constrained('delegations')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('utilisateurs')->cascadeOnDelete();
            $table->string('role')->nullable();
            $table->timestamps();

            $table->unique(['delegation_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delegation_participants');

        Schema::table('delegations', function (Blueprint $table) {
            $table->dropForeign(['meeting_id']);
            $table->dropColumn('meeting_id');
        });
    }
};
