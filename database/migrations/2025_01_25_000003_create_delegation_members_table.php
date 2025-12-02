<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Table pour les membres individuels d'une délégation
     * Une délégation peut avoir un ou plusieurs membres
     */
    public function up(): void
    {
        Schema::create('membres_delegations', function (Blueprint $table) {
            $table->id();
            
            // Référence à la délégation
            $table->foreignId('delegation_id')
                ->constrained('delegations')
                ->cascadeOnDelete();
            
            // Informations du membre
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            
            // Fonction/position dans la délégation
            $table->string('position')->nullable(); // Ex: Chef de délégation, Expert, Observateur
            $table->string('title')->nullable(); // Ex: Ministre, Ambassadeur, Directeur
            
            // Institution/organisation d'origine
            $table->string('institution')->nullable();
            $table->string('department')->nullable();
            
            // Rôle dans la délégation
            $table->enum('role', ['head', 'member', 'expert', 'observer', 'secretary'])
                ->default('member');
            
            // Statut de participation individuel
            $table->enum('status', ['invited', 'confirmed', 'present', 'absent', 'excused'])
                ->default('invited');
            
            // Dates importantes
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            
            // Notes spécifiques au membre
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Index pour recherche (noms courts pour éviter la limite MySQL de 64 caractères)
            $table->index(['delegation_id', 'role'], 'membres_deleg_role_idx');
            $table->index(['delegation_id', 'status'], 'membres_deleg_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membres_delegations');
    }
};

