<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche r&eacute;union - {{ $meeting->title }}</title>
    <style>
        @page { margin: 120px 30px 70px 30px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111827; margin: 0; padding: 0 24px; box-sizing: border-box; }
        h1, h2, h3, h4 { margin: 0 0 8px 0; color: #0f172a; }
        h1 { font-size: 20px; }
        h2 { font-size: 16px; border-bottom: 1px solid #e5e7eb; padding-bottom: 6px; margin-top: 18px; }
        p { margin: 2px 0 6px 0; }
        .meta { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px 16px; margin-top: 10px; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 12px; font-size: 11px; }
        .badge-primary { background: #e0f2fe; color: #075985; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef9c3; color: #854d0e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #e5e7eb; padding: 6px 8px; text-align: left; }
        th { background: #f8fafc; }
        .muted { color: #6b7280; }
        .footer {
            font-size: 11px;
            color: #6b7280;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            padding: 8px 0 0 0;
            margin-top: 24px;
        }
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
                'planifiee' => ['label' => 'Planifi&eacute;e', 'class' => 'badge-primary'],
                'en_preparation' => ['label' => 'En pr&eacute;paration', 'class' => 'badge-warning'],
                'en_cours' => ['label' => 'En cours', 'class' => 'badge-primary'],
                'terminee' => ['label' => 'Termin&eacute;e', 'class' => 'badge-success'],
                'annulee' => ['label' => 'Annul&eacute;e', 'class' => 'badge-danger'],
            ];
            $label = $statusLabels[$status]['label'] ?? ucfirst($status);
            $class = $statusLabels[$status]['class'] ?? 'badge-primary';
        @endphp
        <span class="badge {{ $class }}">{{ $label }}</span>
    </p>

    <div class="meta">
        <div><strong>Type :</strong> {{ $meeting->type?->name ?? 'Non renseign&eacute;' }}</div>
        <div><strong>Comit&eacute; :</strong> {{ $meeting->committee?->name ?? 'Non renseign&eacute;' }}</div>
        <div><strong>Organisateur :</strong> {{ $meeting->organizer?->name ?? 'Non renseign&eacute;' }}</div>
        <div><strong>Salle :</strong> {{ $meeting->room?->name ?? 'Non renseign&eacute;' }}</div>
        <div><strong>D&eacute;but :</strong> {{ $meeting->start_at?->format('d/m/Y H:i') ?? 'Non d&eacute;fini' }}</div>
        <div><strong>Fin :</strong> {{ $meeting->end_at?->format('d/m/Y H:i') ?? 'Non d&eacute;finie' }}</div>
        <div><strong>Dur&eacute;e :</strong> {{ $meeting->duration_minutes ? $meeting->duration_minutes . ' min' : 'Non d&eacute;finie' }}</div>
        <div><strong>Rappel :</strong> {{ $meeting->reminder_minutes_before ? $meeting->reminder_minutes_before . ' min avant' : 'Aucun' }}</div>
    </div>

    @if($meeting->description)
        <h2>Objectif</h2>
        <p>{{ $meeting->description }}</p>
    @endif

    @if($meeting->agenda)
        <h2>Ordre du jour</h2>
        <p>{{ $meeting->agenda }}</p>
    @endif

    <h2>Comit&eacute; d'organisation</h2>
    @if($meeting->organizationCommittee)
        <p><strong>Nom :</strong> {{ $meeting->organizationCommittee->name }}</p>
        <p class="muted">{{ $meeting->organizationCommittee->description }}</p>
        @php $members = $meeting->organizationCommittee->members ?? collect(); @endphp
        @if($members->count())
            <table>
                <thead>
                    <tr>
                        <th>Membre</th>
                        <th>R&ocirc;le</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                        <tr>
                            <td>{{ $member->user?->name ?? 'Non renseign&eacute;' }}</td>
                            <td>{{ $member->role ?? '-' }}</td>
                            <td>{{ $member->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="muted">Aucun membre renseign&eacute;.</p>
        @endif
    @else
        <p class="muted">Aucun comit&eacute; d'organisation associ&eacute;.</p>
    @endif

    @php $delegations = $delegations ?? collect(); @endphp
    @if($delegations->count())
        <h2>Délégations liées</h2>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Code</th>
                    <th>Pays</th>
                    <th>Participants</th>
                </tr>
            </thead>
            <tbody>
                @foreach($delegations as $delegation)
                    <tr>
                        <td>{{ $delegation->title }}</td>
                        <td>{{ $delegation->code ?? '—' }}</td>
                        <td>{{ $delegation->country ?? '—' }}</td>
                        <td>
                            @php $users = $delegation->users ?? collect(); @endphp
                            @if($users->count())
                                {{ $users->pluck('name')->implode(', ') }}
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2>Participants</h2>
    @if(isset($participants) && $participants->count())
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>R&ocirc;le/Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($participants as $user)
                    @php
                        $participation = $meeting->participants->firstWhere('user_id', $user->id);
                        $role = $participation?->role ?? 'Participant';
                        $statusValue = $participation?->status ?? 'invited';
                    @endphp
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email ?? '-' }}</td>
                        <td>{{ ucfirst($role) }} ({{ $statusValue }})</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="muted">Aucun participant renseign&eacute;.</p>
    @endif

    <h2>Documents associ&eacute;s</h2>
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
        <p class="muted">Aucun document associ&eacute; pour le moment.</p>
    @endif

    <div class="footer">
        BP:2112 Libreville-GABON Tel. +(241) 44 47 31, +(241) 44 47 34 -Email : commission@ceeac-eccas.org
    </div>
</body>
</html>
