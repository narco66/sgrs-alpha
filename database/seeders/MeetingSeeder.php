<?php

namespace Database\Seeders;

use App\Models\Committee;
use App\Models\Meeting;
use App\Models\MeetingType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class MeetingSeeder extends Seeder
{
    public function run(): void
    {
        // S’assurer qu’il existe au moins un utilisateur
        $user = User::query()->first() ?? User::factory()->create([
            'name'  => 'Administrateur SGRS',
            'email' => 'admin.sgrs@ceeac.org',
        ]);

        $meetingTypes = MeetingType::all();
        $committees   = Committee::all();

        if ($meetingTypes->isEmpty()) {
            $this->command?->warn('Aucun MeetingType trouvé. Lancer MeetingTypeSeeder avant MeetingSeeder.');
            return;
        }

        // Quelques réunions "manuelles" représentatives
        $data = [
            [
                'title'       => 'Comité des Experts sur l’Intégration Régionale',
                'status'      => 'scheduled',
                'date'        => Carbon::now()->addDays(7)->setTime(9, 0),
                'type_code'   => 'CEX',
                'committee_code' => 'CER',
                'duration'    => 180,
            ],
            [
                'title'       => 'Conseil des Ministres – Session extraordinaire',
                'status'      => 'scheduled',
                'date'        => Carbon::now()->addDays(20)->setTime(10, 0),
                'type_code'   => 'CDM',
                'committee_code' => 'CSD',
                'duration'    => 240,
            ],
            [
                'title'       => 'Atelier de validation du PSIMT 2026–2030',
                'status'      => 'ongoing',
                'date'        => Carbon::now()->subDays(1)->setTime(9, 0),
                'type_code'   => 'AV',
                'committee_code' => 'GTPS',
                'duration'    => 480,
            ],
            [
                'title'       => 'Réunion de suivi des décisions communautaires',
                'status'      => 'completed',
                'date'        => Carbon::now()->subDays(10)->setTime(14, 0),
                'type_code'   => 'RT',
                'committee_code' => 'CSD',
                'duration'    => 120,
            ],
        ];

        foreach ($data as $item) {
            $type = $meetingTypes->firstWhere('code', $item['type_code'])
                ?? $meetingTypes->random();

            $committee = $committees->firstWhere('code', $item['committee_code'])
                ?? $committees->random();

            $start = $item['date'];
            $end   = (clone $start)->addMinutes($item['duration']);

            Meeting::updateOrCreate(
                [
                    'title'        => $item['title'],
                    'start_at'     => $start,
                ],
                [
                    'meeting_type_id'         => $type?->id,
                    'committee_id'            => $committee?->id,
                    'room_id'                 => null,
                    'end_at'                  => $end,
                    'duration_minutes'        => $item['duration'],
                    'status'                  => $item['status'],
                    'description'             => 'Réunion planifiée dans le cadre du suivi des activités de la Commission.',
                    'agenda'                  => 'Ordre du jour défini par le Secrétariat Général.',
                    'organizer_id'            => $user->id,
                    'reminder_minutes_before' => 60,
                ]
            );
        }

        // Générer quelques réunions supplémentaires aléatoires
        Meeting::factory()->count(10)->create();
    }
}
