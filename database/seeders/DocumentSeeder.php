<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Rôles & permissions (Spatie)
        $this->call(RoleAndPermissionSeeder::class);

        // 2. Utilisateurs initiaux
        $this->call(UserSeeder::class);

        // 3. Paramétrage fonctionnel SGRS-CEEAC
        $this->call([
            MeetingTypeSeeder::class,
            CommitteeSeeder::class,
            RoomSeeder::class,
            MeetingSeeder::class,
        ]);
    }
}
