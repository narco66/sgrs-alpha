<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParticipantRequest;
use App\Models\Meeting;
use App\Models\Participant;
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
        $status    = $request->get('status'); // active|inactive|all

        // Liste les utilisateurs qui participent au moins a une reunion (participants_reunions)
        $participants = User::whereHas('meetingParticipations')
            ->with([
                'meetingParticipations' => function ($q) use ($meetingId) {
                    $q->with('meeting')
                        ->when($meetingId, fn ($m) => $m->where('meeting_id', $meetingId));
                },
            ])
            ->withCount([
                'meetingParticipations as meetings_count' => function ($q) use ($meetingId) {
                    if ($meetingId) {
                        $q->where('meeting_id', $meetingId);
                    }
                },
            ])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('service', 'like', "%{$search}%");
                });
            })
            ->when($meetingId, fn ($q) => $q->whereHas('meetingParticipations', fn ($m) => $m->where('meeting_id', $meetingId)))
            ->when($status === 'active', fn ($q) => $q->where('is_active', true))
            ->when($status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->orderBy('name')
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
