<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Document;
use App\Models\User;
use App\Models\MeetingParticipant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ReportingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Page principale des rapports
     * EF44-EF48 - Tableaux de bord et reporting
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Statistiques sur les réunions
     * EF44 - Statistiques sur les réunions
     */
    public function meetings(Request $request)
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);

        // Statistiques par type
        $byType = Meeting::selectRaw('types_reunions.name as type, COUNT(*) as total')
            ->leftJoin('types_reunions', 'reunions.meeting_type_id', '=', 'types_reunions.id')
            ->whereBetween('reunions.start_at', [$startDate, $endDate])
            ->whereNull('reunions.deleted_at')
            ->groupBy('types_reunions.name')
            ->get();

        // Statistiques par statut
        $byStatus = Meeting::selectRaw('status, COUNT(*) as total')
            ->whereBetween('start_at', [$startDate, $endDate])
            ->whereNull('deleted_at')
            ->groupBy('status')
            ->get();

        // Statistiques par période (mois)
        $byPeriod = Meeting::selectRaw('DATE_FORMAT(start_at, "%Y-%m") as period, COUNT(*) as total')
            ->whereBetween('start_at', [$startDate, $endDate])
            ->whereNull('deleted_at')
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        // Délai moyen de convocation (en jours)
        $avgConvocationDelay = Meeting::selectRaw('AVG(DATEDIFF(start_at, created_at)) as avg_delay')
            ->whereBetween('start_at', [$startDate, $endDate])
            ->whereNull('deleted_at')
            ->first();

        return view('reports.meetings', compact(
            'byType',
            'byStatus',
            'byPeriod',
            'avgConvocationDelay',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Statistiques sur les participants
     * EF45 - Statistiques sur les participants
     */
    public function participants(Request $request)
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);

        // Taux de participation global
        $totalInvitations = MeetingParticipant::whereHas('meeting', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_at', [$startDate, $endDate])
              ->whereNull('deleted_at');
        })->count();

        $totalConfirmed = MeetingParticipant::whereHas('meeting', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_at', [$startDate, $endDate])
              ->whereNull('deleted_at');
        })->where('status', 'confirmed')->count();

        $totalAttended = MeetingParticipant::whereHas('meeting', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_at', [$startDate, $endDate])
              ->whereNull('deleted_at');
        })->whereNotNull('checked_in_at')->count();

        $globalParticipationRate = $totalInvitations > 0 
            ? round(($totalConfirmed / $totalInvitations) * 100, 2) 
            : 0;

        $attendanceRate = $totalInvitations > 0 
            ? round(($totalAttended / $totalInvitations) * 100, 2) 
            : 0;

        // Par service
        $byService = MeetingParticipant::selectRaw('utilisateurs.service, COUNT(*) as total_invitations, 
                SUM(CASE WHEN participants_reunions.status = "confirmed" THEN 1 ELSE 0 END) as confirmed,
                SUM(CASE WHEN participants_reunions.checked_in_at IS NOT NULL THEN 1 ELSE 0 END) as attended')
            ->join('utilisateurs', 'participants_reunions.user_id', '=', 'utilisateurs.id')
            ->whereHas('meeting', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_at', [$startDate, $endDate])
                  ->whereNull('deleted_at');
            })
            ->whereNotNull('utilisateurs.service')
            ->groupBy('utilisateurs.service')
            ->orderByDesc('total_invitations')
            ->get();

        return view('reports.participants', compact(
            'totalInvitations',
            'totalConfirmed',
            'totalAttended',
            'globalParticipationRate',
            'attendanceRate',
            'byService',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Statistiques sur les documents
     * EF46 - Statistiques sur les documents
     */
    public function documents(Request $request)
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);

        // Par type
        $byType = Document::selectRaw('document_type, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNull('deleted_at')
            ->groupBy('document_type')
            ->get();

        // Par statut de validation
        $byValidationStatus = Document::selectRaw('COALESCE(validation_status, "draft") as status, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNull('deleted_at')
            ->groupBy('validation_status')
            ->get();

        // Délai moyen de validation
        $avgValidationDelay = Document::selectRaw('AVG(DATEDIFF(updated_at, created_at)) as avg_delay')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('validation_status', ['approved', 'archived'])
            ->whereNull('deleted_at')
            ->first();

        // Documents archivés
        $archivedCount = Document::where('validation_status', 'archived')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNull('deleted_at')
            ->count();

        return view('reports.documents', compact(
            'byType',
            'byValidationStatus',
            'avgValidationDelay',
            'archivedCount',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Indicateurs de performance
     * EF47 - Indicateurs de performance
     */
    public function performance(Request $request)
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);

        // Délai moyen de convocation
        $avgConvocationDelay = Meeting::selectRaw('AVG(DATEDIFF(start_at, created_at)) as avg_delay')
            ->whereBetween('start_at', [$startDate, $endDate])
            ->whereNull('deleted_at')
            ->first();

        // Délai moyen de validation des documents
        $avgValidationDelay = Document::selectRaw('AVG(DATEDIFF(updated_at, created_at)) as avg_delay')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('validation_status', ['approved', 'archived'])
            ->whereNull('deleted_at')
            ->first();

        // Délai moyen d'archivage
        $avgArchivingDelay = Document::selectRaw('AVG(DATEDIFF(updated_at, created_at)) as avg_delay')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('validation_status', 'archived')
            ->whereNull('deleted_at')
            ->first();

        // Taux de réunions terminées
        $totalMeetings = Meeting::whereBetween('start_at', [$startDate, $endDate])
            ->whereNull('deleted_at')
            ->count();

        $completedMeetings = Meeting::whereBetween('start_at', [$startDate, $endDate])
            ->where('status', 'terminee')
            ->whereNull('deleted_at')
            ->count();

        $completionRate = $totalMeetings > 0 
            ? round(($completedMeetings / $totalMeetings) * 100, 2) 
            : 0;

        return view('reports.performance', compact(
            'avgConvocationDelay',
            'avgValidationDelay',
            'avgArchivingDelay',
            'completionRate',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export des rapports
     * EF48 - Export des rapports en PDF et Excel
     */
    public function export(Request $request, string $reportType, string $format = 'pdf')
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);

        if ($format === 'excel') {
            return $this->exportExcel($reportType, $startDate, $endDate);
        } else {
            return $this->exportPdf($reportType, $startDate, $endDate);
        }
    }

    /**
     * Export Excel
     */
    protected function exportExcel(string $reportType, $startDate, $endDate)
    {
        $filename = "rapport_{$reportType}_" . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($reportType, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            // BOM UTF-8 pour Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            switch ($reportType) {
                case 'meetings':
                    fputcsv($file, ['Type', 'Total'], ';');
                    $data = Meeting::selectRaw('types_reunions.name as type, COUNT(*) as total')
                        ->leftJoin('types_reunions', 'reunions.meeting_type_id', '=', 'types_reunions.id')
                        ->whereBetween('reunions.start_at', [$startDate, $endDate])
                        ->whereNull('reunions.deleted_at')
                        ->groupBy('types_reunions.name')
                        ->get();
                    foreach ($data as $row) {
                        fputcsv($file, [$row->type ?? 'Non défini', $row->total], ';');
                    }
                    break;

                case 'participants':
                    fputcsv($file, ['Service', 'Invitations', 'Confirmés', 'Présents', 'Taux de participation'], ';');
                    $data = MeetingParticipant::selectRaw('utilisateurs.service, 
                            COUNT(*) as total_invitations,
                            SUM(CASE WHEN participants_reunions.status = "confirmed" THEN 1 ELSE 0 END) as confirmed,
                            SUM(CASE WHEN participants_reunions.checked_in_at IS NOT NULL THEN 1 ELSE 0 END) as attended')
                        ->join('utilisateurs', 'participants_reunions.user_id', '=', 'utilisateurs.id')
                        ->whereHas('meeting', function ($q) use ($startDate, $endDate) {
                            $q->whereBetween('start_at', [$startDate, $endDate])
                              ->whereNull('deleted_at');
                        })
                        ->whereNotNull('utilisateurs.service')
                        ->groupBy('utilisateurs.service')
                        ->get();
                    foreach ($data as $row) {
                        $rate = $row->total_invitations > 0 
                            ? round(($row->confirmed / $row->total_invitations) * 100, 2) 
                            : 0;
                        fputcsv($file, [
                            $row->service,
                            $row->total_invitations,
                            $row->confirmed,
                            $row->attended,
                            $rate . '%'
                        ], ';');
                    }
                    break;

                case 'documents':
                    fputcsv($file, ['Type', 'Total'], ';');
                    $data = Document::selectRaw('document_type, COUNT(*) as total')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->whereNull('deleted_at')
                        ->groupBy('document_type')
                        ->get();
                    foreach ($data as $row) {
                        fputcsv($file, [$row->document_type ?? 'Non défini', $row->total], ';');
                    }
                    break;
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export PDF
     */
    protected function exportPdf(string $reportType, $startDate, $endDate)
    {
        // Pour l'export PDF, on génère une vue HTML qui peut être convertie en PDF
        // ou on utilise un package comme barryvdh/laravel-dompdf
        
        $data = $this->getReportData($reportType, $startDate, $endDate);
        
        $html = view('reports.exports.pdf', [
            'reportType' => $reportType,
            'data' => $data,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ])->render();

        // Pour l'instant, on retourne le HTML (peut être converti en PDF avec un package)
        // En production, utiliser: return PDF::loadHTML($html)->download("rapport_{$reportType}.pdf");
        
        return response($html)
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }

    /**
     * Récupère les données pour un type de rapport
     */
    protected function getReportData(string $reportType, $startDate, $endDate)
    {
        switch ($reportType) {
            case 'meetings':
                return [
                    'byType' => Meeting::selectRaw('types_reunions.name as type, COUNT(*) as total')
                        ->leftJoin('types_reunions', 'reunions.meeting_type_id', '=', 'types_reunions.id')
                        ->whereBetween('reunions.start_at', [$startDate, $endDate])
                        ->whereNull('reunions.deleted_at')
                        ->groupBy('types_reunions.name')
                        ->get(),
                    'byStatus' => Meeting::selectRaw('status, COUNT(*) as total')
                        ->whereBetween('start_at', [$startDate, $endDate])
                        ->whereNull('deleted_at')
                        ->groupBy('status')
                        ->get(),
                ];

            case 'participants':
                return [
                    'byService' => MeetingParticipant::selectRaw('utilisateurs.service, 
                            COUNT(*) as total_invitations,
                            SUM(CASE WHEN participants_reunions.status = "confirmed" THEN 1 ELSE 0 END) as confirmed')
                        ->join('utilisateurs', 'participants_reunions.user_id', '=', 'utilisateurs.id')
                        ->whereHas('meeting', function ($q) use ($startDate, $endDate) {
                            $q->whereBetween('start_at', [$startDate, $endDate])
                              ->whereNull('deleted_at');
                        })
                        ->whereNotNull('utilisateurs.service')
                        ->groupBy('utilisateurs.service')
                        ->get(),
                ];

            case 'documents':
                return [
                    'byType' => Document::selectRaw('document_type, COUNT(*) as total')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->whereNull('deleted_at')
                        ->groupBy('document_type')
                        ->get(),
                ];

            default:
                return [];
        }
    }

    /**
     * Normalise les filtres de dates pour tous les rapports.
     */
    protected function resolveDateRange(Request $request): array
    {
        $startInput = $request->input('start_date');
        $endInput = $request->input('end_date');

        $startDate = $startInput
            ? Carbon::parse($startInput)->startOfDay()
            : Carbon::now()->subMonths(6)->startOfMonth();

        $endDate = $endInput
            ? Carbon::parse($endInput)->endOfDay()
            : Carbon::now()->endOfMonth();

        return [$startDate, $endDate];
    }
}
