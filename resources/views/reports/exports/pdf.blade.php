<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rapport {{ ucfirst($reportType) }} - SGRS-CEEAC</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #667eea;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #667eea;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .summary {
            background-color: #f0f4ff;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Commission de la CEEAC</h1>
        <p>Système de Gestion des Réunions Statutaires (SGRS-CEEAC)</p>
        <h2>Rapport : {{ ucfirst($reportType) }}</h2>
        <p>Période : {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        <p>Généré le : {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    @if($reportType === 'meetings')
        @if(isset($data['byType']) && $data['byType']->count() > 0)
            <h3>Réunions par Type</h3>
            <table>
                <thead>
                    <tr>
                        <th>Type de réunion</th>
                        <th>Nombre</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['byType'] as $item)
                        <tr>
                            <td>{{ $item->type ?? 'Non défini' }}</td>
                            <td>{{ $item->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if(isset($data['byStatus']) && $data['byStatus']->count() > 0)
            <h3>Réunions par Statut</h3>
            <table>
                <thead>
                    <tr>
                        <th>Statut</th>
                        <th>Nombre</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['byStatus'] as $item)
                        <tr>
                            <td>{{ ucfirst($item->status) }}</td>
                            <td>{{ $item->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    @elseif($reportType === 'participants')
        @if(isset($data['byService']) && $data['byService']->count() > 0)
            <h3>Participation par Service</h3>
            <table>
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Invitations</th>
                        <th>Confirmés</th>
                        <th>Taux de participation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['byService'] as $item)
                        @php
                            $rate = $item->total_invitations > 0 
                                ? round(($item->confirmed / $item->total_invitations) * 100, 2) 
                                : 0;
                        @endphp
                        <tr>
                            <td>{{ $item->service }}</td>
                            <td>{{ $item->total_invitations }}</td>
                            <td>{{ $item->confirmed }}</td>
                            <td>{{ $rate }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    @elseif($reportType === 'documents')
        @if(isset($data['byType']) && $data['byType']->count() > 0)
            <h3>Documents par Type</h3>
            <table>
                <thead>
                    <tr>
                        <th>Type de document</th>
                        <th>Nombre</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['byType'] as $item)
                        <tr>
                            <td>{{ $item->document_type ?? 'Non défini' }}</td>
                            <td>{{ $item->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endif

    <div class="footer">
        <p>Document généré automatiquement par le Système de Gestion des Réunions Statutaires de la CEEAC</p>
        <p>Commission de la CEEAC - {{ now()->year }}</p>
    </div>
</body>
</html>

