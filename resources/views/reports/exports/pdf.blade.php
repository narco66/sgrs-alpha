@extends('pdf.layouts.master')

@section('title', 'Rapport ' . ucfirst($reportType) . ' - SGRS-CEEAC')

@section('styles')
<style>
    .report-header {
        text-align: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #1e3a8a;
    }
    .report-title {
        font-size: 20px;
        color: #1e3a8a;
        margin: 0 0 10px 0;
        text-transform: uppercase;
    }
    .report-period {
        font-size: 12px;
        color: #6b7280;
    }
    .summary-box {
        background: #eff6ff;
        padding: 15px;
        border-radius: 4px;
        margin: 15px 0;
        border-left: 4px solid #1e3a8a;
    }
    .chart-placeholder {
        background: #f9fafb;
        padding: 20px;
        text-align: center;
        color: #6b7280;
        border: 1px dashed #e5e7eb;
        margin: 10px 0;
    }
</style>
@endsection

@section('content')
{{-- EN-TÊTE DU RAPPORT --}}
<div class="report-header">
    <div class="report-title">
        Rapport : {{ ucfirst($reportType) }}
    </div>
    <div class="report-period">
        Période : {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
    </div>
</div>

{{-- RAPPORT RÉUNIONS --}}
@if($reportType === 'meetings')
    {{-- Résumé --}}
    @if(isset($data['total']))
    <div class="summary-box">
        <h3 style="margin: 0 0 10px 0;">Résumé</h3>
        <p><strong>Total des réunions :</strong> {{ $data['total'] ?? 0 }}</p>
    </div>
    @endif
    
    {{-- Réunions par Type --}}
    @if(isset($data['byType']) && $data['byType']->count() > 0)
    <div class="section">
        <h2>Réunions par Type</h2>
        <table>
            <thead>
                <tr>
                    <th>Type de réunion</th>
                    <th style="width: 100px; text-align: center;">Nombre</th>
                    <th style="width: 100px; text-align: center;">Pourcentage</th>
                </tr>
            </thead>
            <tbody>
                @php $totalByType = $data['byType']->sum('total'); @endphp
                @foreach($data['byType'] as $item)
                    <tr>
                        <td>{{ $item->type ?? 'Non défini' }}</td>
                        <td style="text-align: center;">{{ $item->total }}</td>
                        <td style="text-align: center;">
                            {{ $totalByType > 0 ? round(($item->total / $totalByType) * 100, 1) : 0 }}%
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    {{-- Réunions par Statut --}}
    @if(isset($data['byStatus']) && $data['byStatus']->count() > 0)
    <div class="section">
        <h2>Réunions par Statut</h2>
        <table>
            <thead>
                <tr>
                    <th>Statut</th>
                    <th style="width: 100px; text-align: center;">Nombre</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $statusLabels = [
                        'brouillon' => 'Brouillon',
                        'planifiee' => 'Planifiée',
                        'en_preparation' => 'En préparation',
                        'en_cours' => 'En cours',
                        'terminee' => 'Terminée',
                        'annulee' => 'Annulée'
                    ];
                @endphp
                @foreach($data['byStatus'] as $item)
                    <tr>
                        <td>{{ $statusLabels[$item->status] ?? ucfirst($item->status) }}</td>
                        <td style="text-align: center;">{{ $item->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    {{-- Réunions par Mois --}}
    @if(isset($data['byMonth']) && $data['byMonth']->count() > 0)
    <div class="section">
        <h2>Évolution mensuelle</h2>
        <table>
            <thead>
                <tr>
                    <th>Mois</th>
                    <th style="width: 100px; text-align: center;">Nombre</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['byMonth'] as $item)
                    <tr>
                        <td>{{ $item->month ?? $item->period ?? 'N/A' }}</td>
                        <td style="text-align: center;">{{ $item->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

{{-- RAPPORT PARTICIPANTS --}}
@elseif($reportType === 'participants')
    {{-- Participation par Service --}}
    @if(isset($data['byService']) && $data['byService']->count() > 0)
    <div class="section">
        <h2>Participation par Service</h2>
        <table>
            <thead>
                <tr>
                    <th>Service</th>
                    <th style="text-align: center;">Invitations</th>
                    <th style="text-align: center;">Confirmés</th>
                    <th style="text-align: center;">Taux</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['byService'] as $item)
                    @php
                        $rate = $item->total_invitations > 0 
                            ? round(($item->confirmed / $item->total_invitations) * 100, 1) 
                            : 0;
                    @endphp
                    <tr>
                        <td>{{ $item->service ?? 'Non défini' }}</td>
                        <td style="text-align: center;">{{ $item->total_invitations }}</td>
                        <td style="text-align: center;">{{ $item->confirmed }}</td>
                        <td style="text-align: center;">
                            <span class="badge {{ $rate >= 75 ? 'badge-success' : ($rate >= 50 ? 'badge-warning' : 'badge-danger') }}">
                                {{ $rate }}%
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    {{-- Taux de réponse --}}
    @if(isset($data['responseRate']))
    <div class="summary-box">
        <h3>Taux de réponse global</h3>
        <p style="font-size: 24px; color: #1e3a8a; margin: 10px 0;">
            {{ $data['responseRate'] ?? 0 }}%
        </p>
    </div>
    @endif

{{-- RAPPORT DOCUMENTS --}}
@elseif($reportType === 'documents')
    {{-- Documents par Type --}}
    @if(isset($data['byType']) && $data['byType']->count() > 0)
    <div class="section">
        <h2>Documents par Type</h2>
        <table>
            <thead>
                <tr>
                    <th>Type de document</th>
                    <th style="width: 100px; text-align: center;">Nombre</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['byType'] as $item)
                    <tr>
                        <td>{{ $item->document_type ?? $item->type ?? 'Non défini' }}</td>
                        <td style="text-align: center;">{{ $item->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    {{-- Documents par Réunion --}}
    @if(isset($data['byMeeting']) && $data['byMeeting']->count() > 0)
    <div class="section">
        <h2>Documents par Réunion</h2>
        <table>
            <thead>
                <tr>
                    <th>Réunion</th>
                    <th style="width: 100px; text-align: center;">Documents</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['byMeeting'] as $item)
                    <tr>
                        <td>{{ $item->meeting ?? 'Non défini' }}</td>
                        <td style="text-align: center;">{{ $item->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

{{-- RAPPORT PERFORMANCE --}}
@elseif($reportType === 'performance')
    <div class="summary-box">
        <h3>Indicateurs de Performance</h3>
        @if(isset($data['metrics']))
            <table style="margin-top: 10px;">
                @foreach($data['metrics'] as $metric => $value)
                    <tr>
                        <td style="width: 60%; font-weight: bold;">{{ $metric }}</td>
                        <td>{{ $value }}</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p class="text-muted">Aucune donnée de performance disponible.</p>
        @endif
    </div>
@endif

{{-- NOTES DE FIN DE RAPPORT --}}
<div class="section mt-4">
    <div class="info-box info-box-warning">
        <p class="text-small">
            <strong>Note :</strong> Ce rapport a été généré automatiquement par le système SGRS-CEEAC.
            Les données présentées reflètent l'état de la base de données au moment de la génération.
        </p>
    </div>
</div>
@endsection
