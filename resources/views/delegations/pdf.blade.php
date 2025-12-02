<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Délégation - {{ $delegation->title }}</title>
    <style>
        @page { margin: 110px 30px 70px 30px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111827; margin: 0; padding: 0 20px; box-sizing: border-box; }
        h1 { margin: 0 0 12px; font-size: 20px; color: #0f172a; }
        h2 { margin: 18px 0 8px; font-size: 16px; border-bottom: 1px solid #e5e7eb; padding-bottom: 6px; }
        p { margin: 2px 0 6px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #e5e7eb; padding: 6px 8px; text-align: left; }
        th { background: #f8fafc; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px 16px; margin-top: 8px; }
        .muted { color: #6b7280; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 10px; font-size: 11px; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-secondary { background: #e5e7eb; color: #374151; }
        .header-table { width: 100%; margin-bottom: 14px; }
        .footer { font-size: 11px; color: #6b7280; text-align: center; border-top: 1px solid #e5e7eb; padding-top: 6px; margin-top: 20px; }
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
    <table class="header-table">
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

    <h1>{{ $delegation->title }}</h1>
    <p class="muted">
        @if($delegation->code) Code : {{ $delegation->code }} @endif
        @if($delegation->country) — Pays : {{ $delegation->country }} @endif
    </p>

    <div class="grid">
        <div><strong>Statut :</strong>
            <span class="badge {{ $delegation->is_active ? 'badge-success' : 'badge-secondary' }}">
                {{ $delegation->is_active ? 'Actif' : 'Inactif' }}
            </span>
        </div>
        <div><strong>Email contact :</strong> {{ $delegation->contact_email ?? '—' }}</div>
        <div><strong>Téléphone :</strong> {{ $delegation->contact_phone ?? '—' }}</div>
        <div><strong>Adresse :</strong> {{ $delegation->address ?? '—' }}</div>
    </div>

    @if($delegation->description)
        <h2>Présentation</h2>
        <p>{{ $delegation->description }}</p>
    @endif

    <h2>Réunion associée</h2>
    @if($delegation->meeting)
        <p><strong>{{ $delegation->meeting->title }}</strong></p>
        <p class="muted">
            {{ $delegation->meeting->start_at?->format('d/m/Y H:i') ?? 'Date non définie' }}
            @if($delegation->meeting->room)
                — Salle : {{ $delegation->meeting->room->name }}
            @endif
        </p>
    @else
        <p class="muted">Aucune réunion liée.</p>
    @endif

    <h2>Participants de la délégation</h2>
    @if($delegation->participants->count())
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                </tr>
            </thead>
            <tbody>
                @foreach($delegation->participants as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email ?? '—' }}</td>
                        <td>{{ $user->pivot->role ?? 'Participant' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="muted">Aucun participant renseigné.</p>
    @endif

    <h2>Utilisateurs rattachés</h2>
    @if($delegation->users->count())
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Service</th>
                </tr>
            </thead>
            <tbody>
                @foreach($delegation->users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email ?? '—' }}</td>
                        <td>{{ $user->service ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="muted">Aucun utilisateur rattaché.</p>
    @endif

    <div class="footer">
        BP:2112 Libreville-GABON Tel. +(241) 44 47 31, +(241) 44 47 34 -Email : commission@ceeac-eccas.org
    </div>
</body>
</html>
