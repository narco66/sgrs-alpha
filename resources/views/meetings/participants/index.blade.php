{{-- resources/views/meetings/participants/index.blade.php --}}
@extends('layouts.app')

@section('content')
@php
    use App\Models\DelegationMember;

    $statusClasses = [
        'invited'   => 'bg-secondary-subtle text-secondary-emphasis',
        'confirmed' => 'bg-primary-subtle text-primary-emphasis',
        'present'   => 'bg-success-subtle text-success-emphasis',
        'excused'   => 'bg-warning-subtle text-warning-emphasis',
        'absent'    => 'bg-danger-subtle text-danger-emphasis',
    ];

    $statusLabels = [
        'invited'   => 'Invité',
        'confirmed' => 'Confirmé',
        'present'   => 'Présent',
        'excused'   => 'Excusé',
        'absent'    => 'Absent',
    ];
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Participants de la réunion (par délégations)</h4>
        <p class="text-muted mb-0">
            {{ $meeting->title }} — {{ $meeting->start_at?->format('d/m/Y H:i') }}
        </p>
    </div>
    <a href="{{ route('meetings.show', $meeting) }}" class="btn btn-outline-secondary">
        Retour à la réunion
    </a>
</div>

<div class="alert alert-info mb-4">
    Cette page affiche désormais les <strong>participants physiques via les délégations</strong>.
    Pour ajouter ou modifier des membres, utilisez le module <strong>Délégations</strong>.
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light small text-muted">
                    <tr>
                        <th>Délégation / Entité</th>
                        <th>Participant</th>
                        <th>Fonction</th>
                        <th>Rôle dans la délégation</th>
                        <th>Statut individuel</th>
                    </tr>
                </thead>
                <tbody>
                @php
                    $hasMembers = false;
                @endphp
                @foreach($delegations as $delegation)
                    @foreach($delegation->members as $member)
                        @php $hasMembers = true; @endphp
                        <tr>
                            <td>
                                <div class="fw-semibold">
                                    {{ $delegation->title }}
                                </div>
                                <div class="small text-muted">
                                    {{ $delegation->country ?? $delegation->organization_name ?? $delegation->entity_type }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">
                                    {{ $member->full_name }}
                                </div>
                                <div class="small text-muted">
                                    {{ $member->email ?? '—' }}
                                </div>
                            </td>
                            <td>{{ $member->position ?? '—' }}</td>
                            <td>{{ $member->role ?? '—' }}</td>
                            <td>
                                @php $status = $member->status ?? 'invited'; @endphp
                                <span class="badge {{ $statusClasses[$status] ?? 'bg-light text-muted' }}">
                                    {{ $statusLabels[$status] ?? ucfirst($status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                @endforeach

                @if(! $hasMembers)
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Aucune délégation / aucun membre enregistré pour cette réunion.
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
