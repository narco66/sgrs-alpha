<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function __construct()
    {
        // Liaison automatique avec RoomPolicy
        $this->authorizeResource(Room::class, 'room');
    }

    /**
     * Liste des salles de réunion.
     */
    public function index(Request $request)
    {
        $search   = $request->get('q');
        $capacity = $request->get('capacity');
        $status   = $request->get('status'); // 'active' / 'inactive' / null

        $rooms = Room::query()
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            })
            ->when($capacity, function ($q) use ($capacity) {
                $q->where('capacity', '>=', (int)$capacity);
            })
            ->when($status === 'active', fn($q) => $q->where('is_active', true))
            ->when($status === 'inactive', fn($q) => $q->where('is_active', false))
            ->orderBy('created_at', 'desc')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('rooms.index', [
            'rooms'   => $rooms,
            'filters' => [
                'q'        => $search,
                'capacity' => $capacity,
                'status'   => $status,
            ],
        ]);
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        return view('rooms.create', [
            'room' => new Room(),
        ]);
    }

    /**
     * Enregistrement d’une salle.
     */
    public function store(StoreRoomRequest $request)
    {
        $data = $request->validated();

        Room::create([
            'name'        => $data['name'],
            'code'        => strtoupper($data['code']),
            'capacity'    => $data['capacity'],
            'location'    => $data['location'] ?? null,
            'description' => $data['description'] ?? null,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('rooms.index')
            ->with('success', 'La salle de réunion a été créée avec succès.');
    }

    /**
     * Fiche détaillée d’une salle.
     */
    public function show(Room $room)
    {
        // Ici, on pourrait charger les réservations / réunions associées
        // $room->load('meetings');

        return view('rooms.show', [
            'room' => $room,
        ]);
    }

    /**
     * Formulaire d’édition.
     */
    public function edit(Room $room)
    {
        return view('rooms.edit', [
            'room' => $room,
        ]);
    }

    /**
     * Mise à jour.
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        $data = $request->validated();

        $room->update([
            'name'        => $data['name'],
            'code'        => strtoupper($data['code']),
            'capacity'    => $data['capacity'],
            'location'    => $data['location'] ?? null,
            'description' => $data['description'] ?? null,
            'is_active'   => $request->boolean('is_active', $room->is_active),
        ]);

        return redirect()
            ->route('rooms.show', $room)
            ->with('success', 'La salle de réunion a été mise à jour avec succès.');
    }

    /**
     * Suppression (soft delete).
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()
            ->route('rooms.index')
            ->with('success', 'La salle de réunion a été supprimée.');
    }
}
