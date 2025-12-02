<?php

namespace App\Providers;

use App\Models\Meeting;
use App\Policies\MeetingPolicy;
use App\Policies\RolePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Meeting::class => MeetingPolicy::class,
        \App\Models\Participant::class => \App\Policies\ParticipantPolicy::class,
        \App\Models\Document::class => \App\Policies\DocumentPolicy::class,
        \App\Models\AuditLog::class => \App\Policies\AuditLogPolicy::class,
        \App\Models\Room::class => \App\Policies\RoomPolicy::class,
        \App\Models\DocumentType::class => \App\Policies\DocumentTypePolicy::class,
        \App\Models\Delegation::class => \App\Policies\DelegationPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\OrganizationCommittee::class => \App\Policies\OrganizationCommitteePolicy::class,
        \App\Models\MeetingRequest::class => \App\Policies\MeetingRequestPolicy::class,
        \App\Models\ParticipantRequest::class => \App\Policies\ParticipantRequestPolicy::class,
        Role::class => RolePolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
