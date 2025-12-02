<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('demandes_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')
                ->constrained('reunions')
                ->cascadeOnDelete();
            $table->string('participant_name');
            $table->string('participant_email')->nullable();
            $table->string('participant_role')->nullable();
            $table->text('justification')->nullable();
            $table->foreignId('requested_by')
                ->constrained('utilisateurs')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('utilisateurs')
                ->nullOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->text('review_comments')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('participant_id')
                ->nullable()
                ->constrained('participants_reunions')
                ->nullOnDelete(); // Si approuvé, lien vers le participant créé
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes_participants');
    }
};
