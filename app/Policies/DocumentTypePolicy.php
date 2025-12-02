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

    /**
     * Determine if the user can view any document types.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'sg', 'dsi', 'staff']);
    }

    /**
     * Determine if the user can view the document type.
     */
    public function view(User $user, DocumentType $documentType): bool
    {
        return $user->hasAnyRole(['admin', 'sg', 'dsi', 'staff']);
    }

    /**
     * Determine if the user can create document types.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'dsi']);
    }

    /**
     * Determine if the user can update the document type.
     */
    public function update(User $user, DocumentType $documentType): bool
    {
        return $user->hasAnyRole(['admin', 'dsi']);
    }

    /**
     * Determine if the user can delete the document type.
     */
    public function delete(User $user, DocumentType $documentType): bool
    {
        return $user->hasAnyRole(['admin', 'dsi']) 
            && $documentType->documents()->count() === 0;
    }
}

