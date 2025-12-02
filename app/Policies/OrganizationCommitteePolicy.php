<?php

namespace App\Policies;

use App\Models\OrganizationCommittee;
use App\Models\User;

class OrganizationCommitteePolicy
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
        return $user->hasAnyRole(['admin', 'dsi', 'chef-departement', 'organisateur']);
    }

    public function view(User $user, OrganizationCommittee $organizationCommittee): bool
    {
        return $user->hasAnyRole(['admin', 'dsi', 'chef-departement', 'organisateur'])
            || $organizationCommittee->members()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'dsi', 'chef-departement', 'organisateur']);
    }

    public function update(User $user, OrganizationCommittee $organizationCommittee): bool
    {
        return $user->hasAnyRole(['admin', 'dsi', 'chef-departement'])
            || $organizationCommittee->created_by === $user->id;
    }

    public function delete(User $user, OrganizationCommittee $organizationCommittee): bool
    {
        return $user->hasAnyRole(['admin', 'dsi'])
            || ($organizationCommittee->created_by === $user->id && $organizationCommittee->meeting_id === null);
    }
}
