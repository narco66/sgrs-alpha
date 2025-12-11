<?php

namespace App\Policies;

use App\Models\OrganizationCommittee;
use App\Models\User;

class OrganizationCommitteePolicy
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
        return $user->can('organization_committees.view');
    }

    public function view(User $user, OrganizationCommittee $organizationCommittee): bool
    {
        if ($user->can('organization_committees.view')) {
            return true;
        }

        return $organizationCommittee->members()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->can('organization_committees.create');
    }

    public function update(User $user, OrganizationCommittee $organizationCommittee): bool
    {
        if ($user->can('organization_committees.update')) {
            return true;
        }

        return $organizationCommittee->created_by === $user->id;
    }

    public function delete(User $user, OrganizationCommittee $organizationCommittee): bool
    {
        if ($user->can('organization_committees.delete')) {
            return true;
        }

        return $organizationCommittee->created_by === $user->id && $organizationCommittee->meeting_id === null;
    }
}
