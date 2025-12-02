<?php

// database/factories/MeetingFactory.php

namespace Database\Factories;

use App\Models\Meeting;
use App\Models\User;
use App\Models\MeetingType;
use App\Models\Committee;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Enums\MeetingStatus;

class MeetingFactory extends Factory
{
    protected $model = Meeting::class;

    public function definition(): array
    {
        // On génère d'abord un début, puis on ajoute une durée positive
        $start = $this->faker->dateTimeBetween('+1 days', '+2 months');
        $duration = $this->faker->randomElement([30, 60, 90, 120, 180]); // minutes
        $end = (clone $start)->modify("+{$duration} minutes");

        $organizerId = User::query()->inRandomOrder()->value('id') ?? 1;

        return [
            'title'         => $this->faker->sentence(6),
            'slug'          => Str::slug($this->faker->unique()->sentence(3)),
            'meeting_type_id' => MeetingType::query()->inRandomOrder()->value('id') ?? 1,
            'committee_id'    => Committee::query()->inRandomOrder()->value('id'),
            'room_id'         => Room::query()->inRandomOrder()->value('id'),
            'start_at'        => $start,
            'end_at'          => $end,
            'duration_minutes'=> $duration,            // toujours positif
            'status'          => MeetingStatus::PLANNED->value,
            'description'     => $this->faker->optional()->paragraph(),
            'agenda'          => $this->faker->optional()->sentence(),
            'organizer_id'    => $organizerId,
            'reminder_minutes_before' => 60,
            'created_by'      => $organizerId,
            'updated_by'      => $organizerId,
        ];
    }
}
