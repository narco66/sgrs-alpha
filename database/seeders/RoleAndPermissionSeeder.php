<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Definition des permissions
        $permissions = [
            // Dashboard
            'dashboard.view',

            // Meetings
            'meetings.view',
            'meetings.create',
            'meetings.update',
            'meetings.delete',

            // Types de reunions
            'meeting_types.view',
            'meeting_types.create',
            'meeting_types.update',
            'meeting_types.delete',

            // Comites
            'committees.view',
            'committees.create',
            'committees.update',
            'committees.delete',

            // Delegations
            'delegations.view',
            'delegations.create',
            'delegations.update',
            'delegations.delete',

            // Salles
            'rooms.view',
            'rooms.create',
            'rooms.update',
            'rooms.delete',

            // Documents
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

            // Calendrier
            'calendar.view',

            // Notifications
            'notifications.view',
            'notifications.manage',

            // Utilisateurs / Roles
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

            // Matrice RACI
            'raci.view',

            // Demandes de reunions
            'meeting_requests.view',
            'meeting_requests.create',
            'meeting_requests.update',
            'meeting_requests.approve',
            'meeting_requests.delete',

            // Demandes de participants
            'participant_requests.view',
            'participant_requests.create',
            'participant_requests.update',
            'participant_requests.approve',
            'participant_requests.delete',

            // Comites d'organisation
            'organization_committees.view',
            'organization_committees.create',
            'organization_committees.update',
            'organization_committees.delete',

            // Participants
            'participants.view',
            'participants.create',
            'participants.update',
            'participants.delete',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate([
                'name'       => $name,
                'guard_name' => 'web',
            ]);
        }

        // 2. Creation des roles principaux
        $roles = [
            'super-admin',
            'admin',
            'sg',
            'dsi',
            'staff',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate([
                'name'       => $roleName,
                'guard_name' => 'web',
            ]);
        }

        // Rafraichir les instances
        $allPermissions = Permission::all();
        $roleSuperAdmin = Role::where('name', 'super-admin')->first();
        $roleAdmin      = Role::where('name', 'admin')->first();
        $roleSG         = Role::where('name', 'sg')->first();
        $roleDSI        = Role::where('name', 'dsi')->first();
        $roleStaff      = Role::where('name', 'staff')->first();

        // 3. Attribution des permissions par role

        // Super Admin : toutes les permissions
        if ($roleSuperAdmin) {
            $roleSuperAdmin->syncPermissions($allPermissions);
        }

        // Admin : gestion globale applicative
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
                'documents.download',
                'documents.validate',

                'document_types.view',
                'document_types.create',
                'document_types.update',
                'document_types.delete',
                'document_types.manage',

                'calendar.view',

                'notifications.view',
                'notifications.manage',

                'users.view',
                'users.create',
                'users.update',
                'users.delete',
                'users.manage',

                'roles.view',
                'roles.create',
                'roles.update',
                'roles.delete',

                'raci.view',

                'reports.view',
                'reports.export',

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

                'organization_committees.view',
                'organization_committees.create',
                'organization_committees.update',
                'organization_committees.delete',

                'participants.view',
                'participants.create',
                'participants.update',
                'participants.delete',
            ]);
        }

        // SG : pilotage et validation
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

                'document_types.view',

                'calendar.view',

                'notifications.view',

                'reports.view',

                'raci.view',

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

                'participants.view',
                'participants.create',
            ]);
        }

        // DSI : gestion technique / parametrage
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

                'document_types.view',
                'document_types.create',
                'document_types.update',
                'document_types.delete',
                'document_types.manage',

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

                'raci.view',

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

                'organization_committees.view',
                'organization_committees.create',
                'organization_committees.update',

                'participants.view',
                'participants.create',
                'participants.update',
                'participants.delete',
            ]);
        }

        // Staff : lecture + creation limitee
        if ($roleStaff) {
            $roleStaff->syncPermissions([
                'dashboard.view',

                'meetings.view',
                'meetings.create',

                'documents.view',
                'documents.download',

                'document_types.view',

                'delegations.view',

                'calendar.view',

                'notifications.view',

                'raci.view',

                'meeting_requests.view',
                'meeting_requests.create',

                'participant_requests.view',
                'participant_requests.create',

                'participants.view',
            ]);
        }
    }
}
