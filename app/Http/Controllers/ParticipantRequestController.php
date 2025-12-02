<?php

namespace App\Http\Controllers;

use App\Models\ParticipantRequest;
use App\Models\Meeting;
use App\Models\MeetingParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParticipantRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Liste des demandes d'ajout de participants
     * UC37 - Envoyer une demande d'ajout de participant
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $meetingId = $request->get('meeting_id');

        $query = ParticipantRequest::with(['meeting', 'requester', 'reviewer', 'participant'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($meetingId, fn($q) => $q->where('meeting_id', $meetingId));

        // Les utilisateurs normaux voient seulement leurs demandes
        if (!Auth::user()->hasAnyRole(['super-admin', 'admin', 'dsi', 'chef-departement'])) {
            $query->where('requested_by', Auth::id());
        }

        $requests = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('participant-requests.index', compact('requests', 'status', 'meetingId'));
    }

    /**
     * Formulaire de création d'une demande
     */
    public function create(Request $request)
    {
        $meetingId = $request->get('meeting_id');
        $meeting = $meetingId ? Meeting::findOrFail($meetingId) : null;
        $meetings = Meeting::where('status', '!=', 'terminee')
            ->orderBy('start_at', 'desc')
            ->get();

        return view('participant-requests.create', compact('meeting', 'meetings'));
    }

    /**
     * Enregistrement d'une demande
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'meeting_id' => ['required', 'exists:reunions,id'],
            'participant_name' => ['required', 'string', 'max:255'],
            'participant_email' => ['nullable', 'email', 'max:255'],
            'participant_role' => ['nullable', 'string', 'max:255'],
            'justification' => ['required', 'string', 'min:10'],
        ]);

        $participantRequest = ParticipantRequest::create([
            'meeting_id' => $validated['meeting_id'],
            'participant_name' => $validated['participant_name'],
            'participant_email' => $validated['participant_email'] ?? null,
            'participant_role' => $validated['participant_role'] ?? null,
            'justification' => $validated['justification'],
            'requested_by' => Auth::id(),
            'status' => 'pending',
        ]);

        // Notification au chef de département (à implémenter)

        return redirect()
            ->route('participant-requests.show', $participantRequest)
            ->with('success', 'Votre demande d\'ajout de participant a été soumise avec succès.');
    }

    /**
     * Affichage d'une demande
     */
    public function show(ParticipantRequest $participantRequest)
    {
        $this->authorize('view', $participantRequest);

        $participantRequest->load(['meeting', 'requester', 'reviewer', 'participant']);

        return view('participant-requests.show', compact('participantRequest'));
    }

    /**
     * Approbation d'une demande (UC38)
     */
    public function approve(Request $request, ParticipantRequest $participantRequest)
    {
        $this->authorize('update', $participantRequest);

        $validated = $request->validate([
            'review_comments' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $participantRequest) {
            // Créer le participant
            $participant = MeetingParticipant::create([
                'meeting_id' => $participantRequest->meeting_id,
                'user_id' => null, // Participant externe
                'name' => $participantRequest->participant_name,
                'email' => $participantRequest->participant_email,
                'role' => $participantRequest->participant_role ?? 'invite',
                'status' => 'pending',
            ]);

            // Mettre à jour la demande
            $participantRequest->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'review_comments' => $validated['review_comments'] ?? null,
                'participant_id' => $participant->id,
            ]);

            // Envoyer l'invitation (à implémenter)
        });

        return redirect()
            ->route('participant-requests.show', $participantRequest)
            ->with('success', 'La demande a été approuvée et le participant a été ajouté.');
    }

    /**
     * Rejet d'une demande
     */
    public function reject(Request $request, ParticipantRequest $participantRequest)
    {
        $this->authorize('update', $participantRequest);

        $validated = $request->validate([
            'review_comments' => ['required', 'string', 'min:10'],
        ]);

        $participantRequest->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'review_comments' => $validated['review_comments'],
        ]);

        // Notification au demandeur (à implémenter)

        return redirect()
            ->route('participant-requests.show', $participantRequest)
            ->with('success', 'La demande a été rejetée.');
    }

    /**
     * Suppression d'une demande
     */
    public function destroy(ParticipantRequest $participantRequest)
    {
        $this->authorize('delete', $participantRequest);

        if ($participantRequest->status !== 'pending') {
            return back()->with('error', 'Seules les demandes en attente peuvent être supprimées.');
        }

        $participantRequest->delete();

        return redirect()
            ->route('participant-requests.index')
            ->with('success', 'La demande a été supprimée.');
    }
}
