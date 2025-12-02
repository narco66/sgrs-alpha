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
        Schema::create('demandes_reunions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('meeting_type_id')
                ->nullable()
                ->constrained('types_reunions')
                ->nullOnDelete();
            $table->foreignId('committee_id')
                ->nullable()
                ->constrained('comites')
                ->nullOnDelete();
            $table->dateTime('requested_start_at');
            $table->dateTime('requested_end_at')->nullable();
            $table->foreignId('requested_room_id')
                ->nullable()
                ->constrained('salles')
                ->nullOnDelete();
            $table->string('other_location')->nullable();
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
            $table->foreignId('meeting_id')
                ->nullable()
                ->constrained('reunions')
                ->nullOnDelete(); // Si approuvé, lien vers la réunion créée
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes_reunions');
    }
};
