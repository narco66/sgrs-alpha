<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Refonte de la table delegations pour représenter les délégations institutionnelles
     * selon le modèle CEEAC : États membres, organisations internationales, partenaires
     */
    public function up(): void
    {
        Schema::table('delegations', function (Blueprint $table) {
            // Supprimer les colonnes obsolètes si elles existent
            if (Schema::hasColumn('delegations', 'code')) {
                $table->dropColumn('code');
            }
            if (Schema::hasColumn('delegations', 'address')) {
                $table->dropColumn('address');
            }
            
            // Ajouter les nouvelles colonnes pour le modèle institutionnel
            if (!Schema::hasColumn('delegations', 'entity_type')) {
                $table->enum('entity_type', ['state_member', 'international_organization', 'technical_partner', 'financial_partner', 'other'])
                    ->default('state_member')
                    ->after('title');
            }
            
            // Pour les États membres
            if (!Schema::hasColumn('delegations', 'country_code')) {
                $table->string('country_code', 3)->nullable()->after('entity_type');
            }
            
            // Pour les organisations et partenaires
            if (!Schema::hasColumn('delegations', 'organization_name')) {
                $table->string('organization_name')->nullable()->after('country_code');
            }
            if (!Schema::hasColumn('delegations', 'organization_type')) {
                $table->string('organization_type')->nullable()->after('organization_name');
            }
            
            // Contact principal de la délégation
            if (!Schema::hasColumn('delegations', 'head_of_delegation_name')) {
                $table->string('head_of_delegation_name')->nullable()->after('contact_phone');
            }
            if (!Schema::hasColumn('delegations', 'head_of_delegation_position')) {
                $table->string('head_of_delegation_position')->nullable()->after('head_of_delegation_name');
            }
            if (!Schema::hasColumn('delegations', 'head_of_delegation_email')) {
                $table->string('head_of_delegation_email')->nullable()->after('head_of_delegation_position');
            }
            
            // Statut de participation
            if (!Schema::hasColumn('delegations', 'participation_status')) {
                $table->enum('participation_status', ['invited', 'confirmed', 'registered', 'present', 'absent', 'excused'])
                    ->default('invited')
                    ->after('is_active');
            }
            
            // Date de confirmation
            if (!Schema::hasColumn('delegations', 'confirmed_at')) {
                $table->timestamp('confirmed_at')->nullable()->after('participation_status');
            }
            
            // Notes et observations
            if (!Schema::hasColumn('delegations', 'notes')) {
                $table->text('notes')->nullable()->after('confirmed_at');
            }
            
            // Index pour recherche (noms courts pour éviter la limite MySQL de 64 caractères)
            // Vérifier si les colonnes existent avant de créer les index
            if (Schema::hasColumn('delegations', 'meeting_id') && Schema::hasColumn('delegations', 'entity_type')) {
                try {
                    $table->index(['meeting_id', 'entity_type'], 'delegations_meeting_entity_idx');
                } catch (\Exception $e) {
                    // L'index existe déjà, on ignore l'erreur
                }
            }
            if (Schema::hasColumn('delegations', 'meeting_id') && Schema::hasColumn('delegations', 'participation_status')) {
                try {
                    $table->index(['meeting_id', 'participation_status'], 'delegations_meeting_status_idx');
                } catch (\Exception $e) {
                    // L'index existe déjà, on ignore l'erreur
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delegations', function (Blueprint $table) {
            $table->dropIndex('delegations_meeting_entity_idx');
            $table->dropIndex('delegations_meeting_status_idx');
            
            $table->dropColumn([
                'entity_type',
                'country_code',
                'organization_name',
                'organization_type',
                'head_of_delegation_name',
                'head_of_delegation_position',
                'head_of_delegation_email',
                'participation_status',
                'confirmed_at',
                'notes',
            ]);
            
            // Restaurer les colonnes supprimées si nécessaire
            $table->string('code')->unique()->nullable();
            $table->string('address')->nullable();
        });
    }
};

