<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParticipantRequest;
use App\Models\Participant;
use App\Models\Meeting;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Participant::class, 'participant');
    }

    public function index(Request $request)
    {
        $search      = $request->get('q');
        $meetingId   = $request->get('meeting_id');
        $status      = $request->get('status'); // active|inactive|all
        $type        = $request->get('type');   // internal|external|all

        $participants = Participant::withCount('meetings')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('institution', 'like', "%{$search}%");
                });
            })
            // Table des réunions renommée en 'reunions', on cible cet alias pour le filtre
            ->when($meetingId, fn ($q) => $q->whereHas('meetings', fn ($m) => $m->where('reunions.id', $meetingId)))
            ->when($status === 'active', fn ($q) => $q->where('is_active', true))
            ->when($status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->when($type === 'internal', fn ($q) => $q->where('is_internal', true))
            ->when($type === 'external', fn ($q) => $q->where('is_internal', false))
            ->orderBy('created_at', 'desc')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(15)
            ->withQueryString();

        $meetings = Meeting::orderByDesc('start_at')->take(50)->get();

        return view('participants.index', compact('participants', 'meetings', 'search', 'meetingId', 'status', 'type'));
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
            ->with('success', 'Le participant a été créé avec succès.');
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
            ->with('success', 'Le participant a été mis à jour avec succès.');
    }

    public function destroy(Participant $participant)
    {
        $participant->delete();

        return redirect()
            ->route('participants.index')
            ->with('success', 'Le participant a été supprimé.');
    }
}
