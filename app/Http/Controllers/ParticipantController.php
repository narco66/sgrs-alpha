<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParticipantRequest;
use App\Models\Meeting;
use App\Models\Participant;
use App\Models\DelegationMember;
use App\Models\User;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Participant::class, 'participant');
    }

    public function index(Request $request)
    {
        $search    = $request->get('q');
        $meetingId = $request->get('meeting_id');
        $status    = $request->get('status'); // invited|confirmed|present|absent|excused|all

        // Nouvelle logique : liste des personnes physiques via les membres de délégation
        // Un "participant" = un DelegationMember rattaché à une réunion via sa délégation.
        $participantsQuery = DelegationMember::with(['delegation.meeting']);

        // Filtre réunion
        if ($meetingId) {
            $participantsQuery->whereHas('delegation', function ($q) use ($meetingId) {
                $q->where('meeting_id', $meetingId);
            });
        }

        // Filtre statut individuel (invited, confirmed, present, absent, excused)
        if ($status && $status !== 'all') {
            $participantsQuery->where('status', $status);
        }

        // Recherche texte : nom, email, institution, pays, délégation
        if ($search) {
            $participantsQuery->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('institution', 'like', "%{$search}%")
                    ->orWhereHas('delegation', function ($dq) use ($search) {
                        $dq->where('title', 'like', "%{$search}%")
                           ->orWhere('country', 'like', "%{$search}%")
                           ->orWhere('organization_name', 'like', "%{$search}%");
                    });
            });
        }

        $participants = $participantsQuery
            ->orderByRaw("CASE WHEN role = 'head' THEN 0 ELSE 1 END")
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(15)
            ->withQueryString();

        $meetings = Meeting::orderByDesc('start_at')->take(50)->get();

        return view('participants.index', compact('participants', 'meetings', 'search', 'meetingId', 'status'));
    }

    public function create()
    {
        return view('participants.create');
    }

    public function store(ParticipantRequest $request)
    {
        Participant::create($request->validated());

        return redirect()
            ->route('participants.index')
            ->with('success', 'Le participant a ete cree avec succes.');
    }

    public function show(Participant $participant)
    {
        return view('participants.show', compact('participant'));
    }

    public function edit(Participant $participant)
    {
        return view('participants.edit', compact('participant'));
    }

    public function update(ParticipantRequest $request, Participant $participant)
    {
        $participant->update($request->validated());

        return redirect()
            ->route('participants.index')
            ->with('success', 'Le participant a ete mis a jour avec succes.');
    }

    public function destroy(Participant $participant)
    {
        $participant->delete();

        return redirect()
            ->route('participants.index')
            ->with('success', 'Le participant a ete supprime.');
    }
}
