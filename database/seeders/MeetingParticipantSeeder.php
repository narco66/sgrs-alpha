<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Models\MeetingParticipant;
use App\Models\User;
use Illuminate\Database\Seeder;

class MeetingParticipantSeeder extends Seeder
{
    public function run(): void
    {
        $meetings = Meeting::all();
        $users    = User::all();

        foreach ($meetings as $meeting) {
            // 5 participants par réunion
            $selectedUsers = $users->random(min(5, $users->count()));

            foreach ($selectedUsers as $user) {
                MeetingParticipant::firstOrCreate([
                    'meeting_id' => $meeting->id,
                    'user_id'    => $user->id,
                ], [
                    'role'   => 'Participant',
                    'status' => MeetingParticipant::STATUS_INVITED,
                ]);
            }
        }
    }
}
