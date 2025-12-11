<?php

namespace App\Providers;

use App\Events\DocumentSubmitted;
use App\Events\DocumentValidated;
use App\Events\MeetingCancelled;
use App\Events\MeetingCreated;
use App\Events\MeetingInvitationsRequested;
use App\Events\MeetingStatusChanged;
use App\Events\MeetingUpdated;
use App\Events\ParticipantRsvpUpdated;
use App\Events\UserCreated;
use App\Events\UserUpdated;
use App\Listeners\DispatchNotificationListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        UserCreated::class => [
            DispatchNotificationListener::class,
        ],
        UserUpdated::class => [
            DispatchNotificationListener::class,
        ],
        MeetingCreated::class => [
            DispatchNotificationListener::class,
        ],
        MeetingUpdated::class => [
            DispatchNotificationListener::class,
        ],
        MeetingCancelled::class => [
            DispatchNotificationListener::class,
        ],
        MeetingInvitationsRequested::class => [
            DispatchNotificationListener::class,
        ],
        MeetingStatusChanged::class => [
            DispatchNotificationListener::class,
        ],
        ParticipantRsvpUpdated::class => [
            DispatchNotificationListener::class,
        ],
        DocumentSubmitted::class => [
            DispatchNotificationListener::class,
        ],
        DocumentValidated::class => [
            DispatchNotificationListener::class,
        ],
    ];
}




