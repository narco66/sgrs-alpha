<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Ordre du jour',
                'code' => 'ODJ',
                'description' => 'Ordre du jour des réunions statutaires',
                'requires_validation' => true,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Procès-verbal',
                'code' => 'PV',
                'description' => 'Procès-verbal des réunions',
                'requires_validation' => true,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Rapport',
                'code' => 'RAPPORT',
                'description' => 'Rapports techniques et administratifs',
                'requires_validation' => true,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Présentation',
                'code' => 'PRES',
                'description' => 'Présentations PowerPoint ou similaires',
                'requires_validation' => false,
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Note verbale',
                'code' => 'NOTE',
                'description' => 'Notes verbales et correspondances officielles',
                'requires_validation' => true,
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Projet de décision',
                'code' => 'PDEC',
                'description' => 'Projets de décision communautaire',
                'requires_validation' => true,
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Autre',
                'code' => 'AUTRE',
                'description' => 'Autres types de documents',
                'requires_validation' => false,
                'sort_order' => 99,
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            DocumentType::firstOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}

