<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    /**
     * Determine if the user can view any roles.
     * Utilise les permissions Spatie plutôt que de tester un rôle en dur.
     */
    public function viewAny(User $user): bool
    {
        // Accès pour tout utilisateur ayant une permission de consultation/gestion des rôles
        // OU pour le super-admin (sécurité au cas où les permissions seraient incomplètes en base)
        return $user->hasRole('super-admin')
            || $user->can('roles.view')
            || $user->can('roles.manage');
    }

    /**
     * Determine if the user can view the role.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasRole('super-admin')
            || $user->can('roles.view')
            || $user->can('roles.manage');
    }

    /**
     * Determine if the user can create roles.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super-admin')
            || $user->can('roles.create')
            || $user->can('roles.manage');
    }

    /**
     * Determine if the user can update the role.
     */
    public function update(User $user, Role $role): bool
    {
        return $user->hasRole('super-admin')
            || $user->can('roles.update')
            || $user->can('roles.manage');
    }

    /**
     * Determine if the user can delete the role.
     */
    public function delete(User $user, Role $role): bool
    {
        // Vérifier d'abord que l'utilisateur a les permissions nécessaires
        if (! ($user->hasRole('super-admin')
            || $user->can('roles.delete')
            || $user->can('roles.manage'))) {
            return false;
        }

        // Ne pas permettre la suppression des rôles système
        $systemRoles = ['super-admin', 'admin', 'sg', 'dsi', 'staff'];
        if (in_array($role->name, $systemRoles)) {
            return false;
        }

        return true;
    }
}

