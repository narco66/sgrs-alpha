{{-- resources/views/meetings/participants/index.blade.php --}}
@extends('layouts.app')

@section('content')
@php
    use App\Models\MeetingParticipant;

    $statusClasses = [
        MeetingParticipant::STATUS_INVITED   => 'bg-secondary-subtle text-secondary-emphasis',
        MeetingParticipant::STATUS_CONFIRMED => 'bg-primary-subtle text-primary-emphasis',
        MeetingParticipant::STATUS_PRESENT   => 'bg-success-subtle text-success-emphasis',
        MeetingParticipant::STATUS_EXCUSED   => 'bg-warning-subtle text-warning-emphasis',
        MeetingParticipant::STATUS_ABSENT    => 'bg-danger-subtle text-danger-emphasis',
    ];

    $statusIcons = [
        MeetingParticipant::STATUS_INVITED   => 'bi-envelope',
        MeetingParticipant::STATUS_CONFIRMED => 'bi-check-circle',
        MeetingParticipant::STATUS_PRESENT   => 'bi-person-check',
        MeetingParticipant::STATUS_EXCUSED   => 'bi-emoji-frown',
        MeetingParticipant::STATUS_ABSENT    => 'bi-x-circle',
    ];

    $statusLabels = [
        MeetingParticipant::STATUS_INVITED   => 'Invité',
        MeetingParticipant::STATUS_CONFIRMED => 'Confirmé',
        MeetingParticipant::STATUS_PRESENT   => 'Présent',
        MeetingParticipant::STATUS_EXCUSED   => 'Excusé',
        MeetingParticipant::STATUS_ABSENT    => 'Absent',
    ];
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Participants de la réunion</h4>
        <p class="text-muted mb-0">
            {{ $meeting->title }} — {{ $meeting->start_at?->format('d/m/Y H:i') }}
        </p>
    </div>
    <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-outline-secondary">
        Retour à la réunion
    </a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        @include('partials.alerts')

        <form method="POST" action="{{ route('meetings.participants.store', $meeting) }}" class="row g-2 align-items-end">
            @csrf

            <div class="col-md-6">
                <label class="form-label small">Ajouter des participants</label>
                <select name="user_ids[]" class="form-select" multiple required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }} — {{ $user->email }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text">
                    Maintenez Ctrl (ou Cmd) pour sélectionner plusieurs collaborateurs.
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label small">Rôle par défaut</label>
                <input type="text" name="role" class="form-control" value="{{ old('role', 'Participant') }}">
            </div>

            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-person-plus me-1"></i> Ajouter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light small text-muted">
                    <tr>
                        <th>Participant</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Validation / Présence</th>
                        <th>Rappel</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($participants as $participant)
                    @php
                        $status = $participant->status;
                        $rowClass = match($status) {
                            MeetingParticipant::STATUS_PRESENT   => 'table-success',
                            MeetingParticipant::STATUS_CONFIRMED => 'table-primary',
                            MeetingParticipant::STATUS_EXCUSED   => 'table-warning',
                            MeetingParticipant::STATUS_ABSENT    => 'table-danger',
                            default                               => '',
                        };
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td>
                            <div class="fw-semibold">
                                {{ $participant->user?->name ?? 'Utilisateur supprimé' }}
                            </div>
                            <div class="small text-muted">
                                {{ $participant->user?->email }}
                            </div>
                        </td>
                        <td>
                            {{ $participant->role ?: 'Participant' }}
                        </td>
                        <td>
                            <span class="badge {{ $statusClasses[$status] ?? 'bg-light text-muted' }}">
                                <i class="bi {{ $statusIcons[$status] ?? 'bi-dot' }} me-1"></i>
                                {{ $statusLabels[$status] ?? ucfirst($status) }}
                            </span>
                        </td>
                        <td class="small">
                            @if($participant->validated_at)
                                <div>
                                    <i class="bi bi-check2-circle text-success me-1"></i>
                                    Validé le {{ $participant->validated_at->format('d/m/Y H:i') }}
                                </div>
                            @else
                                <div class="text-muted">
                                    <i class="bi bi-hourglass-split me-1"></i> En attente de validation
                                </div>
                            @endif

                            @if($participant->checked_in_at)
                                <div>
                                    <i class="bi bi-person-check text-success me-1"></i>
                                    Présent le {{ $participant->checked_in_at->format('d/m/Y H:i') }}
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($participant->reminder_sent)
                                <span class="badge bg-success-subtle text-success-emphasis">
                                    <i class="bi bi-bell-fill me-1"></i> Envoyé
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary-emphasis">
                                    <i class="bi bi-bell-slash me-1"></i> Non envoyé
                                </span>
                            @endif
                        </td>
                        <td class="text-end">
                            {{-- Actions rapides de workflow --}}
                            <div class="btn-group btn-group-sm" role="group">
                                {{-- Inviter / réinviter --}}
                                <form method="POST" action="{{ route('meetings.participants.update-status', [$meeting, $participant]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ MeetingParticipant::STATUS_INVITED }}">
                                    <button type="submit"
                                            class="btn btn-outline-secondary"
                                            title="Inviter / Réinviter">
                                        <i class="bi bi-envelope"></i>
                                    </button>
                                </form>

                                {{-- Confirmer --}}
                                <form method="POST" action="{{ route('meetings.participants.update-status', [$meeting, $participant]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ MeetingParticipant::STATUS_CONFIRMED }}">
                                    <button type="submit"
                                            class="btn btn-outline-primary"
                                            title="Marquer comme confirmé">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>

                                {{-- Présent --}}
                                <form method="POST" action="{{ route('meetings.participants.update-status', [$meeting, $participant]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ MeetingParticipant::STATUS_PRESENT }}">
                                    <button type="submit"
                                            class="btn btn-outline-success"
                                            title="Marquer comme présent">
                                        <i class="bi bi-person-check"></i>
                                    </button>
                                </form>

                                {{-- Excusé --}}
                                <form method="POST" action="{{ route('meetings.participants.update-status', [$meeting, $participant]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ MeetingParticipant::STATUS_EXCUSED }}">
                                    <button type="submit"
                                            class="btn btn-outline-warning"
                                            title="Marquer comme excusé">
                                        <i class="bi bi-emoji-frown"></i>
                                    </button>
                                </form>
                            </div>

                            {{-- Supprimer le participant --}}
                            <form method="POST"
                                  action="{{ route('meetings.participants.destroy', [$meeting, $participant]) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Retirer ce participant de la réunion ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger ms-1"
                                        title="Retirer">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Aucun participant enregistré pour cette réunion.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
