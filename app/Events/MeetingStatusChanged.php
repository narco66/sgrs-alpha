<?php

namespace App\Events;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MeetingStatusChanged
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Meeting $meeting,
        public ?string $oldStatus,
        public string $newStatus,
        public ?User $actor = null,
        public ?string $comment = null,
    ) {
    }
}




