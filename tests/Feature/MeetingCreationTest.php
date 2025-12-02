<?php

use App\Models\Meeting;
use App\Models\MeetingType;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('permet à un utilisateur autorisé de créer une réunion', function () {
    $user = User::factory()->create();
    // On suppose que les permissions sont déjà attribuées
    $user->givePermissionTo('meetings.create');

    $this->actingAs($user);

    $type = MeetingType::factory()->create();
    $room = Room::factory()->create();

    $response = $this->post(route('meetings.store'), [
        'title'            => 'Conseil des Ministres Sectoriel',
        'meeting_type_id'  => $type->id,
        'date'             => '2025-07-15',
        'time'             => '10:30',
        'duration_minutes' => 90,
        'configuration'    => 'hybride',
        'room_id'          => $room->id,
    ]);

    $response->assertRedirect(route('meetings.index'));
    $this->assertDatabaseHas('meetings', [
        'title'           => 'Conseil des Ministres Sectoriel',
        'meeting_type_id' => $type->id,
    ]);
});
