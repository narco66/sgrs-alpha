<?php

namespace Database\Factories;

use App\Models\MeetingType;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeetingTypeFactory extends Factory
{
    protected $model = MeetingType::class;

    public function definition(): array
    {
        $name = $this->faker->randomElement([
            'Conférence des Chefs d’État',
            'Conseil des Ministres',
            'Comité des Experts',
            'Réunion technique',
            'Atelier de validation',
        ]);

        $code = strtoupper(substr($name, 0, 3));

        return [
            'name'                        => $name,
            'code'                        => $code,
            'color'                       => $this->faker->randomElement(['primary', 'success', 'info', 'warning']),
            'sort_order'                  => $this->faker->numberBetween(0, 10),
            'requires_president_approval' => $this->faker->boolean(40),
            'requires_sg_approval'        => $this->faker->boolean(80),
            'description'                 => $this->faker->optional()->sentence(10),
            'is_active'                   => $this->faker->boolean(90),
        ];
    }
}
