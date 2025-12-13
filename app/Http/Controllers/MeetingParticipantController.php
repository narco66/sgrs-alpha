<?php

namespace App\Http\Controllers;

use App\Events\ParticipantRsvpUpdated;
use App\Models\Meeting;
use App\Models\MeetingParticipant;
use App\Models\Delegation;
use App\Models\DelegationMember;
use App\Models\User;
use Illuminate\Http\Request;

class MeetingParticipantController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Vue des participants d’une réunion.
     *
     * Phase 2 : cette page est désormais basée sur les délégations / membres
     * (et non plus sur participants_reunions) pour refléter le modèle CEEAC.
     * La gestion fine (ajout / édition de membres) se fait dans le module délégations.
     */
    public function index(Meeting $meeting)
    {
        $this->authorize('view', $meeting);

        // Chargement des délégations et de leurs membres pour cette réunion
        $meeting->load(['delegations.members']);

        return view('meetings.participants.index', [
            'meeting'     => $meeting,
            'delegations' => $meeting->delegations,
        ]);
    }

    /**
     * Ajout de participants à une réunion.
     */
    public function store(Request $request, Meeting $meeting)
    {
        $this->authorize('update', $meeting);

        $data = $request->validate([
            'user_ids'   => ['required', 'array'],
            'user_ids.*' => ['integer', 'exists:utilisateurs,id'],
            'role'       => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($data['user_ids'] as $userId) {
            MeetingParticipant::firstOrCreate(
                [
                    'meeting_id' => $meeting->id,
                    'user_id'    => $userId,
                ],
                [
                    'role'   => $data['role'] ?? 'Participant',
                    'status' => MeetingParticipant::STATUS_INVITED,
                ]
            );
        }

        return redirect()
            ->route('meetings.participants.index', $meeting)
            ->with('success', 'Les participants ont été ajoutés à la réunion.');
    }

    /**
     * Mise à jour du statut (workflow : invité, confirmé, présent, excusé, absent).
     */
    public function updateStatus(Request $request, Meeting $meeting, MeetingParticipant $participant)
    {
        $this->authorize('update', $meeting);

        // Vérifier que le participant appartient bien à cette réunion
        if ($participant->meeting_id !== $meeting->id) {
            abort(404);
        }

        $data = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', MeetingParticipant::statuses())],
        ]);

        $status = $data['status'];

        $attributes = ['status' => $status];

        // Gestion des champs selon le status
        if ($status === MeetingParticipant::STATUS_PRESENT) {
            $attributes['checked_in_at'] = now();
        }

        if ($status === MeetingParticipant::STATUS_CONFIRMED) {
            $attributes['validated_at'] = now();
        }

        $participant->update($attributes);

        // EF42 / EF43 : notifications sur les réponses RSVP (participants internes)
        event(new ParticipantRsvpUpdated($meeting, $participant, $request->user()));

        return redirect()
            ->back()
            ->with('success', 'Le statut du participant a été mis à jour.');
    }

    /**
     * Retirer un participant de la réunion.
     */
    public function destroy(Meeting $meeting, MeetingParticipant $participant)
    {
        $this->authorize('update', $meeting);

        if ($participant->meeting_id !== $meeting->id) {
            abort(404);
        }

        $participant->delete();

        return redirect()
            ->route('meetings.participants.index', $meeting)
            ->with('success', 'Le participant a été retiré de la réunion.');
    }
}
