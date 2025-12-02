<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    /**
     * Determine if the user can view any roles.
     * Seul le super-admin peut gérer les rôles et permissions
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can view the role.
     * Seul le super-admin peut voir les rôles
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can create roles.
     * Seul le super-admin peut créer des rôles
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can update the role.
     * Seul le super-admin peut modifier les rôles et attribuer les permissions
     */
    public function update(User $user, Role $role): bool
    {
        // Seul le super-admin peut modifier les rôles
        return $user->hasRole('super-admin');
    }

    /**
     * Determine if the user can delete the role.
     */
    public function delete(User $user, Role $role): bool
    {
        // Seul super-admin peut supprimer des rôles
        if (!$user->hasRole('super-admin')) {
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

