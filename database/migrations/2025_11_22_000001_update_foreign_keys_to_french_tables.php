<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Mettre à jour toutes les clés étrangères pour pointer vers les nouvelles tables françaises
     */
    public function up(): void
    {
        // Cette migration met à jour les contraintes de clés étrangères
        // Note: MySQL nécessite de supprimer et recréer les contraintes
        
        // Mise à jour des clés étrangères vers 'utilisateurs' (anciennement 'users')
        $this->updateForeignKey('meetings', 'organizer_id', 'utilisateurs', 'id');
        $this->updateForeignKey('documents', 'uploaded_by', 'utilisateurs', 'id');
        $this->updateForeignKey('participants_reunions', 'user_id', 'utilisateurs', 'id');
        $this->updateForeignKey('organization_committee_members', 'user_id', 'utilisateurs', 'id');
        $this->updateForeignKey('meeting_requests', 'requested_by', 'utilisateurs', 'id');
        $this->updateForeignKey('meeting_requests', 'reviewed_by', 'utilisateurs', 'id');
        $this->updateForeignKey('participant_requests', 'requested_by', 'utilisateurs', 'id');
        $this->updateForeignKey('participant_requests', 'reviewed_by', 'utilisateurs', 'id');
        $this->updateForeignKey('organization_committees', 'created_by', 'utilisateurs', 'id');
        $this->updateForeignKey('audit_logs', 'user_id', 'utilisateurs', 'id');
        $this->updateForeignKey('status_histories', 'changed_by', 'utilisateurs', 'id');
        $this->updateForeignKey('meeting_status_histories', 'changed_by', 'utilisateurs', 'id');
        $this->updateForeignKey('document_validations', 'validated_by', 'utilisateurs', 'id');
        $this->updateForeignKey('document_versions', 'created_by', 'utilisateurs', 'id');
        
        // Mise à jour des clés étrangères vers 'reunions' (anciennement 'meetings')
        $this->updateForeignKey('participants_reunions', 'meeting_id', 'reunions', 'id');
        $this->updateForeignKey('documents', 'meeting_id', 'reunions', 'id');
        $this->updateForeignKey('organization_committees', 'meeting_id', 'reunions', 'id');
        $this->updateForeignKey('participant_requests', 'meeting_id', 'reunions', 'id');
        $this->updateForeignKey('status_histories', 'meeting_id', 'reunions', 'id');
        $this->updateForeignKey('meeting_status_histories', 'meeting_id', 'reunions', 'id');
        $this->updateForeignKey('meeting_requests', 'meeting_id', 'reunions', 'id');
        
        // Mise à jour des clés étrangères vers 'types_reunions' (anciennement 'meeting_types')
        $this->updateForeignKey('reunions', 'meeting_type_id', 'types_reunions', 'id');
        $this->updateForeignKey('meeting_requests', 'meeting_type_id', 'types_reunions', 'id');
        
        // Mise à jour des clés étrangères vers 'comites' (anciennement 'committees')
        $this->updateForeignKey('reunions', 'committee_id', 'comites', 'id');
        $this->updateForeignKey('meeting_requests', 'committee_id', 'comites', 'id');
        
        // Mise à jour des clés étrangères vers 'salles' (anciennement 'rooms')
        $this->updateForeignKey('reunions', 'room_id', 'salles', 'id');
        $this->updateForeignKey('meeting_requests', 'requested_room_id', 'salles', 'id');
        
        // Mise à jour des clés étrangères vers 'types_documents' (anciennement 'document_types')
        $this->updateForeignKey('documents', 'document_type_id', 'types_documents', 'id');
        
        // Mise à jour des clés étrangères vers 'documents'
        $this->updateForeignKey('document_versions', 'document_id', 'documents', 'id');
        $this->updateForeignKey('document_validations', 'document_id', 'documents', 'id');
        
        // Mise à jour des clés étrangères vers 'delegations'
        $this->updateForeignKey('utilisateurs', 'delegation_id', 'delegations', 'id');
        
        // Mise à jour des clés étrangères vers 'comites_organisation'
        $this->updateForeignKey('membres_comites_organisation', 'organization_committee_id', 'comites_organisation', 'id');
    }

    /**
     * Helper pour mettre à jour une clé étrangère
     */
    private function updateForeignKey(string $table, string $column, string $referencedTable, string $referencedColumn): void
    {
        if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
            return;
        }

        // Récupérer le nom de la contrainte existante
        $constraintName = $this->getForeignKeyName($table, $column);
        
        if ($constraintName) {
            // Supprimer l'ancienne contrainte
            Schema::table($table, function (Blueprint $table) use ($constraintName) {
                $table->dropForeign($constraintName);
            });
        }

        // Recréer la contrainte avec la nouvelle table référencée
        Schema::table($table, function (Blueprint $table) use ($column, $referencedTable, $referencedColumn) {
            $table->foreign($column)
                ->references($referencedColumn)
                ->on($referencedTable)
                ->onDelete('cascade');
        });
    }

    /**
     * Récupérer le nom de la contrainte de clé étrangère
     */
    private function getForeignKeyName(string $table, string $column): ?string
    {
        $result = DB::select(
            "SELECT CONSTRAINT_NAME 
             FROM information_schema.KEY_COLUMN_USAGE 
             WHERE TABLE_SCHEMA = DATABASE() 
             AND TABLE_NAME = ? 
             AND COLUMN_NAME = ? 
             AND REFERENCED_TABLE_NAME IS NOT NULL",
            [$table, $column]
        );

        return $result[0]->CONSTRAINT_NAME ?? null;
    }

    public function down(): void
    {
        // En cas de rollback, on ne peut pas automatiquement restaurer les anciennes contraintes
        // Il faudrait les restaurer manuellement ou recréer les tables
    }
};

