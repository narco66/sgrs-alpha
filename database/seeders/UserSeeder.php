<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Vérifiez d'abord que les rôles existent
        $roleSuperAdmin = Role::where('name', 'super-admin')->first();
        $roleAdmin      = Role::where('name', 'admin')->first();
        $roleSG         = Role::where('name', 'sg')->first();
        $roleDSI        = Role::where('name', 'dsi')->first();
        $roleStaff      = Role::where('name', 'staff')->first();

        // 1. Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'super.admin@sgrs-ceeac.org'],
            [
                'name'              => 'Super Administrateur SGRS-CEEAC',
                'password'          => Hash::make('Password@2025'),
                'email_verified_at' => now(),
            ]
        );

        if ($roleSuperAdmin && !$superAdmin->hasRole($roleSuperAdmin->name)) {
            $superAdmin->assignRole($roleSuperAdmin);
        }

        // 2. Administrateur (SG)
        $sgAdmin = User::updateOrCreate(
            ['email' => 'sg.admin@sgrs-ceeac.org'],
            [
                'name'              => 'Administrateur Secrétariat Général',
                'password'          => Hash::make('Password@2025'),
                'email_verified_at' => now(),
            ]
        );

        if ($roleAdmin && !$sgAdmin->hasRole($roleAdmin->name)) {
            $sgAdmin->assignRole($roleAdmin);
        }
        if ($roleSG && !$sgAdmin->hasRole($roleSG->name)) {
            $sgAdmin->assignRole($roleSG);
        }

        // 3. Administrateur DSI
        $dsiAdmin = User::updateOrCreate(
            ['email' => 'dsi.admin@sgrs-ceeac.org'],
            [
                'name'              => 'Administrateur DSI',
                'password'          => Hash::make('Password@2025'),
                'email_verified_at' => now(),
            ]
        );

        if ($roleAdmin && !$dsiAdmin->hasRole($roleAdmin->name)) {
            $dsiAdmin->assignRole($roleAdmin);
        }
        if ($roleDSI && !$dsiAdmin->hasRole($roleDSI->name)) {
            $dsiAdmin->assignRole($roleDSI);
        }

        // 4. Utilisateur Staff générique (pour tests)
        $staffUser = User::updateOrCreate(
            ['email' => 'staff@sgrs-ceeac.org'],
            [
                'name'              => 'Utilisateur Staff SGRS',
                'password'          => Hash::make('Password@2025'),
                'email_verified_at' => now(),
            ]
        );

        if ($roleStaff && !$staffUser->hasRole($roleStaff->name)) {
            $staffUser->assignRole($roleStaff);
        }

        // 5. Utilisateur de test supplémentaire
        $testUser = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name'              => 'Test User',
                'password'          => Hash::make('password'), // mot de passe par défaut
                'email_verified_at' => now(),
            ]
        );

        if ($roleStaff && !$testUser->hasRole($roleStaff->name)) {
            $testUser->assignRole($roleStaff);
        }

        // 6. Génération optionnelle de quelques users supplémentaires via factory
        // Assurez-vous que votre UserFactory définit un mot de passe par défaut
        User::factory()->count(10)->create()->each(function (User $user) use ($roleStaff) {
            if ($roleStaff) {
                $user->assignRole($roleStaff);
            }
        });
    }
}
