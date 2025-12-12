<?php

namespace App\Listeners;

use App\Services\NotificationService;

/**
 * Listener générique qui délègue le traitement des événements métier
 * au service centralisé de notifications.
 */
class DispatchNotificationListener
{
    public function __construct(
        protected NotificationService $notifications,
    ) {
    }

    public function handle(object $event): void
    {
        $this->notifications->handleEvent($event);
    }
}








