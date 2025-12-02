<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Renommer toutes les tables en français
     */
    public function up(): void
    {
        // Mapping des tables : anglais -> français
        $tables = [
            'users' => 'utilisateurs',
            'meetings' => 'reunions',
            'meeting_types' => 'types_reunions',
            'committees' => 'comites',
            'rooms' => 'salles',
            'documents' => 'documents',
            'document_types' => 'types_documents',
            'document_versions' => 'versions_documents',
            'document_validations' => 'validations_documents',
            'participants' => 'participants',
            'meeting_participants' => 'participants_reunions',
            'delegations' => 'delegations',
            'notifications' => 'notifications',
            'room_reservations' => 'reservations_salles',
            'status_histories' => 'historiques_statuts',
            'meeting_status_histories' => 'historiques_statuts_reunions',
            'organization_committees' => 'comites_organisation',
            'organization_committee_members' => 'membres_comites_organisation',
            'meeting_requests' => 'demandes_reunions',
            'participant_requests' => 'demandes_participants',
            'audit_logs' => 'journaux_audit',
        ];

        foreach ($tables as $oldName => $newName) {
            if (Schema::hasTable($oldName) && !Schema::hasTable($newName)) {
                Schema::rename($oldName, $newName);
            }
        }

        // Renommer les tables de Spatie Permission
        if (Schema::hasTable('roles') && !Schema::hasTable('roles')) {
            // Les tables roles et permissions restent en anglais pour Spatie
            // Mais on peut créer des alias si nécessaire
        }
    }

    /**
     * Revenir aux noms anglais
     */
    public function down(): void
    {
        $tables = [
            'utilisateurs' => 'users',
            'reunions' => 'meetings',
            'types_reunions' => 'meeting_types',
            'comites' => 'committees',
            'salles' => 'rooms',
            'documents' => 'documents',
            'types_documents' => 'document_types',
            'versions_documents' => 'document_versions',
            'validations_documents' => 'document_validations',
            'participants' => 'participants',
            'participants_reunions' => 'meeting_participants',
            'delegations' => 'delegations',
            'notifications' => 'notifications',
            'reservations_salles' => 'room_reservations',
            'historiques_statuts' => 'status_histories',
            'historiques_statuts_reunions' => 'meeting_status_histories',
            'comites_organisation' => 'organization_committees',
            'membres_comites_organisation' => 'organization_committee_members',
            'demandes_reunions' => 'meeting_requests',
            'demandes_participants' => 'participant_requests',
            'journaux_audit' => 'audit_logs',
        ];

        foreach ($tables as $oldName => $newName) {
            if (Schema::hasTable($oldName) && !Schema::hasTable($newName)) {
                Schema::rename($oldName, $newName);
            }
        }
    }
};

