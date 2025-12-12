<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour ajouter les champs logistiques détaillés aux réunions.
 * Permet la saisie complète des éléments de la note logistique.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reunions', function (Blueprint $table) {
            // Section Transport
            $table->text('logistics_transport')->nullable()->after('agenda')
                ->comment('Transport des délégations : moyens, organisation, contacts');
            
            // Section Hébergement
            $table->text('logistics_accommodation')->nullable()->after('logistics_transport')
                ->comment('Hébergement : hôtels, réservations, contacts');
            
            // Section Restauration
            $table->text('logistics_catering')->nullable()->after('logistics_accommodation')
                ->comment('Restauration : repas officiels, traiteurs, menus');
            
            // Section Pauses café
            $table->text('logistics_coffee_breaks')->nullable()->after('logistics_catering')
                ->comment('Organisation des pauses café');
            
            // Section Disposition de la salle
            $table->text('logistics_room_setup')->nullable()->after('logistics_coffee_breaks')
                ->comment('Disposition de la salle : configuration, plan');
            
            // Section Matériel audio/visuel
            $table->text('logistics_av_equipment')->nullable()->after('logistics_room_setup')
                ->comment('Matériel audio/visuel : équipements, besoins techniques');
            
            // Section Interprètes
            $table->text('logistics_interpreters')->nullable()->after('logistics_av_equipment')
                ->comment('Disponibilité des interprètes : langues, effectifs');
            
            // Section Agents de liaison
            $table->text('logistics_liaison_officers')->nullable()->after('logistics_interpreters')
                ->comment('Agents de liaison : contacts, responsabilités');
            
            // Section Sécurité
            $table->text('logistics_security')->nullable()->after('logistics_liaison_officers')
                ->comment('Dispositif de sécurité');
            
            // Section Santé / Dispositif médical
            $table->text('logistics_medical')->nullable()->after('logistics_security')
                ->comment('Dispositif médical : premiers secours, contacts');
            
            // Section Protocole d'accueil
            $table->text('logistics_protocol')->nullable()->after('logistics_medical')
                ->comment('Protocoles d\'accueil : cérémonies, VIP');
            
            // Autres rubriques logistiques
            $table->text('logistics_other')->nullable()->after('logistics_protocol')
                ->comment('Autres éléments logistiques');
            
            // Notes générales logistiques
            $table->text('logistics_notes')->nullable()->after('logistics_other')
                ->comment('Notes et observations générales');
        });

        // Ajouter la colonne equipments à la table salles si elle n'existe pas
        if (!Schema::hasColumn('salles', 'equipments')) {
            Schema::table('salles', function (Blueprint $table) {
                $table->json('equipments')->nullable()->after('description')
                    ->comment('Liste des équipements disponibles dans la salle');
            });
        }
    }

    public function down(): void
    {
        Schema::table('reunions', function (Blueprint $table) {
            $table->dropColumn([
                'logistics_transport',
                'logistics_accommodation',
                'logistics_catering',
                'logistics_coffee_breaks',
                'logistics_room_setup',
                'logistics_av_equipment',
                'logistics_interpreters',
                'logistics_liaison_officers',
                'logistics_security',
                'logistics_medical',
                'logistics_protocol',
                'logistics_other',
                'logistics_notes',
            ]);
        });

        if (Schema::hasColumn('salles', 'equipments')) {
            Schema::table('salles', function (Blueprint $table) {
                $table->dropColumn('equipments');
            });
        }
    }
};












