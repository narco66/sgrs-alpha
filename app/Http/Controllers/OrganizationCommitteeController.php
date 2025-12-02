<?php

namespace App\Http\Controllers;

use App\Models\OrganizationCommittee;
use App\Models\Meeting;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrganizationCommitteeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Liste des comités d'organisation
     * EF20 - Assignation d'un comité d'organisation
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', OrganizationCommittee::class);

        $search = $request->get('q');
        $meetingId = $request->get('meeting_id');

        $committees = OrganizationCommittee::with(['meeting', 'creator', 'members.user'])
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($meetingId, fn($q) => $q->where('meeting_id', $meetingId))
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('organization-committees.index', compact('committees', 'search', 'meetingId'));
    }

    /**
     * Formulaire de création
     */
    public function create(Request $request)
    {
        $this->authorize('create', OrganizationCommittee::class);

        $meetingId = $request->get('meeting_id');
        $meeting = $meetingId ? Meeting::findOrFail($meetingId) : null;
        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('organization-committees.create', compact('meeting', 'users'));
    }

    /**
     * Enregistrement d'un nouveau comité
     */
    public function store(Request $request)
    {
        $this->authorize('create', OrganizationCommittee::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'meeting_id' => ['nullable', 'exists:reunions,id'],
            'members' => ['nullable', 'array'],
            'members.*.user_id' => ['required', 'exists:utilisateurs,id', 'distinct'],
            'members.*.role' => ['required', 'string', 'max:255'],
            'members.*.notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {
            $committee = OrganizationCommittee::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'meeting_id' => $validated['meeting_id'] ?? null,
                'created_by' => Auth::id(),
                'is_active' => true,
            ]);

            if (isset($validated['members'])) {
                $members = collect($validated['members'])
                    ->filter(fn ($member) => !empty($member['user_id']))
                    ->unique('user_id')
                    ->values();

                foreach ($members as $member) {
                    $committee->members()->create([
                        'user_id' => $member['user_id'],
                        'role' => $member['role'],
                        'notes' => $member['notes'] ?? null,
                    ]);
                }
            }
        });

        return redirect()
            ->route('organization-committees.index')
            ->with('success', 'Le comité d\'organisation a été créé avec succès.');
    }

    /**
     * Affichage d'un comité
     */
    public function show(OrganizationCommittee $organizationCommittee)
    {
        $this->authorize('view', $organizationCommittee);

        $organizationCommittee->load(['meeting', 'creator', 'members.user']);

        return view('organization-committees.show', compact('organizationCommittee'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(OrganizationCommittee $organizationCommittee)
    {
        $this->authorize('update', $organizationCommittee);

        $organizationCommittee->load('members.user');
        $meetings = Meeting::orderBy('start_at', 'desc')->get();
        $users = User::where('is_active', true)->orderBy('name')->get();

        return view('organization-committees.edit', compact('organizationCommittee', 'meetings', 'users'));
    }

    /**
     * Mise à jour d'un comité
     */
    public function update(Request $request, OrganizationCommittee $organizationCommittee)
    {
        $this->authorize('update', $organizationCommittee);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'meeting_id' => ['nullable', 'exists:reunions,id'],
            'is_active' => ['boolean'],
            'members' => ['nullable', 'array'],
            'members.*.user_id' => ['required', 'exists:utilisateurs,id', 'distinct'],
            'members.*.role' => ['required', 'string', 'max:255'],
            'members.*.notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $organizationCommittee) {
            $organizationCommittee->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'meeting_id' => $validated['meeting_id'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            // Supprimer les anciens membres
            $organizationCommittee->members()->delete();

            // Ajouter les nouveaux membres
            if (isset($validated['members'])) {
                $members = collect($validated['members'])
                    ->filter(fn ($member) => !empty($member['user_id']))
                    ->unique('user_id')
                    ->values();

                foreach ($members as $member) {
                    $organizationCommittee->members()->create([
                        'user_id' => $member['user_id'],
                        'role' => $member['role'],
                        'notes' => $member['notes'] ?? null,
                    ]);
                }
            }
        });

        return redirect()
            ->route('organization-committees.show', $organizationCommittee)
            ->with('success', 'Le comité d\'organisation a été mis à jour avec succès.');
    }

    /**
     * Suppression d'un comité
     */
    public function destroy(OrganizationCommittee $organizationCommittee)
    {
        $this->authorize('delete', $organizationCommittee);

        $organizationCommittee->delete();

        return redirect()
            ->route('organization-committees.index')
            ->with('success', 'Le comité d\'organisation a été supprimé avec succès.');
    }

    /**
     * Export PDF : composition du comité
     */
    public function exportPdf(OrganizationCommittee $organizationCommittee)
    {
        $this->authorize('view', $organizationCommittee);

        $organizationCommittee->load(['meeting', 'creator', 'members.user']);

        $pdf = Pdf::loadView('organization-committees.pdf', [
            'committee' => $organizationCommittee,
        ])->setPaper('A4', 'portrait');

        $fileName = 'comite-organisation-' . $organizationCommittee->id . '.pdf';

        return $pdf->download($fileName);
    }
}
