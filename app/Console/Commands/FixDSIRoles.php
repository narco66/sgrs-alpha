<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class FixDSIRoles extends Command
{
    protected $signature = 'sgrs:fix-dsi-roles';

    protected $description = 'Réassigne les rôles DSI et super-admin aux utilisateurs appropriés';

    public function handle(): int
    {
        $this->info('Vérification et correction des rôles...');

        $roleSuperAdmin = Role::where('name', 'super-admin')->first();
        $roleDSI = Role::where('name', 'dsi')->first();

        if (!$roleSuperAdmin) {
            $this->error('Le rôle super-admin n\'existe pas. Exécutez d\'abord le seeder RoleAndPermissionSeeder.');
            return self::FAILURE;
        }

        if (!$roleDSI) {
            $this->error('Le rôle dsi n\'existe pas. Exécutez d\'abord le seeder RoleAndPermissionSeeder.');
            return self::FAILURE;
        }

        // Super Admin
        $superAdmin = User::where('email', 'super.admin@sgrs-ceeac.org')->first();
        if ($superAdmin) {
            if (!$superAdmin->hasRole('super-admin')) {
                $superAdmin->assignRole($roleSuperAdmin);
                $this->info('✓ Rôle super-admin assigné à super.admin@sgrs-ceeac.org');
            } else {
                $this->info('✓ super.admin@sgrs-ceeac.org a déjà le rôle super-admin');
            }
        } else {
            $this->warn('⚠ Utilisateur super.admin@sgrs-ceeac.org non trouvé');
        }

        // DSI Admin
        $dsiAdmin = User::where('email', 'dsi.admin@sgrs-ceeac.org')->first();
        if ($dsiAdmin) {
            if (!$dsiAdmin->hasRole('dsi')) {
                $dsiAdmin->assignRole($roleDSI);
                $this->info('✓ Rôle dsi assigné à dsi.admin@sgrs-ceeac.org');
            } else {
                $this->info('✓ dsi.admin@sgrs-ceeac.org a déjà le rôle dsi');
            }

            // S'assurer qu'il a aussi le rôle admin
            $roleAdmin = Role::where('name', 'admin')->first();
            if ($roleAdmin && !$dsiAdmin->hasRole('admin')) {
                $dsiAdmin->assignRole($roleAdmin);
                $this->info('✓ Rôle admin assigné à dsi.admin@sgrs-ceeac.org');
            }
        } else {
            $this->warn('⚠ Utilisateur dsi.admin@sgrs-ceeac.org non trouvé');
        }

        $this->info('Terminé !');
        return self::SUCCESS;
    }
}

