<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        return null;
    }

    /**
     * Determine if the user can view any roles.
     * Utilise les permissions Spatie pluton pas de tester un role en dur.
     */
    public function viewAny(User $user): bool
    {
        // Acces pour tout utilisateur ayant une permission de consultation/gestion des roles
        return $user->can('roles.view') || $user->can('roles.manage');
    }

    /**
     * Determine if the user can view the role.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->can('roles.view') || $user->can('roles.manage');
    }

    /**
     * Determine if the user can create roles.
     */
    public function create(User $user): bool
    {
        return $user->can('roles.create') || $user->can('roles.manage');
    }

    /**
     * Determine if the user can update the role.
     */
    public function update(User $user, Role $role): bool
    {
        return $user->can('roles.update') || $user->can('roles.manage');
    }

    /**
     * Determine if the user can delete the role.
     */
    public function delete(User $user, Role $role): bool
    {
        // Verifier d'abord que l'utilisateur a les permissions necessaires
        if (! ($user->can('roles.delete') || $user->can('roles.manage'))) {
            return false;
        }

        // Ne pas permettre la suppression des roles systeme
        $systemRoles = ['super-admin', 'admin', 'sg', 'dsi', 'staff'];
        if (in_array($role->name, $systemRoles)) {
            return false;
        }

        return true;
    }
}
