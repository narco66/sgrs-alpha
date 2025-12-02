<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class CheckUserRoles extends Command
{
    protected $signature = 'sgrs:check-user-roles {email?}';

    protected $description = 'Vérifie et affiche les rôles d\'un utilisateur';

    public function handle(): int
    {
        $email = $this->argument('email');

        if ($email) {
            $user = User::where('email', $email)->first();
            if (!$user) {
                $this->error("Utilisateur non trouvé : {$email}");
                return self::FAILURE;
            }

            $this->info("Utilisateur : {$user->email}");
            $this->info("Nom : {$user->name}");
            $this->info("Rôles : " . $user->roles->pluck('name')->join(', '));
            $this->info("Permissions : " . $user->getAllPermissions()->pluck('name')->join(', '));
        } else {
            $this->info("Liste des utilisateurs avec leurs rôles :");
            $this->newLine();

            $users = User::with('roles')->get();
            foreach ($users as $user) {
                $roles = $user->roles->pluck('name')->join(', ') ?: 'Aucun rôle';
                $this->line("  - {$user->email} : {$roles}");
            }
        }

        return self::SUCCESS;
    }
}

