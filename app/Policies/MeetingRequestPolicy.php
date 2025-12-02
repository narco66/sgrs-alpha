<?php

namespace App\Policies;

use App\Models\MeetingRequest;
use App\Models\User;

class MeetingRequestPolicy
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

    public function view(User $user, MeetingRequest $meetingRequest): bool
    {
        return $user->hasAnyRole(['admin', 'dsi', 'chef-departement'])
            || $meetingRequest->requested_by === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'dsi', 'chef-departement', 'organisateur', 'fonctionnaire']);
    }

    public function update(User $user, MeetingRequest $meetingRequest): bool
    {
        // Seuls les chefs de département et admins peuvent approuver/rejeter
        if (in_array($ability, ['approve', 'reject'])) {
            return $user->hasAnyRole(['admin', 'dsi', 'chef-departement']);
        }
        
        // Le demandeur peut modifier sa demande si elle est en attente
        return $meetingRequest->requested_by === $user->id 
            && $meetingRequest->status === 'pending';
    }

    public function delete(User $user, MeetingRequest $meetingRequest): bool
    {
        return $user->hasAnyRole(['admin', 'dsi'])
            || ($meetingRequest->requested_by === $user->id && $meetingRequest->status === 'pending');
    }

    public function approve(User $user, MeetingRequest $meetingRequest): bool
    {
        return $user->hasAnyRole(['admin', 'dsi', 'chef-departement'])
            && $meetingRequest->status === 'pending';
    }

    public function reject(User $user, MeetingRequest $meetingRequest): bool
    {
        return $user->hasAnyRole(['admin', 'dsi', 'chef-departement'])
            && $meetingRequest->status === 'pending';
    }
}
