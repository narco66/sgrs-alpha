<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Liste des notifications de l'utilisateur connecté.
     * EF41 - Alertes internes : notifications visibles sur le tableau de bord utilisateur
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()
                ->route('login')
                ->with('error', 'Vous devez être connecté pour consulter vos notifications.');
        }

        $type = $request->get('type', 'all');
        $read = $request->get('read', 'all');

        $query = $user->notifications();

        // Filtre par type
        if ($type !== 'all') {
            $query->where('type', 'like', "%{$type}%");
        }

        // Filtre par statut de lecture
        if ($read === 'unread') {
            $query->whereNull('read_at');
        } elseif ($read === 'read') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('notifications.index', [
            'notifications' => $notifications,
            'filters' => [
                'type' => $type,
                'read' => $read,
            ],
        ]);
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead(DatabaseNotification $notification)
    {
        if (Auth::id() === $notification->notifiable_id) {
            $notification->markAsRead();

            return response()->json(['status' => 'success']);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Action non autorisée.',
        ], 403);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()
            ->route('notifications.index')
            ->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /**
     * Suppression d'une notification.
     */
    public function destroy(DatabaseNotification $notification)
    {
        if (Auth::id() === $notification->notifiable_id) {
            $notification->delete();

            return response()->json(['status' => 'success']);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Action non autorisée.',
        ], 403);
    }
}
