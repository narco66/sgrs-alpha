<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomReservationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\MeetingTypeController;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\DelegationController;
use App\Http\Controllers\MeetingParticipantController;
use App\Http\Controllers\OrganizationCommitteeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportingController;


// Page d'accueil / tableau de bord SGRS-CEEAC
Route::middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Les routes suivantes existent ou existeront dans les phases ultérieures :
        Route::get('/meetings/create', [MeetingController::class, 'create'])
            ->name('meetings.create');

        Route::get('/rooms/reserve', [RoomReservationController::class, 'create'])
            ->name('rooms.reserve');

        Route::get('/documents/import', [DocumentController::class, 'import'])
            ->name('documents.import');

        Route::get('/participants/create', [ParticipantController::class, 'create'])
            ->name('participants.create');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Routes pour les notifications - EF40, EF41
        Route::get('/notifications', [NotificationController::class, 'index'])
            ->name('notifications.index');
        Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])
            ->name('notifications.markAsRead');
        Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])
            ->name('notifications.markAllAsRead');
        Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])
            ->name('notifications.destroy');

        // Routes pour la gestion des rôles et permissions
        // Route de debug pour vérifier les rôles (à supprimer en production)
        Route::get('/debug/my-roles', function () {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['error' => 'Non connecté'], 401);
            }
            
            $user->load('roles');
            return response()->json([
                'email' => $user->email,
                'name' => $user->name,
                'roles' => $user->roles->pluck('name')->toArray(),
                'has_super_admin' => $user->hasRole('super-admin'),
                'has_dsi' => $user->hasRole('dsi'),
                'can_access_roles' => $user->hasRole('super-admin') || $user->hasRole('dsi'),
            ]);
        })->name('debug.my-roles');
        
        Route::resource('roles', \App\Http\Controllers\RoleController::class);
        Route::post('/roles/{role}/assign-user', [\App\Http\Controllers\RoleController::class, 'assignToUser'])
            ->name('roles.assign-user');
        Route::post('/roles/{role}/remove-user', [\App\Http\Controllers\RoleController::class, 'removeFromUser'])
            ->name('roles.remove-user');

        Route::resource('meetings', MeetingController::class);
        Route::resource('participants', ParticipantController::class);

        Route::post('/meetings/{meeting}/notify', [MeetingController::class, 'notifyParticipants'])
            ->name('meetings.notify');
        
        // Routes PDF pour les réunions
        Route::prefix('meetings/{meeting}')->name('meetings.')->group(function () {
            Route::get('pdf', [MeetingController::class, 'exportPdf'])->name('pdf');
            Route::get('pdf/invitation', [MeetingController::class, 'exportInvitationPdf'])->name('pdf.invitation');
            Route::get('pdf/attendance', [MeetingController::class, 'exportAttendancePdf'])->name('pdf.attendance');
            Route::get('pdf/minutes', [MeetingController::class, 'exportMinutesPdf'])->name('pdf.minutes');
            Route::get('pdf/logistics', [MeetingController::class, 'exportLogisticsPdf'])->name('pdf.logistics');
            Route::get('pdf/agenda', [MeetingController::class, 'exportAgendaPdf'])->name('pdf.agenda');
        });

        // Gestion complète des salles
        Route::resource('rooms', RoomController::class);
        Route::post('rooms/{room}/toggle-status', [RoomController::class, 'toggleStatus'])->name('rooms.toggle-status');
        Route::get('rooms/{room}/availability', [RoomController::class, 'checkAvailability'])->name('rooms.availability');

        Route::resource('documents', DocumentController::class)->except(['edit', 'update']);
        Route::get('documents/{document}/download', [DocumentController::class, 'download'])
            ->name('documents.download');
        Route::post('documents/{document}/version', [DocumentController::class, 'uploadVersion'])
            ->name('documents.upload-version');
        Route::post('documents/{document}/validate', [DocumentController::class, 'validateDocument'])
            ->name('documents.validate');

        Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
        Route::get('calendar/day', [CalendarController::class, 'day'])->name('calendar.day');
        Route::get('calendar/week', [CalendarController::class, 'week'])->name('calendar.week');
        Route::get('calendar/month', [CalendarController::class, 'month'])->name('calendar.month');
        Route::get('calendar/year', [CalendarController::class, 'year'])->name('calendar.year');

        Route::get('audit-logs', [AuditLogController::class, 'index'])
            ->name('audit-logs.index');

        Route::resource('meeting-types', MeetingTypeController::class);
        Route::resource('committees', CommitteeController::class);
        Route::resource('delegations', DelegationController::class);
        Route::get('delegations/{delegation}/pdf', [DelegationController::class, 'exportPdf'])
            ->name('delegations.pdf');
        Route::post('delegations/{delegation}/confirm', [DelegationController::class, 'confirm'])
            ->name('delegations.confirm');
        
        // Membres de délégation
        Route::prefix('delegations/{delegation}')->name('delegations.')->group(function () {
            Route::resource('members', \App\Http\Controllers\DelegationMemberController::class)
                ->except(['index', 'show']);
            Route::get('members', [\App\Http\Controllers\DelegationMemberController::class, 'index'])
                ->name('members.index');
            Route::patch('members/{member}/status', [\App\Http\Controllers\DelegationMemberController::class, 'updateStatus'])
                ->name('members.update-status');
            Route::get('members/{member}/badge', [\App\Http\Controllers\DelegationMemberController::class, 'exportBadgePdf'])
                ->name('members.badge');
            Route::get('badges', [\App\Http\Controllers\DelegationMemberController::class, 'exportAllBadgesPdf'])
                ->name('badges');
        });
        
        // Cahier des charges
        Route::prefix('meetings/{meeting}')->name('terms-of-reference.')->group(function () {
            Route::get('terms-of-reference', [\App\Http\Controllers\TermsOfReferenceController::class, 'show'])
                ->name('show');
            Route::get('terms-of-reference/create', [\App\Http\Controllers\TermsOfReferenceController::class, 'create'])
                ->name('create');
            Route::post('terms-of-reference', [\App\Http\Controllers\TermsOfReferenceController::class, 'store'])
                ->name('store');
            Route::get('terms-of-reference/{termsOfReference}/edit', [\App\Http\Controllers\TermsOfReferenceController::class, 'edit'])
                ->name('edit');
            Route::put('terms-of-reference/{termsOfReference}', [\App\Http\Controllers\TermsOfReferenceController::class, 'update'])
                ->name('update');
            Route::post('terms-of-reference/{termsOfReference}/validate', [\App\Http\Controllers\TermsOfReferenceController::class, 'validateTerms'])
                ->name('validate');
            Route::post('terms-of-reference/{termsOfReference}/sign', [\App\Http\Controllers\TermsOfReferenceController::class, 'sign'])
                ->name('sign');
            Route::get('terms-of-reference/{termsOfReference}/pdf', [\App\Http\Controllers\TermsOfReferenceController::class, 'exportPdf'])
                ->name('pdf');
            Route::get('terms-of-reference/{termsOfReference}/download-signed', [\App\Http\Controllers\TermsOfReferenceController::class, 'downloadSignedDocument'])
                ->name('download-signed');
            Route::post('terms-of-reference/{termsOfReference}/version', [\App\Http\Controllers\TermsOfReferenceController::class, 'createVersion'])
                ->name('create-version');
        });
        Route::get('organization-committees/{organizationCommittee}/pdf', [OrganizationCommitteeController::class, 'exportPdf'])
            ->name('organization-committees.pdf');
        Route::resource('document-types', \App\Http\Controllers\DocumentTypeController::class);
        
        // Gestion des utilisateurs (EF03-EF08)
        Route::resource('users', UserController::class);
        Route::post('users/{user}/toggle-active', [UserController::class, 'toggleActive'])
            ->name('users.toggle-active');

        // Changement de statut (workflow)
        Route::post('meetings/{meeting}/status', [MeetingController::class, 'changeStatus'])
            ->name('meetings.change-status');
        Route::prefix('meetings/{meeting}')->group(function () {
            Route::get('participants', [MeetingParticipantController::class, 'index'])
                ->name('meetings.participants.index');

            Route::post('participants', [MeetingParticipantController::class, 'store'])
                ->name('meetings.participants.store');

            Route::patch('participants/{participant}/status', [MeetingParticipantController::class, 'updateStatus'])
                ->name('meetings.participants.update-status');

            Route::delete('participants/{participant}', [MeetingParticipantController::class, 'destroy'])
                ->name('meetings.participants.destroy');
        });
        
        // Reporting et statistiques (EF44-EF48)
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportingController::class, 'index'])->name('index');
            Route::get('meetings', [ReportingController::class, 'meetings'])->name('meetings');
            Route::get('participants', [ReportingController::class, 'participants'])->name('participants');
            Route::get('documents', [ReportingController::class, 'documents'])->name('documents');
            Route::get('performance', [ReportingController::class, 'performance'])->name('performance');
            Route::get('export/{reportType}/{format?}', [ReportingController::class, 'export'])
                ->where(['format' => 'pdf|excel'])
                ->name('export');
        });

        // Matrice RACI - Section 5.2 du cahier des charges
        Route::get('raci', [\App\Http\Controllers\RaciController::class, 'index'])->name('raci.index');

        // Comités d'organisation (EF20)
        Route::resource('organization-committees', \App\Http\Controllers\OrganizationCommitteeController::class);

        // Demandes de réunion (UC35-UC36)
        Route::resource('meeting-requests', \App\Http\Controllers\MeetingRequestController::class);
        Route::post('meeting-requests/{meetingRequest}/approve', [\App\Http\Controllers\MeetingRequestController::class, 'approve'])
            ->name('meeting-requests.approve');
        Route::post('meeting-requests/{meetingRequest}/reject', [\App\Http\Controllers\MeetingRequestController::class, 'reject'])
            ->name('meeting-requests.reject');

        // Demandes d'ajout de participants (UC37-UC38)
        Route::resource('participant-requests', \App\Http\Controllers\ParticipantRequestController::class);
        Route::post('participant-requests/{participantRequest}/approve', [\App\Http\Controllers\ParticipantRequestController::class, 'approve'])
            ->name('participant-requests.approve');
        Route::post('participant-requests/{participantRequest}/reject', [\App\Http\Controllers\ParticipantRequestController::class, 'reject'])
            ->name('participant-requests.reject');
    });

require __DIR__.'/auth.php';
