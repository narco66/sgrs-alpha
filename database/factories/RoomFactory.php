<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition(): array
    {
        $names = [
            'Salle de Conférence 1',
            'Salle de Conférence 2',
            'Salle des Experts',
            'Salle du Conseil',
            'Salle des Ministres',
            'Salle Technique',
        ];

        // On peut utiliser unique() sur le nom pour limiter les collisions
        // mais comme on crée plusieurs rooms, on sécurise surtout via le code.
        $name = $this->faker->randomElement($names);

        // Base du code : SALLE_DE_CONFERENCE_1, SALLE_DU_CONSEIL, etc.
        $baseCode = strtoupper(Str::slug($name, '_'));

        // On ajoute un suffixe aléatoire pour garantir l'unicité en base
        $uniqueSuffix = strtoupper(Str::random(4)); // ex : AB3Z

        return [
            'name'        => $name,
            'code'        => $baseCode . '_' . $uniqueSuffix,  // ex : SALLE_DU_CONSEIL_AB3Z
            'capacity'    => $this->faker->randomElement([20, 30, 50, 80, 120]),
            'location'    => $this->faker->randomElement([
                'Bâtiment A – 2e Étage',
                'Bâtiment A – 3e Étage',
                'Bâtiment B – Rez-de-chaussée',
                'Bâtiment C – 1er Étage',
            ]),
            'description' => $this->faker->optional()->sentence(),
            'is_active'   => $this->faker->boolean(90),
        ];
    }
}
