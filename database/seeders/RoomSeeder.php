<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'name'        => 'Salle de Conférence 1',
                'code'        => 'CONF1',
                'capacity'    => 120,
                'location'    => 'Bâtiment A – 2e Étage',
                'description' => 'Salle principale utilisée pour les sessions du Conseil et les réunions de haut niveau.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Salle de Conférence 2',
                'code'        => 'CONF2',
                'capacity'    => 80,
                'location'    => 'Bâtiment A – 3e Étage',
                'description' => 'Salle utilisée pour les réunions intermédiaires et ateliers techniques.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Salle du Conseil',
                'code'        => 'CONSEIL',
                'capacity'    => 50,
                'location'    => 'Bâtiment B – 1er Étage',
                'description' => 'Salle dédiée au Conseil des Ministres et réunions officielles.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Salle des Experts',
                'code'        => 'EXPERTS',
                'capacity'    => 30,
                'location'    => 'Bâtiment C – Rez-de-chaussée',
                'description' => 'Salle réservée au Comité des Experts.',
                'is_active'   => true,
            ],
            [
                'name'        => 'Salle Technique',
                'code'        => 'TECH1',
                'capacity'    => 20,
                'location'    => 'Bâtiment C – 1er Étage',
                'description' => 'Salle pour les réunions techniques et formations internes.',
                'is_active'   => true,
            ],
        ];

        foreach ($rooms as $data) {
            Room::updateOrCreate(['code' => $data['code']], $data);
        }

        // Génération de salles supplémentaires si besoin
        Room::factory()->count(5)->create();
    }
}
