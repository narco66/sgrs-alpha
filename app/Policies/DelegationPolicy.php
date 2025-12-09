<?php

namespace App\Policies;

use App\Models\Meeting;
use App\Models\Delegation;
use App\Models\User;

class DelegationPolicy
{
    /**
     * Determine whether the user can view any models.
     * EF12 - Consulter une délégation (tous les utilisateurs)
     */
    public function viewAny(User $user): bool
    {
        return true; // Tous les utilisateurs peuvent consulter les délégations
    }

    /**
     * Determine whether the user can view the model.
     * EF12 - Consulter une délégation
     */
    public function view(User $user, Delegation $delegation): bool
    {
        return true; // Tous les utilisateurs peuvent consulter une délégation
    }

    /**
     * Determine whether the user can create models.
     * EF09 - Ajout d'une délégation (administrateurs)
     */
    public function create(User $user): bool
    {
        if ($user->can('delegations.create')) {
            return true;
        }

        // Autoriser aussi le créateur/gestionnaire de la réunion ciblée
        $meetingId = request()->input('meeting_id');
        if ($meetingId) {
            $meeting = Meeting::find($meetingId);
            if ($meeting && $user->can('update', $meeting)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     * EF10 - Modification d'une délégation (administrateurs)
     */
    public function update(User $user, Delegation $delegation): bool
    {
        if ($user->can('delegations.update')) {
            return true;
        }

        if ($delegation->meeting && $user->can('update', $delegation->meeting)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     * EF11 - Suppression de délégation (administrateurs)
     */
    public function delete(User $user, Delegation $delegation): bool
    {
        // Vérifier qu'il n'y a pas de membres
        if ($delegation->members()->count() > 0) {
            return false;
        }

        if ($user->can('delegations.delete')) {
            return true;
        }

        return $delegation->meeting && $user->can('update', $delegation->meeting);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Delegation $delegation): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'dsi']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Delegation $delegation): bool
    {
        return $user->hasAnyRole(['super-admin', 'admin', 'dsi']);
    }
}

