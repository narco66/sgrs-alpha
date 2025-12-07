<?php

namespace App\Http\Controllers;

use App\Models\Delegation;
use App\Models\DelegationMember;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

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
    public function exportBadgePdf(Delegation $delegation, DelegationMember $member)
    {
        $this->authorize('view', $delegation);

        if ($member->delegation_id !== $delegation->id) {
            abort(404);
        }

        $delegation->load('meeting');

        $pdf = Pdf::loadView('participants.pdf-badge', [
            'participant' => $member,
            'delegation' => $delegation,
            'meeting' => $delegation->meeting,
        ])->setPaper([0, 0, 241, 153], 'landscape'); // Format badge 85mm x 54mm

        $fileName = 'badge-' . ($member->last_name ?? $member->id) . '.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Export de tous les badges d'une délégation en PDF.
     */
    public function exportAllBadgesPdf(Delegation $delegation)
    {
        $this->authorize('view', $delegation);

        $delegation->load(['members', 'meeting']);

        $html = '';
        foreach ($delegation->members as $member) {
            $html .= view('participants.pdf-badge', [
                'participant' => $member,
                'delegation' => $delegation,
                'meeting' => $delegation->meeting,
            ])->render();
            $html .= '<div style="page-break-after: always;"></div>';
        }

        $pdf = Pdf::loadHTML($html)->setPaper([0, 0, 241, 153], 'landscape');

        $fileName = 'badges-delegation-' . $delegation->id . '.pdf';

        return $pdf->download($fileName);
    }
}










