<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reunions', function (Blueprint $table) {
            $table->id();
            $table->string('title');

            $table->foreignId('meeting_type_id')
                ->constrained('types_reunions')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('room_id')
                ->nullable()
                ->constrained('salles')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->enum('configuration', ['presentiel', 'hybride', 'visioconference'])
                ->default('presentiel');

            $table->text('description')->nullable();

            $table->timestamp('start_at');
            $table->unsignedInteger('duration_minutes')->default(30);

            $table->string('status')->default('planifiee'); // planifiee, en_preparation, en_cours, terminee, annulee…

            // Rappel en minutes avant la réunion (0 = aucun)
            $table->unsignedInteger('reminder_minutes_before')->default(0);

            // Créateur / organisateur
            $table->foreignId('created_by')
                ->constrained('utilisateurs')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reunions');
    }
};
