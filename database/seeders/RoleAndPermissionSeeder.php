<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ------------------------------
        // 1. Définition des permissions
        // ------------------------------
        $permissions = [
            // Dashboard
            'dashboard.view',

            // Meetings
            'meetings.view',
            'meetings.create',
            'meetings.update',
            'meetings.delete',

            // Types de réunions
            'meeting_types.view',
            'meeting_types.create',
            'meeting_types.update',
            'meeting_types.delete',

            // Comités
            'committees.view',
            'committees.create',
            'committees.update',
            'committees.delete',

            // Délégations
            'delegations.view',
            'delegations.create',
            'delegations.update',
            'delegations.delete',

            // Salles
            'rooms.view',
            'rooms.create',
            'rooms.update',
            'rooms.delete',

            // Documents (module déjà prévu dans le cahier des charges)
            'documents.view',
            'documents.create',
            'documents.update',
            'documents.delete',
            'documents.download',
            'documents.validate',

            // Types de documents
            'document_types.view',
            'document_types.create',
            'document_types.update',
            'document_types.delete',
            'document_types.manage',
            
            // Participants
            'participants.view',
            'participants.create',
            'participants.update',
            'participants.delete',

            // Délégations
            'delegations.view',
            'delegations.create',
            'delegations.update',
            'delegations.delete',

            // Calendrier
            'calendar.view',

            // Notifications
            'notifications.view',
            'notifications.manage',

            // Utilisateurs / Rôles
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'users.manage',

            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',
            'roles.manage',

            // Rapports
            'reports.view',
            'reports.export',

            // Audit
            'audit_logs.view',

            // Demandes
            'meeting_requests.view',
            'meeting_requests.create',
            'meeting_requests.update',
            'meeting_requests.approve',
            'meeting_requests.delete',

            'participant_requests.view',
            'participant_requests.create',
            'participant_requests.update',
            'participant_requests.approve',
            'participant_requests.delete',

            // Comités d'organisation
            'organization_committees.view',
            'organization_committees.create',
            'organization_committees.update',
            'organization_committees.delete',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate([
                'name'       => $name,
                'guard_name' => 'web',
            ]);
        }

        // --------------------------------
        // 2. Création des rôles principaux
        // --------------------------------
        $roles = [
            'super-admin',
            'admin',
            'sg',     // Secrétariat Général
            'dsi',    // DSI
            'staff',  // utilisateur "standard"
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate([
                'name'       => $roleName,
                'guard_name' => 'web',
            ]);
        }

        // Rafraîchir les instances
        $allPermissions = Permission::all();
        $roleSuperAdmin = Role::where('name', 'super-admin')->first();
        $roleAdmin      = Role::where('name', 'admin')->first();
        $roleSG         = Role::where('name', 'sg')->first();
        $roleDSI        = Role::where('name', 'dsi')->first();
        $roleStaff      = Role::where('name', 'staff')->first();

        // ------------------------------------------------------
        // 3. Attribution des permissions par "profils" métiers
        // ------------------------------------------------------

        // Super Admin : toutes les permissions
        if ($roleSuperAdmin) {
            $roleSuperAdmin->syncPermissions($allPermissions);
        }

        // Admin : quasi toutes, sauf éventuellement quelques restrictions
        if ($roleAdmin) {
            $roleAdmin->syncPermissions([
                'dashboard.view',

                'meetings.view',
                'meetings.create',
                'meetings.update',
                'meetings.delete',

                'meeting_types.view',
                'meeting_types.create',
                'meeting_types.update',
                'meeting_types.delete',

                'committees.view',
                'committees.create',
                'committees.update',
                'committees.delete',

                'delegations.view',
                'delegations.create',
                'delegations.update',
                'delegations.delete',

                'rooms.view',
                'rooms.create',
                'rooms.update',
                'rooms.delete',

                'documents.view',
                'documents.create',
                'documents.update',
                'documents.delete',

                'calendar.view',

                'notifications.view',
                'notifications.manage',

                'users.view',
                'users.create',
                'users.update',
                'users.delete',

                'roles.view',
                'roles.create',
                'roles.update',
                'roles.delete',
            ]);
        }

        // Rôle SG : focalisé sur pilotage et validation
        if ($roleSG) {
            $roleSG->syncPermissions([
                'dashboard.view',

                'meetings.view',
                'meetings.create',
                'meetings.update',

                'meeting_types.view',
                'committees.view',
                'delegations.view',

                'rooms.view',

                'documents.view',
                'documents.create',
                'documents.update',
                'documents.download',
                'documents.validate',

                'calendar.view',

                'notifications.view',

                'reports.view',

                'meeting_requests.view',
                'meeting_requests.create',
                'meeting_requests.update',
                'meeting_requests.approve',

                'participant_requests.view',
                'participant_requests.create',
                'participant_requests.update',
                'participant_requests.approve',

                'organization_committees.view',
                'organization_committees.create',
                'organization_committees.update',
            ]);
        }

        // Rôle DSI : gestion technique / paramétrage
        if ($roleDSI) {
            $roleDSI->syncPermissions([
                'dashboard.view',

                'meetings.view',

                'meeting_types.view',
                'meeting_types.create',
                'meeting_types.update',

                'committees.view',
                'committees.create',
                'committees.update',

                'delegations.view',
                'delegations.create',
                'delegations.update',
                'delegations.delete',

                'rooms.view',
                'rooms.create',
                'rooms.update',

                'documents.view',

                'calendar.view',

                'notifications.view',
                'notifications.manage',

                'users.view',
                'users.create',
                'users.update',
                'users.manage',

                'roles.view',
                'roles.create',
                'roles.update',
                'roles.delete',
                'roles.manage',

                'reports.view',

                'meeting_requests.view',
                'meeting_requests.approve',

                'participant_requests.view',
                'participant_requests.approve',

                'organization_committees.view',
                'organization_committees.create',
                'organization_committees.update',

                'participants.view',
                'participants.create',
                'participants.update',
            ]);
        }

        // Rôle Staff : lecture + création limitée
        if ($roleStaff) {
            $roleStaff->syncPermissions([
                'dashboard.view',

                'meetings.view',
                'meetings.create',

                'documents.view',
                'documents.download',

                'delegations.view',

                'calendar.view',

                'notifications.view',

                'meeting_requests.view',
                'meeting_requests.create',

                'participant_requests.view',
                'participant_requests.create',
            ]);
        }
    }
}
