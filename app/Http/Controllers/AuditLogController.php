<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', AuditLog::class);

        $event      = $request->get('event');
        $userId     = $request->get('user_id');
        $modelType  = $request->get('model'); // App\Models\Meeting, etc.

        $query = AuditLog::with('user')
            ->when($event, fn ($q) => $q->where('event', $event))
            ->when($userId, fn ($q) => $q->where('user_id', $userId))
            ->when($modelType, fn ($q) => $q->where('auditable_type', $modelType))
            ->orderByDesc('created_at');

        $logs = $query->paginate(20)->withQueryString();

        // Liste simple des types d’événements utilisés
        $events = AuditLog::select('event')->distinct()->pluck('event')->sort()->values();

        return view('audit_logs.index', [
            'logs'   => $logs,
            'events' => $events,
            'filters' => [
                'event'     => $event,
                'user_id'   => $userId,
                'model'     => $modelType,
            ],
        ]);
    }
}
