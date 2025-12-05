<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Participant;
use App\Models\Room;
use App\Models\Document;
use App\Models\MeetingParticipant;
use App\Models\User;
use App\Models\DocumentValidation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Affiche la page d'accueil / tableau de bord du SGRS-CEEAC.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth   = Carbon::now()->endOfMonth();

        /*
        |--------------------------------------------------------------------------
        | STATISTIQUES GÉNÉRALES - KPI PRINCIPAUX
        |--------------------------------------------------------------------------
        */
        $meetingsThisMonth = Meeting::whereBetween('start_at', [$startOfMonth, $endOfMonth])
            ->whereNull('deleted_at')
            ->count();

        $meetingsLastMonth = Meeting::whereBetween('start_at', [
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->subMonth()->endOfMonth()
        ])->whereNull('deleted_at')->count();

        $meetingsGrowth = $meetingsLastMonth > 0 
            ? round((($meetingsThisMonth - $meetingsLastMonth) / $meetingsLastMonth) * 100, 1)
            : 0;

        $activeParticipants = Participant::where('is_active', true)->count();
        $totalUsers = User::where('is_active', true)->count();

        $activeRooms = Room::where('is_active', true)->count();
        $totalRooms = Room::count();

        $sharedDocuments = Document::where('is_shared', true)->count();
        $totalDocuments = Document::whereNull('deleted_at')->count();

        // Taux de participation moyen
        $totalInvitations = MeetingParticipant::whereHas('meeting', function ($q) use ($startOfMonth, $endOfMonth) {
            $q->whereBetween('start_at', [$startOfMonth, $endOfMonth])
              ->whereNull('deleted_at');
        })->count();

        $confirmedInvitations = MeetingParticipant::whereHas('meeting', function ($q) use ($startOfMonth, $endOfMonth) {
            $q->whereBetween('start_at', [$startOfMonth, $endOfMonth])
              ->whereNull('deleted_at');
        })->where('status', 'confirmed')->count();

        $participationRate = $totalInvitations > 0 
            ? round(($confirmedInvitations / $totalInvitations) * 100, 1)
            : 0;

        // Documents en attente de validation
        $pendingValidations = DocumentValidation::where('status', 'pending')->count();
        $approvedDocuments = Document::where('validation_status', 'approved')->count();

        // Réunions par statut ce mois
        $meetingsByStatus = Meeting::selectRaw('status, COUNT(*) as count')
            ->whereBetween('start_at', [$startOfMonth, $endOfMonth])
            ->whereNull('deleted_at')
            ->groupBy('status')
            ->pluck('count', 'status');

        $completedMeetings = $meetingsByStatus->get('terminee', 0);
        $cancelledMeetings = $meetingsByStatus->get('annulee', 0);
        $successRate = $meetingsThisMonth > 0 
            ? round((($meetingsThisMonth - $cancelledMeetings) / $meetingsThisMonth) * 100, 1)
            : 100;

        /*
        |--------------------------------------------------------------------------
        | RÉUNIONS RÉCENTES (avec pagination)
        |--------------------------------------------------------------------------
        */
        $recentMeetings = Meeting::with(['room', 'type'])
            ->whereNull('deleted_at')
            ->orderByDesc('created_at')
            ->paginate(5, ['*'], 'recent_page');

        /*
        |--------------------------------------------------------------------------
        | RÉUNIONS À VENIR (avec filtre : today / week / month)
        |--------------------------------------------------------------------------
        */
        $upcomingMeetingsQuery = Meeting::with(['room', 'type'])
            ->whereDate('start_at', '>=', $today)
            ->orderBy('start_at');

        $period = $request->get('period', 'today');

        if ($period === 'week') {
            $upcomingMeetingsQuery->whereBetween('start_at', [
                $today->copy()->startOfWeek(),
                $today->copy()->endOfWeek(),
            ]);
        } elseif ($period === 'month') {
            $upcomingMeetingsQuery->whereBetween('start_at', [$startOfMonth, $endOfMonth]);
        } else {
            // today par défaut
            $upcomingMeetingsQuery->whereDate('start_at', $today);
        }

        $upcomingMeetings = $upcomingMeetingsQuery->paginate(5, ['*'], 'upcoming_page');

        /*
        |--------------------------------------------------------------------------
        | NOTIFICATIONS (SYSTÈME NATIF LARAVEL)
        |--------------------------------------------------------------------------
        */
        $notifications = collect();

        if ($user) {
            // Utilisation exclusive du système Laravel → no user_id required
            $notifications = $user->notifications()
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | CALENDRIER DU MOIS
        |--------------------------------------------------------------------------
        */
        $calendarMeetings = Meeting::select('id', 'title', 'start_at', 'status')
            ->whereBetween('start_at', [$startOfMonth, $endOfMonth])
            ->get();

        /*
        |--------------------------------------------------------------------------
        | GRAPHIQUE 1 : NOMBRE DE RÉUNIONS PAR MOIS (12 derniers mois)
        |--------------------------------------------------------------------------
        */
        $chartMeetingsByMonthLabels = [];
        $chartMeetingsByMonthData   = [];

        for ($i = 11; $i >= 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd   = Carbon::now()->subMonths($i)->endOfMonth();

            $count = Meeting::whereBetween('start_at', [$monthStart, $monthEnd])
                ->whereNull('deleted_at')
                ->count();

            $chartMeetingsByMonthLabels[] = $monthStart->translatedFormat('M');
            $chartMeetingsByMonthData[]   = $count;
        }

        /*
        |--------------------------------------------------------------------------
        | GRAPHIQUE 2 : RÉUNIONS PAR TYPE DE RÉUNION
        |--------------------------------------------------------------------------
        */
        $chartMeetingsByTypeLabels = [];
        $chartMeetingsByTypeData   = [];

        $meetingsByType = Meeting::selectRaw('types_reunions.name as type_name, COUNT(*) as count')
            ->leftJoin('types_reunions', 'reunions.meeting_type_id', '=', 'types_reunions.id')
            ->whereNull('reunions.deleted_at')
            ->groupBy('types_reunions.name')
            ->orderByDesc('count')
            ->get();

        if ($meetingsByType->count() > 0) {
            foreach ($meetingsByType as $row) {
                $chartMeetingsByTypeLabels[] = $row->type_name ?? 'Non spécifié';
                $chartMeetingsByTypeData[]   = $row->count;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | GRAPHIQUE 3 : TENDANCE DES RÉUNIONS (7 derniers jours)
        |--------------------------------------------------------------------------
        */
        $chartDailyLabels = [];
        $chartDailyData   = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Meeting::whereDate('start_at', $date)
                ->whereNull('deleted_at')
                ->count();

            $chartDailyLabels[] = $date->translatedFormat('D d/m');
            $chartDailyData[]   = $count;
        }

        /*
        |--------------------------------------------------------------------------
        | GRAPHIQUE 4 : STATUT DES DOCUMENTS
        |--------------------------------------------------------------------------
        */
        $chartDocumentsByStatus = Document::selectRaw('COALESCE(validation_status, "draft") as status, COUNT(*) as count')
            ->whereNull('deleted_at')
            ->groupBy('validation_status')
            ->pluck('count', 'status');

        /*
        |--------------------------------------------------------------------------
        | RETOUR À LA VUE
        |--------------------------------------------------------------------------
        */
        return view('dashboard.index', [
            'user'                       => $user,
            'meetingsThisMonth'          => $meetingsThisMonth,
            'meetingsGrowth'             => $meetingsGrowth,
            'activeParticipants'         => $activeParticipants,
            'totalUsers'                 => $totalUsers,
            'activeRooms'                => $activeRooms,
            'totalRooms'                 => $totalRooms,
            'sharedDocuments'            => $sharedDocuments,
            'totalDocuments'             => $totalDocuments,
            'participationRate'          => $participationRate,
            'confirmedInvitations'       => $confirmedInvitations,
            'pendingValidations'         => $pendingValidations,
            'approvedDocuments'          => $approvedDocuments,
            'completedMeetings'          => $completedMeetings,
            'successRate'                => $successRate,
            'recentMeetings'             => $recentMeetings,
            'upcomingMeetings'           => $upcomingMeetings,
            'period'                     => $period,
            'notifications'              => $notifications,
            'calendarMeetings'           => $calendarMeetings,
            'startOfMonth'               => $startOfMonth,
            'chartMeetingsByMonthLabels' => $chartMeetingsByMonthLabels,
            'chartMeetingsByMonthData'   => $chartMeetingsByMonthData,
            'chartMeetingsByTypeLabels'  => $chartMeetingsByTypeLabels,
            'chartMeetingsByTypeData'    => $chartMeetingsByTypeData,
            'chartDailyLabels'           => $chartDailyLabels,
            'chartDailyData'             => $chartDailyData,
            'chartDocumentsByStatus'     => $chartDocumentsByStatus,
        ]);
    }
}
