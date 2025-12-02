<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants_reunions', function (Blueprint $table) {
            $table->id();

            // Référence à la réunion
            $table->foreignId('meeting_id')
                ->constrained('reunions')
                ->cascadeOnDelete();

            // Référence à l'utilisateur participant
            $table->foreignId('user_id')
                ->constrained('utilisateurs')
                ->cascadeOnDelete();

            // Rôle dans la réunion (ex : Observateur, Participant, Président, Rapporteur...)
            $table->string('role')->nullable();

            // Statut de participation : invité, confirmé, absent, excusé, présent
            $table->string('status')->default('invited');

            // Indique s’il a reçu une notification de rappel
            $table->boolean('reminder_sent')->default(false);

            // Validations éventuelles (workflow interne SG/DSI)
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('checked_in_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants_reunions');
    }
};
