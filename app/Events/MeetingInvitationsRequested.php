<?php

namespace App\Events;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MeetingInvitationsRequested
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Meeting $meeting,
        public ?User $actor = null,
        public string $source = 'manual',
    ) {
    }
}








