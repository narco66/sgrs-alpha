<?php

namespace App\Events;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MeetingCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Meeting $meeting,
        public ?User $actor = null,
    ) {
    }
}




