<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche réunion - {{ $meeting->title }}</title>
    <style>
        @page { margin: 120px 30px 70px 30px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111827; margin: 0; padding: 0 24px; box-sizing: border-box; }
        h1, h2, h3, h4 { margin: 0 0 8px 0; color: #0f172a; }
        h1 { font-size: 20px; }
        h2 { font-size: 16px; border-bottom: 1px solid #e5e7eb; padding-bottom: 6px; margin-top: 18px; }
        h3 { font-size: 14px; margin-top: 12px; margin-bottom: 6px; color: #374151; }
        p { margin: 2px 0 6px 0; }
        .meta { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px 16px; margin-top: 10px; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 12px; font-size: 11px; }
        .badge-primary { background: #e0f2fe; color: #075985; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef9c3; color: #854d0e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #e5e7eb; padding: 6px 8px; text-align: left; font-size: 11px; }
        th { background: #f8fafc; font-weight: bold; }
        .muted { color: #6b7280; }
        .footer {
            font-size: 11px;
            color: #6b7280;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            padding: 8px 0 0 0;
            margin-top: 24px;
        }
        .delegation-section { margin-top: 12px; page-break-inside: avoid; }
        .member-list { margin-left: 16px; margin-top: 4px; font-size: 11px; }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('images/logo-ceeac.png');
        if (! file_exists($logoPath)) {
            $alt = glob(public_path('images/logo*ceeac.png'));
            $logoPath = $alt[0] ?? null;
        }
    @endphp
    <table width="100%" style="margin-bottom:12px;">
        <tr>
            <td style="width:70px;">
                @if($logoPath)
                    <img src="{{ $logoPath }}" alt="CEEAC" style="height:50px;">
                @endif
            </td>
            <td style="text-align:right; font-size:13px; color:#0f172a; font-weight:bold;">
                SGRS-CEEAC: Système de Gestion des Réunions Statutaires de la CEEAC
            </td>
        </tr>
    </table>
    <h1>{{ $meeting->title }}</h1>
    <p class="muted">
        @php
            $status = is_object($meeting->status) && property_exists($meeting->status, 'value')
                ? $meeting->status->value
                : ($meeting->status ?? 'brouillon');
            $statusLabels = [
                'brouillon' => ['label' => 'Brouillon', 'class' => 'badge-warning'],
                'planifiee' => ['label' => 'Planifiée', 'class' => 'badge-primary'],
                'en_preparation' => ['label' => 'En préparation', 'class' => 'badge-warning'],
                'en_cours' => ['label' => 'En cours', 'class' => 'badge-primary'],
                'terminee' => ['label' => 'Terminée', 'class' => 'badge-success'],
                'annulee' => ['label' => 'Annulée', 'class' => 'badge-danger'],
            ];
            $label = $statusLabels[$status]['label'] ?? ucfirst($status);
            $class = $statusLabels[$status]['class'] ?? 'badge-primary';
        @endphp
        <span class="badge {{ $class }}">{{ $label }}</span>
    </p>

    <div class="meta">
        <div><strong>Type :</strong> {{ $meeting->type?->name ?? 'Non renseigné' }}</div>
        <div><strong>Comité :</strong> {{ $meeting->committee?->name ?? 'Non renseigné' }}</div>
        <div><strong>Organisateur :</strong> {{ $meeting->organizer?->name ?? 'Non renseigné' }}</div>
        <div><strong>Salle :</strong> {{ $meeting->room?->name ?? 'Non renseigné' }}</div>
        <div><strong>Début :</strong> {{ $meeting->start_at?->format('d/m/Y H:i') ?? 'Non défini' }}</div>
        <div><strong>Fin :</strong> {{ $meeting->end_at?->format('d/m/Y H:i') ?? 'Non définie' }}</div>
        <div><strong>Durée :</strong> {{ $meeting->duration_minutes ? $meeting->duration_minutes . ' min' : 'Non définie' }}</div>
        <div><strong>Rappel :</strong> {{ $meeting->reminder_minutes_before ? $meeting->reminder_minutes_before . ' min avant' : 'Aucun' }}</div>
    </div>

    @if($meeting->description)
        <h2>Objectif</h2>
        <p>{{ $meeting->description }}</p>
    @endif

    @if($meeting->agenda)
        <h2>Ordre du jour</h2>
        <p style="white-space: pre-wrap;">{{ $meeting->agenda }}</p>
    @endif

    {{-- Comité d'organisation --}}
    <h2>Comité d'organisation</h2>
    @if($meeting->organizationCommittee)
        <p><strong>Nom :</strong> {{ $meeting->organizationCommittee->name }}</p>
        @if($meeting->organizationCommittee->description)
            <p class="muted">{{ $meeting->organizationCommittee->description }}</p>
        @endif
        @if($meeting->organizationCommittee->host_country)
            <p><strong>Pays hôte :</strong> {{ $meeting->organizationCommittee->host_country }}</p>
        @endif
        @php $members = $meeting->organizationCommittee->members ?? collect(); @endphp
        @if($members->count())
            <table>
                <thead>
                    <tr>
                        <th>Membre</th>
                        <th>Type</th>
                        <th>Rôle</th>
                        <th>Service/Département</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                        <tr>
                            <td>{{ $member->user?->name ?? 'Non renseigné' }}</td>
                            <td>
                                @php
                                    $memberType = $member->member_type ?? 'ceeac';
                                    echo $memberType === 'host_country' ? 'Pays hôte' : 'CEEAC';
                                @endphp
                            </td>
                            <td>{{ $member->role ?? '-' }}</td>
                            <td>{{ $member->department ?? $member->service ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="muted">Aucun membre renseigné.</p>
        @endif
    @else
        <p class="muted">Aucun comité d'organisation associé.</p>
    @endif

    {{-- Cahier des charges --}}
    @if($meeting->termsOfReference)
        <h2>Cahier des charges</h2>
        <p><strong>Pays hôte :</strong> {{ $meeting->termsOfReference->host_country ?? 'Non renseigné' }}</p>
        @if($meeting->termsOfReference->signature_date)
            <p><strong>Date de signature :</strong> {{ $meeting->termsOfReference->signature_date->format('d/m/Y') }}</p>
        @endif
        <p><strong>Statut :</strong> 
            @php
                $statusLabels = [
                    'draft' => 'Brouillon',
                    'pending_validation' => 'En attente de validation',
                    'validated' => 'Validé',
                    'signed' => 'Signé',
                    'cancelled' => 'Annulé'
                ];
                echo $statusLabels[$meeting->termsOfReference->status] ?? $meeting->termsOfReference->status;
            @endphp
        </p>
        <p><strong>Version :</strong> {{ $meeting->termsOfReference->version }}</p>
    @endif

    {{-- Délégations participantes --}}
    <h2>Délégations participantes</h2>
    @php $delegations = $meeting->delegations ?? collect(); @endphp
    @if($delegations->count())
        @foreach($delegations as $delegation)
            <div class="delegation-section">
                <h3>{{ $delegation->title }}</h3>
                <table>
                    <tr>
                        <td style="width: 30%;"><strong>Type d'entité :</strong></td>
                        <td>
                            @php
                                $entityTypes = [
                                    'state_member' => 'État membre',
                                    'international_organization' => 'Organisation internationale',
                                    'technical_partner' => 'Partenaire technique',
                                    'financial_partner' => 'Partenaire financier',
                                    'other' => 'Autre'
                                ];
                                echo $entityTypes[$delegation->entity_type] ?? $delegation->entity_type;
                            @endphp
                        </td>
                    </tr>
                    @if($delegation->country)
                        <tr>
                            <td><strong>Pays :</strong></td>
                            <td>{{ $delegation->country }}</td>
                        </tr>
                    @endif
                    @if($delegation->organization_name)
                        <tr>
                            <td><strong>Organisation :</strong></td>
                            <td>{{ $delegation->organization_name }}</td>
                        </tr>
                    @endif
                    @if($delegation->head_of_delegation_name)
                        <tr>
                            <td><strong>Chef de délégation :</strong></td>
                            <td>
                                {{ $delegation->head_of_delegation_name }}
                                @if($delegation->head_of_delegation_position)
                                    ({{ $delegation->head_of_delegation_position }})
                                @endif
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td><strong>Statut de participation :</strong></td>
                        <td>
                            @php
                                $participationStatuses = [
                                    'invited' => 'Invité',
                                    'confirmed' => 'Confirmé',
                                    'registered' => 'Inscrit',
                                    'present' => 'Présent',
                                    'absent' => 'Absent',
                                    'excused' => 'Excusé'
                                ];
                                echo $participationStatuses[$delegation->participation_status] ?? $delegation->participation_status;
                            @endphp
                        </td>
                    </tr>
                </table>

                {{-- Membres de la délégation --}}
                @php $delegationMembers = $delegation->members ?? collect(); @endphp
                @if($delegationMembers->count())
                    <h4 style="margin-top: 8px; margin-bottom: 4px; font-size: 12px;">Membres de la délégation ({{ $delegationMembers->count() }})</h4>
                    <table style="margin-top: 4px;">
                        <thead>
                            <tr>
                                <th>Nom complet</th>
                                <th>Email</th>
                                <th>Fonction</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($delegationMembers as $member)
                                <tr>
                                    <td>
                                        {{ trim(($member->title ?? '') . ' ' . ($member->first_name ?? '') . ' ' . ($member->last_name ?? '')) }}
                                    </td>
                                    <td>{{ $member->email ?? '-' }}</td>
                                    <td>{{ $member->position ?? '-' }}</td>
                                    <td>
                                        @php
                                            $roles = [
                                                'head' => 'Chef',
                                                'deputy' => 'Adjoint',
                                                'member' => 'Membre',
                                                'advisor' => 'Conseiller',
                                                'expert' => 'Expert',
                                                'interpreter' => 'Interprète'
                                            ];
                                            echo $roles[$member->role] ?? $member->role;
                                        @endphp
                                    </td>
                                    <td>
                                        @php
                                            $statuses = [
                                                'pending' => 'En attente',
                                                'confirmed' => 'Confirmé',
                                                'registered' => 'Inscrit',
                                                'present' => 'Présent',
                                                'absent' => 'Absent'
                                            ];
                                            echo $statuses[$member->status] ?? $member->status;
                                        @endphp
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="muted" style="margin-top: 4px; font-size: 11px;">Aucun membre enregistré pour cette délégation.</p>
                @endif
            </div>
        @endforeach
    @else
        <p class="muted">Aucune délégation enregistrée pour cette réunion.</p>
    @endif

    {{-- Documents associés --}}
    <h2>Documents associés</h2>
    @php $documents = $meeting->documents ?? collect(); @endphp
    @if($documents->count())
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Auteur</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $doc)
                    <tr>
                        <td>{{ $doc->title }}</td>
                        <td>{{ $doc->type?->name ?? $doc->document_type ?? '-' }}</td>
                        <td>{{ $doc->created_at?->format('d/m/Y') ?? '-' }}</td>
                        <td>{{ $doc->uploader?->name ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="muted">Aucun document associé pour le moment.</p>
    @endif

    <div class="footer">
        BP:2112 Libreville-GABON Tel. +(241) 44 47 31, +(241) 44 47 34 - Email : commission@ceeac-eccas.org
        <br>
        Document généré le {{ now()->format('d/m/Y à H:i') }} - SGRS-CEEAC
    </div>
</body>
</html>
