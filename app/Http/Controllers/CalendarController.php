<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Redirige vers la vue mensuelle par défaut
     */
    public function index(Request $request)
    {
        return redirect()->route('calendar.month', [
            'date' => $request->get('date', Carbon::today()->toDateString())
        ]);
    }

    /**
     * Vue journalière - Calendrier avec grille horaire (0h-18h)
     */
    public function day(Request $request)
    {
        $dateParam = $request->get('date');
        
        try {
            $baseDate = $dateParam ? Carbon::parse($dateParam) : Carbon::today();
        } catch (\Throwable $e) {
            $baseDate = Carbon::today();
        }

        $startDate = $baseDate->copy()->startOfDay();
        $endDate = $baseDate->copy()->endOfDay();

        $meetings = Meeting::with(['type', 'committee', 'room'])
            ->whereBetween('start_at', [$startDate, $endDate])
            ->orderBy('start_at')
            ->get();

        return view('calendar.day', [
            'baseDate' => $baseDate,
            'meetings' => $meetings,
        ]);
    }

    /**
     * Vue hebdomadaire - Calendrier avec grille horaire hebdomadaire
     */
    public function week(Request $request)
    {
        $dateParam = $request->get('date');
        
        try {
            $baseDate = $dateParam ? Carbon::parse($dateParam) : Carbon::today();
        } catch (\Throwable $e) {
            $baseDate = Carbon::today();
        }

        // Début de la semaine (lundi)
        $startOfWeek = $baseDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $baseDate->copy()->endOfWeek(Carbon::SUNDAY);

        $meetings = Meeting::with(['type', 'committee', 'room'])
            ->whereBetween('start_at', [$startOfWeek->startOfDay(), $endOfWeek->endOfDay()])
            ->orderBy('start_at')
            ->get();

        // Grouper les réunions par jour
        $meetingsByDay = [];
        for ($day = 0; $day < 7; $day++) {
            $currentDay = $startOfWeek->copy()->addDays($day);
            $meetingsByDay[$currentDay->toDateString()] = $meetings->filter(function($meeting) use ($currentDay) {
                return Carbon::parse($meeting->start_at)->isSameDay($currentDay);
            });
        }

        return view('calendar.week', [
            'baseDate' => $baseDate,
            'startOfWeek' => $startOfWeek,
            'endOfWeek' => $endOfWeek,
            'meetings' => $meetings,
            'meetingsByDay' => $meetingsByDay,
        ]);
    }

    /**
     * Vue mensuelle - Calendrier mensuel en grille
     */
    public function month(Request $request)
    {
        $dateParam = $request->get('date');
        
        try {
            $baseDate = $dateParam ? Carbon::parse($dateParam) : Carbon::today();
        } catch (\Throwable $e) {
            $baseDate = Carbon::today();
        }

        $startDate = $baseDate->copy()->startOfMonth();
        $endDate = $baseDate->copy()->endOfMonth();

        // Charger les réunions du mois + celles qui commencent avant mais se terminent dans le mois
        $meetings = Meeting::with(['type', 'committee', 'room'])
            ->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('start_at', [$startDate, $endDate])
                  ->orWhere(function($sub) use ($startDate, $endDate) {
                      $sub->where('start_at', '<', $startDate)
                          ->where(function($endQ) use ($startDate) {
                              $endQ->where('end_at', '>=', $startDate)
                                   ->orWhereNull('end_at');
                          });
                  });
            })
            ->orderBy('start_at')
            ->get();

        return view('calendar.month', [
            'baseDate' => $baseDate,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'meetings' => $meetings,
        ]);
    }

    /**
     * Vue annuelle - Mini-calendriers mensuels
     */
    public function year(Request $request)
    {
        $yearParam = $request->get('year');
        
        try {
            $year = $yearParam ? (int)$yearParam : Carbon::today()->year;
            if ($year < 2000 || $year > 2100) {
                $year = Carbon::today()->year;
            }
        } catch (\Throwable $e) {
            $year = Carbon::today()->year;
        }

        $startDate = Carbon::create($year, 1, 1)->startOfYear();
        $endDate = Carbon::create($year, 12, 31)->endOfYear();

        $meetings = Meeting::with(['type', 'committee', 'room'])
            ->whereBetween('start_at', [$startDate, $endDate])
            ->orderBy('start_at')
            ->get();

        // Grouper par mois
        $meetingsByMonth = [];
        $stats = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthStart = Carbon::create($year, $month, 1)->startOfMonth();
            $monthEnd = Carbon::create($year, $month, 1)->endOfMonth();
            
            $monthMeetings = $meetings->filter(function($meeting) use ($monthStart, $monthEnd) {
                $meetingDate = Carbon::parse($meeting->start_at);
                return $meetingDate->between($monthStart, $monthEnd);
            });
            
            $meetingsByMonth[$month] = $monthMeetings;
            $stats[str_pad($month, 2, '0', STR_PAD_LEFT)] = $monthMeetings->count();
        }

        return view('calendar.year', [
            'year' => $year,
            'meetings' => $meetings,
            'meetingsByMonth' => $meetingsByMonth,
            'stats' => $stats,
        ]);
    }
}
