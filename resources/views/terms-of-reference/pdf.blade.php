<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cahier des charges - {{ $meeting->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #667eea;
            margin: 0;
            font-size: 24px;
        }
        .header h2 {
            color: #666;
            margin: 10px 0 0 0;
            font-size: 16px;
            font-weight: normal;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .info-section h3 {
            color: #667eea;
            border-bottom: 2px solid #667eea;
            padding-bottom: 5px;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .info-row {
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 200px;
        }
        .content-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .content-section h4 {
            color: #333;
            background-color: #f5f5f5;
            padding: 10px;
            margin: 0 0 15px 0;
            font-size: 14px;
        }
        .content-text {
            text-align: justify;
            white-space: pre-wrap;
        }
        .signature-section {
            margin-top: 50px;
            page-break-inside: avoid;
        }
        .signature-box {
            display: inline-block;
            width: 45%;
            margin: 20px 2%;
            vertical-align: top;
        }
        .signature-line {
            border-top: 2px solid #333;
            margin-top: 60px;
            padding-top: 5px;
            text-align: center;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        @page {
            margin: 2cm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CAHIER DES CHARGES</h1>
        <h2>Entre la CEEAC et le {{ $termsOfReference->host_country }}</h2>
        <p style="margin-top: 10px; color: #666;">
            Réunion : {{ $meeting->title }}<br>
            Version {{ $termsOfReference->version }}
        </p>
    </div>

    <div class="info-section">
        <h3>Informations générales</h3>
        <div class="info-row">
            <span class="info-label">Pays hôte :</span>
            <span>{{ $termsOfReference->host_country }}</span>
        </div>
        @if($termsOfReference->signature_date)
            <div class="info-row">
                <span class="info-label">Date de signature :</span>
                <span>{{ $termsOfReference->signature_date->format('d/m/Y') }}</span>
            </div>
        @endif
        @if($termsOfReference->effective_from || $termsOfReference->effective_until)
            <div class="info-row">
                <span class="info-label">Période d'application :</span>
                <span>
                    Du {{ $termsOfReference->effective_from ? $termsOfReference->effective_from->format('d/m/Y') : 'N/A' }}
                    au {{ $termsOfReference->effective_until ? $termsOfReference->effective_until->format('d/m/Y') : 'N/A' }}
                </span>
            </div>
        @endif
        <div class="info-row">
            <span class="info-label">Statut :</span>
            <span>{{ ucfirst($termsOfReference->status) }}</span>
        </div>
    </div>

    <div class="content-section">
        <h4>1. RESPONSABILITÉS DE LA CEEAC</h4>
        <div class="content-text">{{ $termsOfReference->responsibilities_ceeac ?? 'Non renseigné' }}</div>
    </div>

    <div class="content-section">
        <h4>2. RESPONSABILITÉS DU PAYS HÔTE</h4>
        <div class="content-text">{{ $termsOfReference->responsibilities_host ?? 'Non renseigné' }}</div>
    </div>

    @if($termsOfReference->financial_sharing)
        <div class="content-section">
            <h4>3. PARTAGE DES CHARGES FINANCIÈRES</h4>
            <div class="content-text">{{ $termsOfReference->financial_sharing }}</div>
        </div>
    @endif

    @if($termsOfReference->logistical_sharing)
        <div class="content-section">
            <h4>4. PARTAGE DES CHARGES LOGISTIQUES</h4>
            <div class="content-text">{{ $termsOfReference->logistical_sharing }}</div>
        </div>
    @endif

    @if($termsOfReference->obligations_ceeac || $termsOfReference->obligations_host)
        <div class="content-section">
            <h4>5. OBLIGATIONS RESPECTIVES</h4>
            @if($termsOfReference->obligations_ceeac)
                <h5 style="margin-top: 15px; font-size: 13px;">5.1. Obligations de la CEEAC</h5>
                <div class="content-text">{{ $termsOfReference->obligations_ceeac }}</div>
            @endif
            @if($termsOfReference->obligations_host)
                <h5 style="margin-top: 15px; font-size: 13px;">5.2. Obligations du pays hôte</h5>
                <div class="content-text">{{ $termsOfReference->obligations_host }}</div>
            @endif
        </div>
    @endif

    @if($termsOfReference->additional_terms)
        <div class="content-section">
            <h4>6. TERMES ADDITIONNELS</h4>
            <div class="content-text">{{ $termsOfReference->additional_terms }}</div>
        </div>
    @endif

    @if($termsOfReference->notes)
        <div class="content-section">
            <h4>7. NOTES</h4>
            <div class="content-text">{{ $termsOfReference->notes }}</div>
        </div>
    @endif

    @if($termsOfReference->isSigned())
        <div class="signature-section">
            <h4>8. SIGNATURES</h4>
            <div class="signature-box">
                <strong>Pour la CEEAC :</strong>
                @if($termsOfReference->signerCeeac)
                    <div style="margin-top: 10px;">{{ $termsOfReference->signerCeeac->name }}</div>
                @endif
                <div class="signature-line">
                    Signature et cachet
                </div>
            </div>
            <div class="signature-box">
                <strong>Pour le {{ $termsOfReference->host_country }} :</strong>
                @if($termsOfReference->signed_by_host_name)
                    <div style="margin-top: 10px;">{{ $termsOfReference->signed_by_host_name }}</div>
                    @if($termsOfReference->signed_by_host_position)
                        <div style="font-size: 11px; color: #666;">{{ $termsOfReference->signed_by_host_position }}</div>
                    @endif
                @endif
                <div class="signature-line">
                    Signature et cachet
                </div>
            </div>
        </div>
    @endif

    <div class="footer">
        <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>SGRS-CEEAC - Système de Gestion des Réunions Statutaires</p>
    </div>
</body>
</html>








