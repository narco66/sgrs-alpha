<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use App\Services\AuditLogger;

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
    public function markAsRead(Request $request, DatabaseNotification $notification)
    {
        if (Auth::id() === $notification->notifiable_id) {
            $notification->markAsRead();

            // Audit : consultation d'une notification
            AuditLogger::log(
                event: 'notification_read',
                target: null,
                old: null,
                new: null,
                meta: [
                    'notification_id' => $notification->id,
                    'type'            => $notification->type,
                    'user_id'         => Auth::id(),
                ]
            );

            // Réponse adaptée selon le type de requête (AJAX ou formulaire classique)
            if ($request->expectsJson()) {
                return response()->json(['status' => 'success']);
            }

            return redirect()
                ->back()
                ->with('success', 'La notification a été marquée comme lue.');
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
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        AuditLogger::log(
            event: 'notifications_mark_all_read',
            target: null,
            old: null,
            new: null,
            meta: [
                'user_id' => $user->id,
            ]
        );

        return redirect()
            ->route('notifications.index')
            ->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /**
     * Suppression d'une notification.
     */
    public function destroy(Request $request, DatabaseNotification $notification)
    {
        if (Auth::id() === $notification->notifiable_id) {
            $notification->delete();

            if ($request->expectsJson()) {
                return response()->json(['status' => 'success']);
            }

            return redirect()
                ->back()
                ->with('success', 'La notification a été supprimée.');
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Action non autorisée.',
        ], 403);
    }

    /**
     * Endpoint JSON pour le rafraîchissement quasi temps réel de la cloche.
     */
    public function poll(): JsonResponse
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'count' => 0,
                'notifications' => [],
            ], 401);
        }

        $unread = $user->unreadNotifications()
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $notifications = $unread->map(function (DatabaseNotification $notification) {
            $data = $notification->data ?? [];
            $type = $data['type'] ?? class_basename($notification->type);

            return [
                'id'               => $notification->id,
                'type'             => $type,
                'message'          => $data['message'] ?? 'Notification',
                'user_name'        => $data['user_name'] ?? null,
                'user_email'       => $data['user_email'] ?? null,
                'url'              => $data['url'] ?? null,
                'created_at_human' => optional($notification->created_at)->diffForHumans(),
            ];
        });

        return response()->json([
            'count'         => $user->unreadNotifications()->count(),
            'notifications' => $notifications,
        ]);
    }
}
