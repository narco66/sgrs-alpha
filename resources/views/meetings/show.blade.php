@extends('layouts.app')

@section('content')
@php
    // Statut courant sous forme de chaîne
    // On gère le cas où status est un enum OU une simple chaîne.
    if (is_object($meeting->status) && property_exists($meeting->status, 'value')) {
        $currentStatus = $meeting->status->value ?? 'brouillon';
    } else {
        $currentStatus = $meeting->status ?? 'brouillon';
    }

    // Libellés lisibles
    $statusLabels = [
        'brouillon'      => 'Brouillon',
        'planifiee'      => 'Planifiée',
        'en_preparation' => 'En préparation',
        'en_cours'       => 'En cours',
        'terminee'       => 'Terminée',
        'annulee'        => 'Annulée',
    ];

    // Style Bootstrap des badges
    $statusBadges = [
        'brouillon'      => 'bg-secondary',
        'planifiee'      => 'bg-info text-dark',
        'en_preparation' => 'bg-warning text-dark',
        'en_cours'       => 'bg-primary',
        'terminee'       => 'bg-success',
        'annulee'        => 'bg-danger',
    ];

    // Transitions autorisées (simplifiées – à aligner avec MeetingWorkflowService)
    $transitions = [
        'brouillon'      => ['planifiee', 'annulee'],
        'planifiee'      => ['en_preparation', 'annulee'],
        'en_preparation' => ['en_cours', 'annulee'],
        'en_cours'       => ['terminee', 'annulee'],
        'terminee'       => [],
        'annulee'        => [],
    ];

    $availableTargets = $transitions[$currentStatus] ?? [];

    // Style des boutons de workflow
    $statusButtons = [
        'planifiee'      => ['label' => 'Marquer comme planifiée',     'class' => 'btn-outline-info'],
        'en_preparation' => ['label' => 'Passer en préparation',       'class' => 'btn-outline-warning'],
        'en_cours'       => ['label' => 'Démarrer la réunion',         'class' => 'btn-outline-primary'],
        'terminee'       => ['label' => 'Clôturer la réunion',         'class' => 'btn-outline-success'],
        'annulee'        => ['label' => 'Annuler la réunion',          'class' => 'btn-outline-danger'],
    ];

    // Historique des statuts (fourni par le contrôleur, sinon fallback)
    if (!isset($histories)) {
        try {
            $histories = $meeting->meetingStatusHistories()->with('user')->orderByDesc('created_at')->get();
        } catch (Exception $e) {
            $histories = collect();
        }
    }

    // Préparation des références fréquentes
    $typeName     = optional($meeting->type)->name;
    $typeCode     = optional($meeting->type)->code;
    $creatorName  = optional($meeting->creator)->name;
    $roomName     = optional($meeting->room)->name;
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">{{ $meeting->title }}</h4>
        <p class="text-muted mb-0">
            @if($typeName)
                {{ $typeName }} •
            @endif
            Créée par {{ $creatorName ?? 'Non renseigné' }}
        </p>
    </div>
    <div class="text-end">
        <div class="mb-2">
            <span class="badge rounded-pill {{ $statusBadges[$currentStatus] ?? 'bg-secondary' }}">
                {{ $statusLabels[$currentStatus] ?? ucfirst($currentStatus) }}
            </span>
        </div>

        <div class="d-flex flex-wrap justify-content-end gap-2">
            @can('update', $meeting)
                <a href="{{ route('meetings.edit', $meeting) }}" class="btn btn-sm btn-outline-secondary">
                    Modifier
                </a>
            @endcan
            <a href="{{ route('meetings.pdf', $meeting) }}" class="btn btn-sm btn-outline-primary">
                Export PDF
            </a>
                    @can('update', $meeting)
                <form action="{{ route('meetings.notify', $meeting) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-envelope me-1"></i> Notifier les délégations
                    </button>
                </form>
            @endcan
            @can('delete', $meeting)
                <form action="{{ route('meetings.destroy', $meeting) }}" method="POST"
                      onsubmit="return confirm('Confirmez-vous la suppression de cette réunion ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        Supprimer
                    </button>
                </form>
            @endcan
        </div>
    </div>
</div>

@include('partials.alerts')

{{-- Zone de workflow : boutons de changement de statut --}}
@can('update', $meeting)
    @if(count($availableTargets))
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h6 class="mb-3">Workflow de la réunion</h6>
                <p class="text-muted small mb-2">
                    Statut actuel :
                    <span class="badge rounded-pill {{ $statusBadges[$currentStatus] ?? 'bg-secondary' }}">
                        {{ $statusLabels[$currentStatus] ?? ucfirst($currentStatus) }}
                    </span>
                </p>

                <div class="mb-2 small text-muted">
                    Choisissez la prochaine étape dans le cycle de vie de la réunion.
                </div>

                <div class="d-flex flex-wrap gap-2">
                    @foreach($availableTargets as $target)
                        <form method="POST"
                              action="{{ route('meetings.change-status', $meeting) }}"
                              class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="{{ $target }}">
                            <button type="submit"
                                    class="btn btn-sm {{ $statusButtons[$target]['class'] ?? 'btn-outline-secondary' }}">
                                {{ $statusButtons[$target]['label'] ?? $statusLabels[$target] ?? ucfirst($target) }}
                            </button>
                        </form>
                    @endforeach
                </div>

                {{-- Champ de commentaire optionnel pour le prochain changement de statut --}}
                <form method="POST"
                      action="{{ route('meetings.change-status', $meeting) }}"
                      class="mt-3">
                    @csrf

                    <div class="row g-2 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label small mb-1">Changer le statut avec un commentaire</label>
                            <select name="status" class="form-select form-select-sm">
                                @foreach($statusLabels as $value => $label)
                                    @if($value !== $currentStatus)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small mb-1">Commentaire (optionnel)</label>
                            <input type="text" name="comment" class="form-control form-control-sm"
                                   placeholder="Motif du changement de statut">
                        </div>
                        <div class="col-md-2 text-end">
                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                Valider
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endcan

<div class="row g-3">
    {{-- Détails principaux --}}
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <h6 class="mb-3">Informations générales</h6>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="text-muted small">Type de réunion</div>
                        <div class="fw-semibold">
                            {{ $typeName ?? 'Non renseigné' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Configuration</div>
                        <div>
                            @if($meeting->configuration === 'presentiel')
                                <span class="badge bg-primary">Présentiel</span>
                            @elseif($meeting->configuration === 'hybride')
                                <span class="badge bg-info text-dark">Hybride</span>
                            @else
                                <span class="badge bg-secondary">Visioconférence</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-4">
                        <div class="text-muted small">Date</div>
                        <div class="fw-semibold">
                            {{ $meeting->start_at?->format('d/m/Y') ?? '—' }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Heure</div>
                        <div class="fw-semibold">
                            @if($meeting->start_at)
                                {{ $meeting->start_at->format('H:i') }}
                                –
                                {{ $meeting->start_at->copy()->addMinutes($meeting->duration_minutes)->format('H:i') }}
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Durée</div>
                        <div class="fw-semibold">
                            {{ $meeting->duration_minutes }} minutes
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="text-muted small">Salle</div>
                        <div class="fw-semibold">
                            {{ $roomName ?? 'Non attribuée' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Rappel</div>
                        <div class="fw-semibold">
                            @php
                                $r = $meeting->reminder_minutes_before;
                            @endphp
                            @if($r === null)
                                —
                            @elseif($r === 0)
                                Aucun rappel
                            @elseif($r < 60)
                                {{ $r }} minutes avant
                            @elseif($r === 60)
                                1 heure avant
                            @elseif($r % 60 === 0)
                                {{ $r / 60 }} heures avant
                            @else
                                {{ $r }} minutes avant
                            @endif
                        </div>
                    </div>
                </div>

                @if($meeting->description)
                    <hr>
                    <div class="text-muted small mb-1">Description</div>
                    <p class="mb-0">{{ $meeting->description }}</p>
                @endif
        </div>
    </div>

        {{-- Documents liés --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="mb-3">Documents liés</h6>
                @php $documents = $meeting->documents ?? collect(); @endphp
                @if($documents->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr class="text-muted small">
                                    <th>Titre</th>
                                    <th>Type</th>
                                    <th>Ajouté par</th>
                                    <th>Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $doc)
                                    <tr>
                                        <td class="fw-semibold">{{ $doc->title }}</td>
                                        <td class="small text-muted">{{ $doc->type?->name ?? $doc->type_label }}</td>
                                        <td class="small">{{ $doc->uploader?->name ?? 'N/A' }}</td>
                                        <td class="small text-muted">{{ $doc->created_at?->format('d/m/Y') ?? '—' }}</td>
                                        <td class="text-end">
                                            @can('download', $doc)
                                                <a href="{{ route('documents.download', $doc) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            @else
                                                <span class="text-muted small"><i class="bi bi-lock"></i></span>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted small mb-0">Aucun document lié à cette réunion.</p>
                @endif
            </div>
        </div>

        {{-- Délégations liées --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Délégations participantes</h6>
                    @can('create', \App\Models\Delegation::class)
                        <a href="{{ route('delegations.create', ['meeting_id' => $meeting->id]) }}" 
                           class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Ajouter une délégation
                        </a>
                    @endcan
                </div>
                
                @if($meeting->delegations->isNotEmpty())
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        @foreach($meeting->delegations as $delegation)
                            <a href="{{ route('delegations.show', $delegation) }}"
                               class="badge bg-primary text-decoration-none p-2">
                                <i class="bi bi-building"></i> {{ $delegation->title }}
                                @if($delegation->members_count > 0)
                                    <span class="badge bg-light text-dark ms-1">{{ $delegation->members_count }} membre{{ $delegation->members_count > 1 ? 's' : '' }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i> 
                        Aucune délégation n'a encore été ajoutée à cette réunion. 
                        Cliquez sur "Ajouter une délégation" pour commencer.
                    </div>
                @endif
            </div>
        </div>

        {{-- Historique des statuts --}}
        @if($histories->isNotEmpty())
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="mb-3">Historique des statuts</h6>

                    <ul class="list-unstyled mb-0">
                        @foreach($histories as $history)
                            @php
                                $old = $history->old_status;
                                $new = $history->new_status;
                                $userName = optional($history->user)->name ?? 'Système';
                            @endphp
                            <li class="mb-3 d-flex">
                                <div class="me-3">
                                    <span class="badge rounded-pill {{ $statusBadges[$new] ?? 'bg-secondary' }}">
                                        {{ $statusLabels[$new] ?? ucfirst($new) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="small">
                                        <strong>{{ $userName }}</strong>
                                        a changé le statut
                                        @if($old)
                                            de
                                            <span class="text-muted">
                                                {{ $statusLabels[$old] ?? $old }}
                                            </span>
                                        @endif
                                        à
                                        <span class="fw-semibold">
                                            {{ $statusLabels[$new] ?? $new }}
                                        </span>
                                    </div>
                                    <div class="text-muted small">
                                        {{ $history->created_at?->format('d/m/Y H:i') }}
                                    </div>
                                    @if($history->comment)
                                        <div class="small mt-1">
                                            <span class="text-muted">Commentaire :</span>
                                            {{ $history->comment }}
                                        </div>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                        </ul>
                </div>
            </div>
        @else
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="mb-3">Historique des statuts</h6>
                    <p class="text-muted small mb-0">
                        Aucun changement de statut enregistré pour le moment.
                    </p>
                </div>
            </div>
        @endif

        {{-- Notes & actions rapides --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="mb-3">Notes & actions rapides</h6>
                <ul class="list-unstyled small text-muted mb-3">
                    <li class="mb-2"><i class="bi bi-check2-circle me-1 text-success"></i>Vérifier les présences clés et envoyer les relances si besoin.</li>
                    <li class="mb-2"><i class="bi bi-geo-alt me-1 text-primary"></i>Confirmer la salle et les équipements (projection, audio).</li>
                    <li><i class="bi bi-clipboard-check me-1 text-warning"></i>Finaliser l’ordre du jour et partager les documents associés.</li>
                </ul>
                <textarea class="form-control" rows="3" placeholder="Ajoutez vos notes internes ou points à suivre..."></textarea>
                <small class="text-muted d-block mt-2">Ces notes ne sont pas enregistrées : elles servent de pense-bête local.</small>
            </div>
        </div>
    </div>

    {{-- Colonne latérale : informations --}}
    <div class="col-lg-4">
        {{-- Section Comité d'Organisation (EF20) --}}
        @if($meeting->organizationCommittee)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="bi bi-people-fill me-2"></i> Comité d'Organisation
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>{{ $meeting->organizationCommittee->name }}</strong>
                        @if($meeting->organizationCommittee->description)
                            <p class="text-muted small mb-0 mt-1">{{ $meeting->organizationCommittee->description }}</p>
                        @endif
                    </div>
                    @if($meeting->organizationCommittee->members->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Membre</th>
                                        <th>Rôle</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($meeting->organizationCommittee->members as $member)
                                        <tr>
                                            <td>{{ $member->user->name ?? 'N/A' }}</td>
                                            <td><span class="badge bg-info">{{ $member->role }}</span></td>
                                            <td class="text-muted small">{{ $member->notes ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                    <div class="mt-3">
                        <a href="{{ route('organization-committees.show', $meeting->organizationCommittee) }}" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> Voir les détails du comité
                        </a>
                    </div>
                </div>
            </div>
        @else
            @can('update', $meeting)
                <div class="card shadow-sm border-0 mb-4 border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">
                                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                                    Aucun comité d'organisation assigné
                                </h6>
                                <p class="text-muted small mb-0">EF20 - Assignez un comité d'organisation à cette réunion</p>
                            </div>
                            <a href="{{ route('organization-committees.create', ['meeting_id' => $meeting->id]) }}" 
                               class="btn btn-sm btn-warning">
                                <i class="bi bi-plus-circle"></i> Créer/Assigner un comité
                            </a>
                        </div>
                    </div>
                </div>
            @endcan
        @endif

        {{-- Section Délégations (remplace l'ancienne section Participants) --}}
        {{-- Les délégations sont déjà affichées dans la section dédiée plus haut --}}

        {{-- Informations créateur / méta --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="mb-3">Informations système</h6>
                <div class="small mb-1">
                    <span class="text-muted">Créée le :</span>
                    {{ $meeting->created_at?->format('d/m/Y H:i') ?? '—' }}
                </div>
                <div class="small mb-1">
                    <span class="text-muted">Dernière mise à jour :</span>
                    {{ $meeting->updated_at?->format('d/m/Y H:i') ?? '—' }}
                </div>
                <div class="small">
                    <span class="text-muted">Organisateur :</span>
                    {{ $creatorName ?? 'Non renseigné' }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Si un message de succès pour une délégation est affiché, faire défiler vers la section des délégations
    @php
        $successMessage = session('success');
        $isDelegationMessage = $successMessage && (strpos($successMessage, 'Délégation') !== false || strpos($successMessage, 'délégation') !== false);
    @endphp
    
    @if($isDelegationMessage)
        setTimeout(function() {
            const delegationsSection = document.getElementById('delegations-section');
            if (delegationsSection) {
                delegationsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                // Ajouter un effet de surbrillance
                delegationsSection.style.transition = 'box-shadow 0.3s ease';
                delegationsSection.style.boxShadow = '0 0 20px rgba(13, 110, 253, 0.5)';
                setTimeout(function() {
                    delegationsSection.style.boxShadow = '';
                }, 2000);
            }
        }, 500);
    @endif
});
</script>
@endpush
@endsection
