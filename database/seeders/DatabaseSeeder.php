<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            UserSeeder::class, // UserSeeder doit être appelé APRÈS RoleAndPermissionSeeder
            MeetingTypeSeeder::class,
            CommitteeSeeder::class,
            RoomSeeder::class,
            DocumentTypeSeeder::class,
            MeetingParticipantSeeder::class,
            MeetingSeeder::class,
        ]);

        // SUPPRIMEZ la création d'utilisateur ici car elle est déjà gérée dans UserSeeder
        // Cela évite les doublons et les conflits
    }
}
