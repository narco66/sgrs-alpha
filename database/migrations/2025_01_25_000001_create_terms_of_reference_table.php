<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Table pour gérer les cahiers des charges entre la CEEAC et le pays hôte
     */
    public function up(): void
    {
        Schema::create('cahiers_charges', function (Blueprint $table) {
            $table->id();
            
            // Référence à la réunion
            $table->foreignId('meeting_id')
                ->constrained('reunions')
                ->cascadeOnDelete();
            
            // Pays hôte
            $table->string('host_country')->nullable();
            
            // Dates importantes
            $table->date('signature_date')->nullable();
            $table->date('effective_from')->nullable();
            $table->date('effective_until')->nullable();
            
            // Contenu du cahier des charges
            $table->text('responsibilities_ceeac')->nullable(); // Responsabilités CEEAC
            $table->text('responsibilities_host')->nullable(); // Responsabilités pays hôte
            $table->text('financial_sharing')->nullable(); // Partage financier
            $table->text('logistical_sharing')->nullable(); // Partage logistique
            $table->text('obligations_ceeac')->nullable(); // Obligations CEEAC
            $table->text('obligations_host')->nullable(); // Obligations pays hôte
            $table->text('additional_terms')->nullable(); // Termes additionnels
            
            // Statut et validation
            $table->enum('status', ['draft', 'pending_validation', 'validated', 'signed', 'cancelled'])
                ->default('draft');
            
            // Validation interne CEEAC
            $table->foreignId('validated_by')
                ->nullable()
                ->constrained('utilisateurs')
                ->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            
            // Signature
            $table->foreignId('signed_by_ceeac')
                ->nullable()
                ->constrained('utilisateurs')
                ->nullOnDelete();
            $table->string('signed_by_host_name')->nullable(); // Nom du signataire pays hôte
            $table->string('signed_by_host_position')->nullable(); // Fonction du signataire pays hôte
            $table->timestamp('signed_at')->nullable();
            
            // Version et historique
            $table->integer('version')->default(1);
            $table->foreignId('previous_version_id')
                ->nullable()
                ->constrained('cahiers_charges')
                ->nullOnDelete();
            
            // Fichier PDF généré
            $table->string('pdf_path')->nullable();
            
            // Notes et commentaires
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour recherche
            $table->index(['meeting_id', 'status']);
            $table->index('version');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cahiers_charges');
    }
};




