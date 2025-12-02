<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('journaux_audit', function (Blueprint $table) {
            $table->id();

            $table->string('event'); // created, updated, deleted, status_changed, etc.

            $table->morphs('auditable'); // auditable_type, auditable_id
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('utilisateurs')
                ->nullOnDelete();

            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->json('meta')->nullable(); // ex: ['status_from' => 'planifiee', 'status_to' => 'en_cours']

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journaux_audit');
    }
};
