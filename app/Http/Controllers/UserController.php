<?php

namespace App\Http\Controllers;

use App\Events\UserCreated;
use App\Events\UserUpdated;
use App\Events\UserApproved;
use App\Events\UserRejected;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserStatusRequest;
use App\Models\Delegation;
use App\Models\User;
use App\Models\AuditLog;
use App\Services\AuditLogger;
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
        $status = $request->get('status');

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
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->orderBy('name');

        // Les administrateurs voient tous les utilisateurs, les autres voient seulement les actifs
        if (!auth()->user()->hasAnyRole(['super-admin', 'admin', 'dsi'])) {
            $query->where('is_active', true);
        }

        $users = $query->paginate(15)->withQueryString();

        $delegations = Delegation::active()->orderBy('title')->get();
        $services = User::whereNotNull('service')->distinct()->pluck('service')->sort();
        $allRoles = Role::orderBy('name')->get();

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
            'status', 
            'delegations', 
            'services',
            'allRoles',
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
            // Création back-office : on considère le compte comme actif par défaut,
            // sauf indication contraire explicite.
            'is_active' => array_key_exists('is_active', $data) ? (bool) $data['is_active'] : true,
            'status' => $data['status'] ?? ((array_key_exists('is_active', $data) ? (bool) $data['is_active'] : true) ? 'active' : 'inactive'),
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

        // EF40 / Notifications : création d'utilisateur (alerte interne + email)
        event(new UserCreated($user, $request->user()));

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
        
        // Historique des changements de statut depuis la table journaux_audit
        $statusLogs = AuditLog::where('auditable_type', User::class)
            ->where('auditable_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get()
            ->filter(function (AuditLog $log) {
                $old = $log->old_values ?? [];
                $new = $log->new_values ?? [];

                $hasStatusChange = array_key_exists('status', $old) || array_key_exists('status', $new);
                $isStatusEvent = in_array($log->event, [
                    'user_registration_requested',
                    'user_account_approved',
                    'user_account_rejected',
                ], true);

                return $hasStatusChange || $isStatusEvent;
            });

        return view('users.show', compact('user', 'statusLogs'));
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

        if (isset($data['status'])) {
            $updateData['status'] = $data['status'];
            if ($data['status'] === 'active') {
                $updateData['is_active'] = true;
            } elseif (in_array($data['status'], ['inactive', 'rejected', 'pending'], true)) {
                $updateData['is_active'] = false;
            }
        }

        // Seul l'utilisateur lui-même peut modifier son mot de passe via ce formulaire
        if ($request->user()->id === $user->id && !empty($data['password'])) {
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

        // EF40 / Notifications : mise à jour d'utilisateur (alerte à l'intéressé)
        event(new UserUpdated($user, $request->user()));

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

        $newIsActive = !$user->is_active;

        $user->update([
            'is_active' => $newIsActive,
            // On distingue "inactive" (désactivation administrative)
            // de "rejected" (rejet de demande initiale).
            'status' => $newIsActive ? 'active' : 'inactive',
        ]);

        $status = $user->is_active ? 'activé' : 'désactivé';

        return redirect()
            ->route('users.show', $user)
            ->with('success', "Le compte utilisateur a été {$status} avec succès.");
    }

    /**
     * Validation explicite d'un compte utilisateur par un administrateur.
     * - Active le compte
     * - Optionnellement, attribue un rôle par défaut si aucun rôle n'est présent
     * - Déclenche les notifications et la journalisation via événement métier
     */
    public function approve(User $user)
    {
        $this->authorize('toggleActive', $user);

        if ($user->is_active && $user->status === 'active') {
            return redirect()
                ->route('users.show', $user)
                ->with('info', 'Ce compte est déjà actif.');
        }

        $user->update([
            'is_active'         => true,
            'status'            => 'active',
            'email_verified_at' => $user->email_verified_at ?? now(),
        ]);

        // Si aucun rôle n'est affecté, on peut attribuer un rôle métier minimal par défaut
        if ($user->roles()->count() === 0) {
            $defaultRole = Role::where('name', 'user')->first();
            if ($defaultRole) {
                $user->assignRole($defaultRole);
            }
        }

        event(new UserApproved($user, auth()->user()));

        return redirect()
            ->route('users.show', $user)
            ->with('success', 'Le compte utilisateur a été validé et activé avec succès.');
    }

    /**
     * Rejet explicite d'un compte utilisateur par un administrateur.
     * - Le compte reste / devient inactif
     * - Un motif facultatif peut être saisi
     * - Déclenche les notifications et la journalisation via événement métier
     */
    public function reject(Request $request, User $user)
    {
        $this->authorize('toggleActive', $user);

        $reason = $request->input('reason');

        $user->update([
            'is_active' => false,
            'status'    => 'rejected',
        ]);

        event(new UserRejected($user, auth()->user(), $reason));

        return redirect()
            ->route('users.show', $user)
            ->with('success', 'Le compte utilisateur a été rejeté. L\'utilisateur ne pourra pas se connecter.');
    }

    /**
     * Mise à jour rapide du statut + des rôles via la liste (double-clic).
     *
     * Cette action est réservée aux administrateurs (super-admin, admin, dsi, users.manage).
     * Retourne une réponse JSON pour permettre une mise à jour dynamique de la ligne.
     */
    public function quickUpdateStatusAndRoles(UpdateUserStatusRequest $request, User $user)
    {
        $data = $request->validated();

        $newStatus = $data['status'];

        $updateData = [
            'status'    => $newStatus,
            'is_active' => $newStatus === 'active',
        ];

        $oldValues = $user->only(['status', 'is_active']);

        $user->update($updateData);

        // Mise à jour des rôles si fournis
        if (isset($data['roles'])) {
            $roles = Role::whereIn('id', $data['roles'])->get();
            $user->syncRoles($roles);
        }

        // Audit dédié pour cette action rapide
        AuditLogger::log(
            event: 'user_quick_status_update',
            target: $user,
            old: $oldValues,
            new: $updateData,
            meta: [
                'actor_id' => $request->user()->id,
                'roles'    => $user->roles()->pluck('name')->toArray(),
            ]
        );

        // Rafraîchir les rôles pour la réponse
        $user->load('roles');
        $primaryRole = $user->roles->first();

        // Réponse adaptée : JSON pour les appels AJAX, redirection sinon.
        if ($request->wantsJson()) {
            return response()->json([
                'status'       => 'success',
                'user_id'      => $user->id,
                'new_status'   => $user->status,
                'is_active'    => $user->is_active,
                'roles'        => $user->roles->pluck('name')->toArray(),
                'primary_role' => $primaryRole?->name,
            ]);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'Le statut et les rôles de l\'utilisateur ont été mis à jour.');
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

