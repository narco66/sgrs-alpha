<?php

namespace Database\Factories;

use App\Models\Meeting;
use App\Models\MeetingParticipant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeetingParticipantFactory extends Factory
{
    protected $model = MeetingParticipant::class;

    public function definition(): array
    {
        return [
            'meeting_id' => Meeting::factory(),
            'user_id'    => User::factory(),
            'role'       => $this->faker->randomElement(['Participant', 'Rapporteur', 'Observateur']),
            'status'     => $this->faker->randomElement(MeetingParticipant::statuses()),
            'reminder_sent' => $this->faker->boolean(30),
        ];
    }
}
