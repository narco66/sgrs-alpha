<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Liste des rôles avec leurs permissions
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(403, 'Vous devez être connecté pour accéder à cette page.');
        }
        
        // Charger explicitement les rôles pour éviter les problèmes de cache
        $user->load('roles');
        
        // Vérification directe des rôles (plus fiable que hasRole)
        $userRoles = $user->roles->pluck('name')->toArray();
        $isSuperAdmin = in_array('super-admin', $userRoles);
        
        // Seul le super-admin peut accéder à la gestion des rôles
        if (!$isSuperAdmin) {
            $userRolesStr = implode(', ', $userRoles) ?: 'Aucun rôle';
            $message = "Accès refusé.\n\n";
            $message .= "Seul le Super-Administrateur peut accéder à la gestion des rôles et permissions.\n\n";
            $message .= "Votre compte : {$user->email}\n";
            $message .= "Vos rôles actuels : {$userRolesStr}\n\n";
            $message .= "Pour accéder à cette page, connectez-vous avec :\n";
            $message .= "Super-Admin : super.admin@sgrs-ceeac.org / Password@2025";
            
            abort(403, $message);
        }

        $search = $request->get('search', '');
        
        $query = Role::with(['permissions', 'users'])
            ->withCount(['permissions', 'users']);

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $roles = $query->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        // Récupérer toutes les permissions pour les formulaires
        $allPermissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            // Grouper par module (ex: meetings.view -> meetings)
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'other';
        });

        return view('roles.index', [
            'roles' => $roles,
            'allPermissions' => $allPermissions,
            'search' => $search,
            'currentUser' => $user, // Passer l'utilisateur à la vue
        ]);
    }

    /**
     * Formulaire de création d'un rôle
     */
    public function create()
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(403, 'Vous devez être connecté pour accéder à cette page.');
        }
        
        $user->load('roles');
        $userRoles = $user->roles->pluck('name')->toArray();
        $isSuperAdmin = in_array('super-admin', $userRoles);
        
        if (!$isSuperAdmin) {
            abort(403, 'Seul le Super-Administrateur peut créer des rôles.');
        }

        $allPermissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'other';
        });

        return view('roles.create', [
            'allPermissions' => $allPermissions,
        ]);
    }

    /**
     * Enregistrement d'un nouveau rôle
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(403, 'Vous devez être connecté pour accéder à cette page.');
        }
        
        $user->load('roles');
        $userRoles = $user->roles->pluck('name')->toArray();
        $isSuperAdmin = in_array('super-admin', $userRoles);
        
        if (!$isSuperAdmin) {
            abort(403, 'Seul le Super-Administrateur peut créer des rôles.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        DB::transaction(function () use ($validated) {
            $role = Role::create([
                'name' => $validated['name'],
                'guard_name' => 'web',
            ]);

            if (!empty($validated['permissions'])) {
                $permissions = Permission::whereIn('id', $validated['permissions'])->get();
                $role->syncPermissions($permissions);
            }
        });

        return redirect()
            ->route('roles.index')
            ->with('success', 'Le rôle a été créé avec succès.');
    }

    /**
     * Affichage d'un rôle
     */
    public function show(Role $role)
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(403, 'Vous devez être connecté pour accéder à cette page.');
        }
        
        // Charger explicitement les rôles
        $user->load('roles');
        
        // Vérification directe des rôles (plus fiable que hasRole)
        $userRoles = $user->roles->pluck('name')->toArray();
        $isSuperAdmin = in_array('super-admin', $userRoles);
        
        // Seul le super-admin peut voir les rôles
        if (!$isSuperAdmin) {
            $userRolesStr = implode(', ', $userRoles) ?: 'Aucun rôle';
            $message = "Accès refusé.\n\n";
            $message .= "Seul le Super-Administrateur peut voir les détails des rôles.\n\n";
            $message .= "Votre compte : {$user->email}\n";
            $message .= "Vos rôles actuels : {$userRolesStr}\n\n";
            $message .= "Pour accéder à cette page, connectez-vous avec :\n";
            $message .= "Super-Admin : super.admin@sgrs-ceeac.org / Password@2025";
            
            abort(403, $message);
        }

        $role->load(['permissions', 'users']);
        
        $allPermissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'other';
        });

        return view('roles.show', [
            'role' => $role,
            'allPermissions' => $allPermissions,
        ]);
    }

    /**
     * Formulaire d'édition d'un rôle
     */
    public function edit(Role $role)
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(403, 'Vous devez être connecté pour accéder à cette page.');
        }
        
        // Charger explicitement les rôles
        $user->load('roles');
        
        // Vérification directe des rôles (plus fiable que hasRole)
        $userRoles = $user->roles->pluck('name')->toArray();
        $isSuperAdmin = in_array('super-admin', $userRoles);
        
        // Seul le super-admin peut modifier les rôles
        if (!$isSuperAdmin) {
            $userRolesStr = implode(', ', $userRoles) ?: 'Aucun rôle';
            $message = "Accès refusé.\n\n";
            $message .= "Seul le Super-Administrateur peut modifier les rôles et permissions.\n\n";
            $message .= "Votre compte : {$user->email}\n";
            $message .= "Vos rôles actuels : {$userRolesStr}\n\n";
            $message .= "Pour accéder à cette page, connectez-vous avec :\n";
            $message .= "Super-Admin : super.admin@sgrs-ceeac.org / Password@2025";
            
            abort(403, $message);
        }

        $role->load('permissions');
        
        $allPermissions = Permission::orderBy('name')->get()->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'other';
        });

        return view('roles.edit', [
            'role' => $role,
            'allPermissions' => $allPermissions,
        ]);
    }

    /**
     * Mise à jour d'un rôle
     */
    public function update(Request $request, Role $role)
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(403, 'Vous devez être connecté pour accéder à cette page.');
        }
        
        $user->load('roles');
        $userRoles = $user->roles->pluck('name')->toArray();
        $isSuperAdmin = in_array('super-admin', $userRoles);
        
        if (!$isSuperAdmin) {
            abort(403, 'Seul le Super-Administrateur peut modifier les rôles et permissions.');
        }

        // Ne pas permettre la modification du nom des rôles système
        $systemRoles = ['super-admin', 'admin', 'sg', 'dsi', 'staff'];
        $isSystem = in_array($role->name, $systemRoles);
        
        $rules = [
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ];
        
        // Ajouter la règle de validation pour le nom seulement si ce n'est pas un rôle système
        if (!$isSystem) {
            $rules['name'] = ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id];
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($validated, $role, $isSystem) {
            // Ne mettre à jour le nom que si ce n'est pas un rôle système
            if (!$isSystem && isset($validated['name'])) {
                $role->update([
                    'name' => $validated['name'],
                ]);
            }

            if (isset($validated['permissions'])) {
                $permissions = Permission::whereIn('id', $validated['permissions'])->get();
                $role->syncPermissions($permissions);
            } else {
                $role->syncPermissions([]);
            }
        });

        return redirect()
            ->route('roles.show', $role)
            ->with('success', 'Le rôle a été mis à jour avec succès.');
    }

    /**
     * Suppression d'un rôle
     */
    public function destroy(Role $role)
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(403, 'Vous devez être connecté pour accéder à cette page.');
        }
        
        $user->load('roles');
        $userRoles = $user->roles->pluck('name')->toArray();
        $isSuperAdmin = in_array('super-admin', $userRoles);
        
        if (!$isSuperAdmin) {
            abort(403, 'Seul le Super-Administrateur peut supprimer des rôles.');
        }

        // Ne pas permettre la suppression des rôles système
        $systemRoles = ['super-admin', 'admin', 'sg', 'dsi', 'staff'];
        if (in_array($role->name, $systemRoles)) {
            return back()
                ->with('error', 'Ce rôle système ne peut pas être supprimé.');
        }

        // Vérifier qu'il n'y a pas d'utilisateurs avec ce rôle
        if ($role->users()->count() > 0) {
            return back()
                ->with('error', 'Ce rôle ne peut pas être supprimé car il est attribué à des utilisateurs.');
        }

        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('success', 'Le rôle a été supprimé avec succès.');
    }

    /**
     * Attribution d'un rôle à un utilisateur
     */
    public function assignToUser(Request $request, Role $role)
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(403, 'Vous devez être connecté pour accéder à cette page.');
        }
        
        $user->load('roles');
        $userRoles = $user->roles->pluck('name')->toArray();
        $isSuperAdmin = in_array('super-admin', $userRoles);
        
        if (!$isSuperAdmin) {
            abort(403, 'Seul le Super-Administrateur peut attribuer des rôles.');
        }

        $validated = $request->validate([
            'user_id' => ['required', 'exists:utilisateurs,id'],
        ]);

        $user = \App\Models\User::findOrFail($validated['user_id']);
        
        if (!$user->hasRole($role->name)) {
            $user->assignRole($role);
        }

        return back()
            ->with('success', "Le rôle a été attribué à l'utilisateur.");
    }

    /**
     * Retrait d'un rôle d'un utilisateur
     */
    public function removeFromUser(Request $request, Role $role)
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(403, 'Vous devez être connecté pour accéder à cette page.');
        }
        
        $user->load('roles');
        $userRoles = $user->roles->pluck('name')->toArray();
        $isSuperAdmin = in_array('super-admin', $userRoles);
        
        if (!$isSuperAdmin) {
            abort(403, 'Seul le Super-Administrateur peut retirer des rôles.');
        }

        $validated = $request->validate([
            'user_id' => ['required', 'exists:utilisateurs,id'],
        ]);

        $user = \App\Models\User::findOrFail($validated['user_id']);
        $user->removeRole($role);

        return back()
            ->with('success', "Le rôle a été retiré de l'utilisateur.");
    }
}

