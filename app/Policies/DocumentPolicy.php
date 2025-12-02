<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('documents.view');
    }

    public function view(User $user, Document $document): bool
    {
        return $user->can('documents.view');
    }

    public function create(User $user): bool
    {
        return $user->can('documents.create');
    }

    public function update(User $user, Document $document): bool
    {
        return $user->can('documents.update') || $document->uploaded_by === $user->id;
    }

    public function delete(User $user, Document $document): bool
    {
        return $user->can('documents.delete') || $document->uploaded_by === $user->id;
    }

    public function download(User $user, Document $document): bool
    {
        return $user->can('documents.download') || $document->is_shared;
    }
}
