<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Delegation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Liste des utilisateurs avec recherche et filtres
     * EF06 - Consultation du profil utilisateur
     * EF07 - Recherche d'utilisateur
     */
    public function index(Request $request)
    {
        $search = $request->get('q');
        $service = $request->get('service');
        $delegationId = $request->get('delegation_id');
        $isActive = $request->get('is_active');

        $query = User::with(['delegation', 'roles'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('service', 'like', "%{$search}%");
                });
            })
            ->when($service, fn($q) => $q->where('service', $service))
            ->when($delegationId, fn($q) => $q->where('delegation_id', $delegationId))
            ->when($isActive !== null, fn($q) => $q->where('is_active', $isActive))
            ->orderBy('created_at', 'desc')
            ->orderBy('name');

        // Les administrateurs voient tous les utilisateurs, les autres voient seulement les actifs
        if (!auth()->user()->hasAnyRole(['super-admin', 'admin', 'dsi'])) {
            $query->where('is_active', true);
        }

        $users = $query->paginate(15)->withQueryString();

        $delegations = Delegation::active()->orderBy('title')->get();
        $services = User::whereNotNull('service')->distinct()->pluck('service')->sort();

        // Statistiques globales (tous les utilisateurs, pas seulement la page actuelle)
        $statsQuery = User::query();
        if (!auth()->user()->hasAnyRole(['super-admin', 'admin', 'dsi'])) {
            $statsQuery->where('is_active', true);
        }
        $totalUsers = $statsQuery->count();
        $activeUsers = (clone $statsQuery)->where('is_active', true)->count();
        $inactiveUsers = (clone $statsQuery)->where('is_active', false)->count();
        $totalRoles = Role::count();

        return view('users.index', compact(
            'users', 
            'search', 
            'service', 
            'delegationId', 
            'isActive', 
            'delegations', 
            'services',
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'totalRoles'
        ));
    }

    /**
     * Formulaire de création d'un utilisateur
     * EF03 - Création d'un utilisateur
     */
    public function create()
    {
        $delegations = Delegation::active()->orderBy('title')->get();
        $roles = Role::orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();

        return view('users.create', [
            'user' => new User(),
            'delegations' => $delegations,
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Enregistrement d'un nouvel utilisateur
     * EF03 - Création d'un utilisateur
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'delegation_id' => $data['delegation_id'] ?? null,
            'service' => $data['service'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'email_verified_at' => $data['email_verified_at'] ?? null,
        ]);

        $canManageRoles = $request->user()->hasAnyRole(['super-admin', 'admin', 'dsi'])
            || $request->user()->hasPermissionTo('users.manage');

        if ($canManageRoles) {
            $roles = !empty($data['roles'])
                ? Role::whereIn('id', $data['roles'])->get()
                : collect();
            $permissions = !empty($data['permissions'])
                ? Permission::whereIn('id', $data['permissions'])->get()
                : collect();

            // Utiliser sync pour éviter les erreurs de nom/guard
            $user->syncRoles($roles);
            $user->syncPermissions($permissions);
        }

        return redirect()
            ->route('users.show', $user)
            ->with('success', 'L\'utilisateur a été créé avec succès.');
    }

    /**
     * Affichage détaillé d'un utilisateur
     * EF06 - Consultation du profil utilisateur
     */
    public function show(User $user)
    {
        $user->load([
            'delegation',
            'roles',
            'organizedMeetings',
            // Ignore participations whose meeting has been deleted to avoid null routes
            'meetingParticipations' => fn ($query) => $query->whereHas('meeting'),
            'meetingParticipations.meeting',
        ]);

        return view('users.show', compact('user'));
    }

    /**
     * Formulaire d'édition d'un utilisateur
     * EF04 - Modification des informations d'un utilisateur
     */
    public function edit(User $user)
    {
        $delegations = Delegation::active()->orderBy('title')->get();
        $roles = Role::orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();

        return view('users.edit', compact('user', 'delegations', 'roles', 'permissions'));
    }

    /**
     * Mise à jour d'un utilisateur
     * EF04 - Modification des informations d'un utilisateur
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        $updateData = [
            'name' => $data['name'],
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'email' => $data['email'],
            'delegation_id' => $data['delegation_id'] ?? null,
            'service' => $data['service'] ?? null,
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        $canManageRoles = $request->user()->hasAnyRole(['super-admin', 'admin', 'dsi'])
            || $request->user()->hasPermissionTo('users.manage');

        if ($canManageRoles) {
            $roles = isset($data['roles'])
                ? Role::whereIn('id', $data['roles'])->get()
                : collect();
            $permissions = isset($data['permissions'])
                ? Permission::whereIn('id', $data['permissions'])->get()
                : collect();

            $user->syncRoles($roles);
            $user->syncPermissions($permissions);
        }

        return redirect()
            ->route('users.show', $user)
            ->with('success', 'L\'utilisateur a été mis à jour avec succès.');
    }

    /**
     * Activation ou désactivation d'un compte utilisateur
     * EF05 - Activation ou désactivation d'un compte utilisateur
     */
    public function toggleActive(User $user)
    {
        $this->authorize('update', $user);

        $user->update([
            'is_active' => !$user->is_active,
        ]);

        $status = $user->is_active ? 'activé' : 'désactivé';

        return redirect()
            ->route('users.show', $user)
            ->with('success', "Le compte utilisateur a été {$status} avec succès.");
    }

    /**
     * Suppression d'un utilisateur
     */
    public function destroy(User $user)
    {
        // Empêcher la suppression de soi-même
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'L\'utilisateur a été supprimé avec succès.');
    }
}

