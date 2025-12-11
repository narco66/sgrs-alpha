<?php

namespace App\Policies;

use App\Models\Participant;
use App\Models\User;

class ParticipantPolicy
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
        return $user->can('participants.view');
    }

    public function view(User $user, Participant $participant): bool
    {
        return $user->can('participants.view');
    }

    public function create(User $user): bool
    {
        return $user->can('participants.create');
    }

    public function update(User $user, Participant $participant): bool
    {
        return $user->can('participants.update');
    }

    public function delete(User $user, Participant $participant): bool
    {
        return $user->can('participants.delete');
    }
}
