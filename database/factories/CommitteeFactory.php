<?php

namespace Database\Factories;

use App\Models\Committee;
use App\Models\MeetingType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommitteeFactory extends Factory
{
    protected $model = Committee::class;

    public function definition(): array
    {
        return [
            'name'            => $this->faker->randomElement([
                'Comité des Experts',
                'Comité de suivi',
                'Comité technique sectoriel',
                'Groupe de travail thématique',
            ]),
            'code'            => strtoupper($this->faker->lexify('C??')),
            'meeting_type_id' => MeetingType::query()->inRandomOrder()->value('id')
                                    ?? MeetingType::factory(),
            'is_permanent'    => $this->faker->boolean(70),
            'is_active'       => $this->faker->boolean(90),
            'description'     => $this->faker->optional()->sentence(12),
            'sort_order'      => $this->faker->numberBetween(0, 10),
        ];
    }
}
