<?php

namespace App\Policies;

use App\Models\Participant;
use App\Models\User;

class ParticipantPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        // Tous les utilisateurs authentifiés peuvent voir la liste des participants
        return true;
    }

    public function view(User $user, Participant $participant): bool
    {
        // Tous les utilisateurs authentifiés peuvent voir un participant
        return true;
    }

    public function create(User $user): bool
    {
        // Seuls les administrateurs et organisateurs peuvent créer des participants
        return $user->hasAnyRole(['super-admin', 'admin', 'dsi', 'sg', 'organisateur']);
    }

    public function update(User $user, Participant $participant): bool
    {
        // Seuls les administrateurs peuvent modifier
        return $user->hasAnyRole(['super-admin', 'admin', 'dsi']);
    }

    public function delete(User $user, Participant $participant): bool
    {
        // Seuls les administrateurs peuvent supprimer
        return $user->hasAnyRole(['super-admin', 'admin', 'dsi']);
    }
}
