<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['users.view', 'users.manage']);
    }

    public function view(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return true;
        }

        return $user->hasAnyPermission(['users.view', 'users.manage']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyPermission(['users.create', 'users.manage']);
    }

    public function update(User $user, User $model): bool
    {
        // Seuls les administrateurs / gestionnaires d'utilisateurs peuvent modifier un compte via UserController
        // (l'utilisateur modifie son propre compte via le module Profil, pas ici).
        return $user->hasAnyPermission(['users.update', 'users.manage']);
    }

    public function delete(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return false;
        }

        return $user->hasAnyPermission(['users.delete', 'users.manage']);
    }

    public function toggleActive(User $user, User $model): bool
    {
        return $user->hasAnyPermission(['users.update', 'users.manage']);
    }
}
