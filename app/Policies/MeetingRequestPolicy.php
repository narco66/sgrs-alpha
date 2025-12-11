<?php

namespace App\Policies;

use App\Models\MeetingRequest;
use App\Models\User;

class MeetingRequestPolicy
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
        return $user->can('meeting_requests.view') || $user->can('meeting_requests.create');
    }

    public function view(User $user, MeetingRequest $meetingRequest): bool
    {
        return $user->can('meeting_requests.view') || $meetingRequest->requested_by === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can('meeting_requests.create');
    }

    public function update(User $user, MeetingRequest $meetingRequest): bool
    {
        if ($user->can('meeting_requests.update')) {
            return true;
        }

        return $meetingRequest->requested_by === $user->id && $meetingRequest->status === 'pending';
    }

    public function delete(User $user, MeetingRequest $meetingRequest): bool
    {
        if ($user->can('meeting_requests.delete')) {
            return true;
        }

        return $meetingRequest->requested_by === $user->id && $meetingRequest->status === 'pending';
    }

    public function approve(User $user, MeetingRequest $meetingRequest): bool
    {
        return $user->can('meeting_requests.approve') && $meetingRequest->status === 'pending';
    }

    public function reject(User $user, MeetingRequest $meetingRequest): bool
    {
        return $user->can('meeting_requests.approve') && $meetingRequest->status === 'pending';
    }
}
