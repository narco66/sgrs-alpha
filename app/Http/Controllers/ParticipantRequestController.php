<?php

namespace App\Http\Controllers;

use App\Models\ParticipantRequest;
use App\Models\Meeting;
use App\Models\MeetingParticipant;
use App\Models\Delegation;
use App\Models\DelegationMember;
use App\Models\User;
use App\Notifications\ParticipantRequestStatusUpdatedNotification;
use App\Notifications\ParticipantRequestSubmittedNotification;
use App\Services\AuditLogger;
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

        // Notifications aux rôles de validation (DSI, SG, Admin, Super Admin)
        $validators = User::role(['dsi', 'sg', 'admin', 'super-admin'])->get();
        foreach ($validators as $user) {
            $user->notify(new ParticipantRequestSubmittedNotification($participantRequest));
        }

        // Audit
        AuditLogger::log(
            event: 'participant_request_created',
            target: $participantRequest,
            old: null,
            new: $participantRequest->getAttributes(),
            meta: []
        );

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
        $this->authorize('approve', $participantRequest);

        $validated = $request->validate([
            'review_comments' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $participantRequest) {
            // Retrouver la réunion
            $meeting = $participantRequest->meeting;

            // Créer (ou retrouver) une délégation "Invités individuels" pour cette réunion
            $delegation = Delegation::firstOrCreate(
                [
                    'meeting_id'  => $participantRequest->meeting_id,
                    'title'       => 'Invités individuels',
                    'entity_type' => Delegation::ENTITY_TYPE_OTHER,
                ],
                [
                    'country'               => null,
                    'organization_name'     => null,
                    'is_active'             => true,
                    'participation_status'  => Delegation::STATUS_CONFIRMED,
                    'confirmed_at'          => now(),
                ]
            );

            // Créer le membre de délégation correspondant au participant demandé
            $member = DelegationMember::create([
                'delegation_id' => $delegation->id,
                'first_name'    => $participantRequest->participant_name,
                'last_name'     => null,
                'email'         => $participantRequest->participant_email,
                'position'      => $participantRequest->participant_role,
                'role'          => DelegationMember::ROLE_MEMBER,
                'status'        => DelegationMember::STATUS_INVITED,
            ]);

            // Mettre à jour la demande
            $participantRequest->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'review_comments' => $validated['review_comments'] ?? null,
                // On conserve l'ID du membre créé pour traçabilité
                'delegation_member_id' => $member->id,
            ]);

            // Notification au demandeur
            if ($participantRequest->requester) {
                $participantRequest->requester->notify(new ParticipantRequestStatusUpdatedNotification($participantRequest));
            }

            // Audit
            AuditLogger::log(
                event: 'participant_request_approved',
                target: $participantRequest,
                old: null,
                new: $participantRequest->getAttributes(),
                meta: ['participant_id' => $participant->id]
            );
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
        $this->authorize('reject', $participantRequest);

        $validated = $request->validate([
            'review_comments' => ['required', 'string', 'min:10'],
        ]);

        $participantRequest->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'review_comments' => $validated['review_comments'],
        ]);

        // Notification au demandeur
        if ($participantRequest->requester) {
            $participantRequest->requester->notify(new ParticipantRequestStatusUpdatedNotification($participantRequest));
        }

        AuditLogger::log(
            event: 'participant_request_rejected',
            target: $participantRequest,
            old: null,
            new: $participantRequest->getAttributes(),
            meta: []
        );

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

        AuditLogger::log(
            event: 'participant_request_deleted',
            target: $participantRequest,
            old: $participantRequest->getOriginal(),
            new: null,
            meta: []
        );

        return redirect()
            ->route('participant-requests.index')
            ->with('success', 'La demande a été supprimée.');
    }
}
