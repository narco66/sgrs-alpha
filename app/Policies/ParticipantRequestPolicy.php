<?php

namespace App\Policies;

use App\Models\ParticipantRequest;
use App\Models\User;

class ParticipantRequestPolicy
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
        return true; // Tous les utilisateurs peuvent voir leurs demandes
    }

    public function view(User $user, ParticipantRequest $participantRequest): bool
    {
        return $user->hasAnyRole(['admin', 'dsi', 'chef-departement'])
            || $participantRequest->requested_by === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'dsi', 'chef-departement', 'organisateur', 'fonctionnaire']);
    }

    public function update(User $user, ParticipantRequest $participantRequest): bool
    {
        // Seuls les chefs de département et admins peuvent approuver/rejeter
        if (in_array($ability, ['approve', 'reject'])) {
            return $user->hasAnyRole(['admin', 'dsi', 'chef-departement']);
        }
        
        // Le demandeur peut modifier sa demande si elle est en attente
        return $participantRequest->requested_by === $user->id 
            && $participantRequest->status === 'pending';
    }

    public function delete(User $user, ParticipantRequest $participantRequest): bool
    {
        return $user->hasAnyRole(['admin', 'dsi'])
            || ($participantRequest->requested_by === $user->id && $participantRequest->status === 'pending');
    }

    public function approve(User $user, ParticipantRequest $participantRequest): bool
    {
        return $user->hasAnyRole(['admin', 'dsi', 'chef-departement'])
            && $participantRequest->status === 'pending';
    }

    public function reject(User $user, ParticipantRequest $participantRequest): bool
    {
        return $user->hasAnyRole(['admin', 'dsi', 'chef-departement'])
            && $participantRequest->status === 'pending';
    }
}
