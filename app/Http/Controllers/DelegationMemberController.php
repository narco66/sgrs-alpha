<?php

namespace App\Http\Controllers;

use App\Models\Delegation;
use App\Models\DelegationMember;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\BadgeGeneratorService;
use Illuminate\Support\Str;

/**
 * Contrôleur pour la gestion des membres de délégation
 */
class DelegationMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Liste des membres d'une délégation
     */
    public function index(Delegation $delegation)
    {
        $this->authorize('view', $delegation);

        $members = $delegation->members()
            ->orderByRaw("CASE WHEN role = 'head' THEN 0 ELSE 1 END")
            ->orderBy('last_name')
            ->get();

        return view('delegation-members.index', [
            'delegation' => $delegation,
            'members' => $members,
        ]);
    }

    /**
     * Formulaire d'ajout d'un membre à une délégation
     */
    public function create(Delegation $delegation)
    {
        $this->authorize('update', $delegation);

        return view('delegation-members.create', [
            'delegation' => $delegation,
            'member' => new DelegationMember(),
        ]);
    }

    /**
     * Enregistrement d'un nouveau membre
     */
    public function store(Request $request, Delegation $delegation)
    {
        $this->authorize('update', $delegation);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'phone' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'institution' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:' . implode(',', DelegationMember::roles())],
            'status' => ['nullable', 'string', 'in:' . implode(',', DelegationMember::statuses())],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['delegation_id'] = $delegation->id;
        $validated['status'] = $validated['status'] ?? DelegationMember::STATUS_INVITED;
        $validated['badge_uuid'] = (string) Str::uuid();

        // Upload de la photo si fournie
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('delegation_members', 'public');
            $validated['photo_path'] = $path;
        }

        // Si c'est le chef de délégation, s'assurer qu'il n'y en a qu'un
        if ($validated['role'] === DelegationMember::ROLE_HEAD) {
            $delegation->members()->where('role', DelegationMember::ROLE_HEAD)->update([
                'role' => DelegationMember::ROLE_MEMBER
            ]);
        }

        DelegationMember::create($validated);

        return redirect()
            ->route('delegations.show', $delegation)
            ->with('success', 'Le membre a été ajouté à la délégation avec succès.');
    }

    /**
     * Formulaire d'édition d'un membre
     */
    public function edit(Delegation $delegation, DelegationMember $member)
    {
        $this->authorize('update', $delegation);

        if ($member->delegation_id !== $delegation->id) {
            abort(404);
        }

        return view('delegation-members.edit', [
            'delegation' => $delegation,
            'member' => $member,
        ]);
    }

    /**
     * Mise à jour d'un membre
     */
    public function update(Request $request, Delegation $delegation, DelegationMember $member)
    {
        $this->authorize('update', $delegation);

        if ($member->delegation_id !== $delegation->id) {
            abort(404);
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'phone' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'institution' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:' . implode(',', DelegationMember::roles())],
            'status' => ['nullable', 'string', 'in:' . implode(',', DelegationMember::statuses())],
            'notes' => ['nullable', 'string'],
        ]);

        // Si on change le rôle en chef de délégation, s'assurer qu'il n'y en a qu'un
        if ($validated['role'] === DelegationMember::ROLE_HEAD && $member->role !== DelegationMember::ROLE_HEAD) {
            $delegation->members()->where('role', DelegationMember::ROLE_HEAD)
                ->where('id', '!=', $member->id)
                ->update(['role' => DelegationMember::ROLE_MEMBER]);
        }

        // Gestion de la photo
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si présente
            if ($member->photo_path) {
                Storage::disk('public')->delete($member->photo_path);
            }

            $path = $request->file('photo')->store('delegation_members', 'public');
            $validated['photo_path'] = $path;
        }

        $member->update($validated);

        return redirect()
            ->route('delegations.show', $delegation)
            ->with('success', 'Le membre a été mis à jour avec succès.');
    }

    /**
     * Suppression d'un membre
     */
    public function destroy(Delegation $delegation, DelegationMember $member)
    {
        $this->authorize('update', $delegation);

        if ($member->delegation_id !== $delegation->id) {
            abort(404);
        }

        $member->delete();

        // Supprimer la photo associée s'il y en a une
        if ($member->photo_path) {
            Storage::disk('public')->delete($member->photo_path);
        }

        return redirect()
            ->route('delegations.show', $delegation)
            ->with('success', 'Le membre a été retiré de la délégation.');
    }

    /**
     * Mise à jour du statut d'un membre
     */
    public function updateStatus(Request $request, Delegation $delegation, DelegationMember $member)
    {
        $this->authorize('update', $delegation);

        if ($member->delegation_id !== $delegation->id) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', DelegationMember::statuses())],
        ]);

        $updateData = ['status' => $validated['status']];
        
        if ($validated['status'] === DelegationMember::STATUS_CONFIRMED && !$member->confirmed_at) {
            $updateData['confirmed_at'] = now();
        }
        
        if ($validated['status'] === DelegationMember::STATUS_PRESENT && !$member->checked_in_at) {
            $updateData['checked_in_at'] = now();
        }

        $member->update($updateData);

        return back()->with('success', 'Le statut du membre a été mis à jour.');
    }

    /**
     * Export du badge d'un membre en PDF.
     */
    public function exportBadgePdf(Delegation $delegation, DelegationMember $member, BadgeGeneratorService $badgeGenerator)
    {
        $this->authorize('view', $delegation);

        if ($member->delegation_id !== $delegation->id) {
            abort(404);
        }

        $delegation->load('meeting');

        $fileName = 'badge-' . (($member->last_name ?? '') ?: $member->id) . '.pdf';

        return $badgeGenerator->downloadForParticipant(
            $member,
            $delegation->meeting,
            $delegation,
            $fileName
        );
    }

    /**
     * Export de tous les badges d'une délégation en PDF.
     * Inclut le Chef de Délégation (si renseigné dans les champs de la délégation)
     * et tous les membres enregistrés.
     */
    public function exportAllBadgesPdf(Delegation $delegation, BadgeGeneratorService $badgeGenerator)
    {
        $this->authorize('view', $delegation);

        $delegation->load(['members' => function($query) {
            $query->orderByRaw("CASE WHEN role = 'head' THEN 0 ELSE 1 END")
                  ->orderBy('last_name');
        }, 'meeting']);

        // Construire la liste des participants pour les badges
        $participants = collect();

        // 1. Ajouter le Chef de Délégation depuis les champs de la délégation (si renseigné et pas déjà dans les membres)
        if (!empty($delegation->head_of_delegation_name)) {
            // Vérifier si ce chef n'est pas déjà dans les membres
            $headAlreadyInMembers = $delegation->members->contains(function($member) use ($delegation) {
                return $member->role === 'head' && 
                       strtolower(trim($member->first_name . ' ' . $member->last_name)) === strtolower(trim($delegation->head_of_delegation_name));
            });

            if (!$headAlreadyInMembers) {
                // Créer un objet pseudo-membre pour le chef de délégation
                $headOfDelegation = new \stdClass();
                $headOfDelegation->first_name = '';
                $headOfDelegation->last_name = $delegation->head_of_delegation_name;
                $headOfDelegation->full_name = $delegation->head_of_delegation_name;
                $headOfDelegation->position = $delegation->head_of_delegation_position;
                $headOfDelegation->email = $delegation->head_of_delegation_email;
                $headOfDelegation->title = '';
                $headOfDelegation->role = 'head';
                $headOfDelegation->status = $delegation->participation_status ?? 'confirmed';
                // Utiliser la photo du chef définie au niveau de la délégation si disponible
                $headOfDelegation->photo_path = $delegation->head_of_delegation_photo_path ?? null;
                
                $participants->push($headOfDelegation);
            }
        }

        // 2. Ajouter tous les membres de la délégation
        foreach ($delegation->members as $member) {
            $participants->push($member);
        }

        // Si aucun participant, retourner une erreur
        if ($participants->isEmpty()) {
            return back()->with('error', 'Aucun membre à inclure dans les badges.');
        }

        $fileName = 'badges-delegation-' . ($delegation->country ?? $delegation->id) . '.pdf';

        return $badgeGenerator->downloadForParticipants(
            $participants,
            $delegation->meeting,
            $delegation,
            $fileName
        );
    }
}










