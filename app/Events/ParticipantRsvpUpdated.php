<?php

namespace App\Events;

use App\Models\Meeting;
use App\Models\MeetingParticipant;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ParticipantRsvpUpdated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public Meeting $meeting,
        public MeetingParticipant $participant,
        public ?User $actor = null,
    ) {
    }
}






