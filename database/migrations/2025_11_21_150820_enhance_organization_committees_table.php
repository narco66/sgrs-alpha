<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Amélioration de la table comités d'organisation pour distinguer
     * les fonctionnaires CEEAC et du pays hôte
     */
    public function up(): void
    {
        Schema::table('comites_organisation', function (Blueprint $table) {
            // Pays hôte (si la réunion se tient dans un État membre)
            if (!Schema::hasColumn('comites_organisation', 'host_country')) {
                $table->string('host_country')->nullable()->after('description');
            }
            
            // Date de création et d'activation
            if (!Schema::hasColumn('comites_organisation', 'activated_at')) {
                $table->timestamp('activated_at')->nullable()->after('is_active');
            }
            
            // Notes et observations
            if (!Schema::hasColumn('comites_organisation', 'notes')) {
                $table->text('notes')->nullable()->after('activated_at');
            }
        });

        Schema::table('membres_comites_organisation', function (Blueprint $table) {
            // Type de membre : CEEAC ou pays hôte
            if (!Schema::hasColumn('membres_comites_organisation', 'member_type')) {
                $table->enum('member_type', ['ceeac', 'host_country'])
                    ->default('ceeac')
                    ->after('organization_committee_id');
            }
            
            // Service/département d'origine
            if (!Schema::hasColumn('membres_comites_organisation', 'department')) {
                $table->string('department')->nullable()->after('member_type');
            }
            if (!Schema::hasColumn('membres_comites_organisation', 'service')) {
                $table->string('service')->nullable()->after('department');
            }
            
            // Responsabilités spécifiques
            if (!Schema::hasColumn('membres_comites_organisation', 'responsibilities')) {
                $table->text('responsibilities')->nullable()->after('notes');
            }
            
            // Dates d'intervention
            if (!Schema::hasColumn('membres_comites_organisation', 'joined_at')) {
                $table->timestamp('joined_at')->nullable()->after('responsibilities');
            }
            if (!Schema::hasColumn('membres_comites_organisation', 'left_at')) {
                $table->timestamp('left_at')->nullable()->after('joined_at');
            }
            
            // Index pour recherche (nom court pour éviter la limite MySQL de 64 caractères)
            // Ajouter l'index seulement si les colonnes nécessaires existent
            if (Schema::hasColumn('membres_comites_organisation', 'organization_committee_id') 
                && Schema::hasColumn('membres_comites_organisation', 'member_type')) {
                // Vérifier si l'index existe déjà en essayant de le créer (ignorer l'erreur si existe)
                try {
                    $table->index(['organization_committee_id', 'member_type'], 'membres_comite_org_committee_member_idx');
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
        Schema::table('membres_comites_organisation', function (Blueprint $table) {
            $table->dropIndex('membres_comite_org_committee_member_idx');
            $table->dropColumn([
                'member_type',
                'department',
                'service',
                'responsibilities',
                'joined_at',
                'left_at',
            ]);
        });

        Schema::table('comites_organisation', function (Blueprint $table) {
            $table->dropColumn([
                'host_country',
                'activated_at',
                'notes',
            ]);
        });
    }
};

