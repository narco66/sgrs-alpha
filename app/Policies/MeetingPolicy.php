<?php

namespace App\Policies;

use App\Models\Meeting;
use App\Models\User;

class MeetingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('meetings.view');
    }

    public function view(User $user, Meeting $meeting): bool
    {
        return $user->can('meetings.view') ||
               $meeting->created_by === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can('meetings.create');
    }

    public function update(User $user, Meeting $meeting): bool
    {
        return $user->can('meetings.update') ||
               $meeting->created_by === $user->id;
    }

    public function delete(User $user, Meeting $meeting): bool
    {
        return $user->can('meetings.delete');
    }
}
