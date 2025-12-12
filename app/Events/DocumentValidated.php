<?php

namespace App\Events;

use App\Models\Document;
use App\Models\DocumentValidation;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentValidated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Document $document,
        public DocumentValidation $validation,
        public ?User $actor = null,
    ) {
    }
}






