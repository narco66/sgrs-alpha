<?php

namespace Database\Seeders;

use App\Models\Committee;
use App\Models\MeetingType;
use Illuminate\Database\Seeder;

class CommitteeSeeder extends Seeder
{
    public function run(): void
    {
        $cce = MeetingType::where('code', 'CCE')->first();
        $cdm = MeetingType::where('code', 'CDM')->first();
        $cex = MeetingType::where('code', 'CEX')->first();

        $committees = [
            [
                'name'            => 'Comité des Experts Intégration Régionale',
                'code'            => 'CER',
                'meeting_type_id' => $cex?->id,
                'is_permanent'    => true,
                'is_active'       => true,
                'description'     => 'Préparation des dossiers techniques du Conseil des Ministres.',
                'sort_order'      => 1,
            ],
            [
                'name'            => 'Comité de suivi des décisions',
                'code'            => 'CSD',
                'meeting_type_id' => $cdm?->id,
                'is_permanent'    => true,
                'is_active'       => true,
                'description'     => 'Suivi de la mise en œuvre des décisions communautaires.',
                'sort_order'      => 2,
            ],
            [
                'name'            => 'Comité des Experts Financiers',
                'code'            => 'CEF',
                'meeting_type_id' => $cdm?->id,
                'is_permanent'    => true,
                'is_active'       => true,
                'description'     => 'Analyse des budgets, finances et rapports financiers.',
                'sort_order'      => 3,
            ],
            [
                'name'            => 'Groupe de travail PSIMT 2026–2030',
                'code'            => 'GTPS',
                'meeting_type_id' => $cex?->id,
                'is_permanent'    => false,
                'is_active'       => true,
                'description'     => 'Groupe de travail chargé du Plan Stratégique du Système d’Information.',
                'sort_order'      => 4,
            ],
            [
                'name'            => 'Comité préparatoire Sommet CEEAC',
                'code'            => 'CPS',
                'meeting_type_id' => $cce?->id,
                'is_permanent'    => false,
                'is_active'       => true,
                'description'     => 'Préparation logistique et protocolaire des Sommets.',
                'sort_order'      => 5,
            ],
        ];

        foreach ($committees as $data) {
            Committee::updateOrCreate(
                ['code' => $data['code']],
                $data
            );
        }
    }
}
