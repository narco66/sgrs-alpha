<?php

namespace Database\Seeders;

use App\Models\MeetingType;
use Illuminate\Database\Seeder;

class MeetingTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name'                        => 'Conférence des Chefs d’État',
                'code'                        => 'CCE',
                'color'                       => 'danger',
                'sort_order'                  => 1,
                'requires_president_approval' => true,
                'requires_sg_approval'        => true,
                'description'                 => 'Instance suprême de décision de la Communauté.',
                'is_active'                   => true,
            ],
            [
                'name'                        => 'Conseil des Ministres',
                'code'                        => 'CDM',
                'color'                       => 'primary',
                'sort_order'                  => 2,
                'requires_president_approval' => false,
                'requires_sg_approval'        => true,
                'description'                 => 'Organe exécutif chargé de la mise en œuvre des décisions.',
                'is_active'                   => true,
            ],
            [
                'name'                        => 'Comité des Experts',
                'code'                        => 'CEX',
                'color'                       => 'info',
                'sort_order'                  => 3,
                'requires_president_approval' => false,
                'requires_sg_approval'        => true,
                'description'                 => 'Réunions préparatoires techniques des décisions.',
                'is_active'                   => true,
            ],
            [
                'name'                        => 'Réunion technique',
                'code'                        => 'RT',
                'color'                       => 'success',
                'sort_order'                  => 4,
                'requires_president_approval' => false,
                'requires_sg_approval'        => false,
                'description'                 => 'Réunions internes techniques entre directions.',
                'is_active'                   => true,
            ],
            [
                'name'                        => 'Atelier de validation',
                'code'                        => 'AV',
                'color'                       => 'warning',
                'sort_order'                  => 5,
                'requires_president_approval' => false,
                'requires_sg_approval'        => false,
                'description'                 => 'Ateliers de validation des documents et rapports.',
                'is_active'                   => true,
            ],
        ];

        foreach ($types as $data) {
            MeetingType::updateOrCreate(
                ['code' => $data['code']],
                $data
            );
        }
    }
}
