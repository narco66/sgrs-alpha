<?php

namespace App\Policies;

use App\Models\DocumentType;
use App\Models\User;

class DocumentTypePolicy
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
        return $user->can('document_types.view');
    }

    public function view(User $user, DocumentType $documentType): bool
    {
        return $user->can('document_types.view');
    }

    public function create(User $user): bool
    {
        return $user->can('document_types.create') || $user->can('document_types.manage');
    }

    public function update(User $user, DocumentType $documentType): bool
    {
        return $user->can('document_types.update') || $user->can('document_types.manage');
    }

    public function delete(User $user, DocumentType $documentType): bool
    {
        if (! ($user->can('document_types.delete') || $user->can('document_types.manage'))) {
            return false;
        }

        return $documentType->documents()->count() === 0;
    }
}
