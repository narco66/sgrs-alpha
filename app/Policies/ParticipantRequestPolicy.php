<?php

namespace App\Policies;

use App\Models\ParticipantRequest;
use App\Models\User;

class ParticipantRequestPolicy
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
        return $user->can('participant_requests.view') || $user->can('participant_requests.create');
    }

    public function view(User $user, ParticipantRequest $participantRequest): bool
    {
        return $user->can('participant_requests.view') || $participantRequest->requested_by === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can('participant_requests.create');
    }

    public function update(User $user, ParticipantRequest $participantRequest): bool
    {
        if ($user->can('participant_requests.update')) {
            return true;
        }

        return $participantRequest->requested_by === $user->id && $participantRequest->status === 'pending';
    }

    public function delete(User $user, ParticipantRequest $participantRequest): bool
    {
        if ($user->can('participant_requests.delete')) {
            return true;
        }

        return $participantRequest->requested_by === $user->id && $participantRequest->status === 'pending';
    }

    public function approve(User $user, ParticipantRequest $participantRequest): bool
    {
        return $user->can('participant_requests.approve') && $participantRequest->status === 'pending';
    }

    public function reject(User $user, ParticipantRequest $participantRequest): bool
    {
        return $user->can('participant_requests.approve') && $participantRequest->status === 'pending';
    }
}
