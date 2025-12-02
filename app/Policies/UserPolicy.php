<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['users.view', 'users.manage']);
    }

    /**
     * Determine if the user can view the user.
     */
    public function view(User $user, User $model): bool
    {
        // Les utilisateurs peuvent voir leur propre profil
        if ($user->id === $model->id) {
            return true;
        }
        
        // Les administrateurs peuvent voir tous les utilisateurs
        if ($user->hasAnyRole(['super-admin', 'admin', 'dsi'])) {
            return true;
        }
        
        // Les autres peuvent voir seulement les utilisateurs actifs
        return $model->is_active && $user->hasPermissionTo('users.view');
    }

    /**
     * Determine if the user can create users.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('dsi');
    }

    /**
     * Determine if the user can update the user.
     */
    public function update(User $user, User $model): bool
    {
        // Les utilisateurs peuvent modifier leur propre profil
        if ($user->id === $model->id) {
            return true;
        }

        return $user->hasAnyRole(['super-admin', 'admin', 'dsi']) || $user->hasPermissionTo('users.manage');
    }

    /**
     * Determine if the user can delete the user.
     */
    public function delete(User $user, User $model): bool
    {
        // On ne peut pas supprimer son propre compte
        if ($user->id === $model->id) {
            return false;
        }
        
        return $user->hasRole('dsi');
    }

    /**
     * Determine if the user can toggle active status.
     */
    public function toggleActive(User $user, User $model): bool
    {
        return $user->hasRole('dsi');
    }
}

