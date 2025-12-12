<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function __construct()
    {
        // Liaison automatique avec RoomPolicy
        $this->authorizeResource(Room::class, 'room');
    }

    /**
     * Liste des salles de réunion avec filtres.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $search = $request->get('q');
        $capacity = $request->get('capacity');

        $query = Room::query()
            ->with(['meetings' => function ($q) {
                // Charger uniquement les réunions pertinentes (aujourd'hui et futures non annulées)
                $q->where('status', '!=', 'annulee')
                  ->where(function ($sub) {
                      $sub->whereDate('start_at', '>=', today())
                          ->orWhere('end_at', '>=', now());
                  })
                  ->orderBy('start_at');
            }]);

        // Filtre par recherche
        $query->when($search, function ($q) use ($search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        });

        // Filtre par capacité minimale
        $query->when($capacity, function ($q) use ($capacity) {
            $q->where('capacity', '>=', (int) $capacity);
        });

        // Filtre par disponibilité
        if ($filter === 'available') {
            $query->where('is_active', true)->currentlyFree();
        } elseif ($filter === 'occupied') {
            $query->currentlyOccupied();
        }

        // Ne montrer que les salles actives par défaut (sauf si admin)
        if (!auth()->user()?->can('viewAny', Room::class)) {
            $query->where('is_active', true);
        }

        $rooms = $query
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        // Statistiques pour l'en-tête
        $stats = [
            'total' => Room::count(),
            'available' => Room::where('is_active', true)->currentlyFree()->count(),
            'occupied' => Room::currentlyOccupied()->count(),
        ];

        return view('rooms.index', [
            'rooms'   => $rooms,
            'filter'  => $filter,
            'stats'   => $stats,
            'filters' => [
                'q'        => $search,
                'capacity' => $capacity,
                'filter'   => $filter,
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
            'equipmentOptions' => Room::$equipmentLabels,
        ]);
    }

    /**
     * Enregistrement d'une salle.
     */
    public function store(StoreRoomRequest $request)
    {
        $data = $request->validated();

        // Gestion de l'upload d'image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('rooms', 'public');
        }

        $room = Room::create([
            'name'        => $data['name'],
            'code'        => strtoupper($data['code']),
            'capacity'    => $data['capacity'],
            'location'    => $data['location'] ?? null,
            'description' => $data['description'] ?? null,
            'image'       => $imagePath,
            'equipments'  => $request->input('equipments', []),
            'is_active'   => $request->boolean('is_active', true),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'id'     => $room->id,
                'name'   => $room->name,
            ]);
        }

        return redirect()
            ->route('rooms.index')
            ->with('success', 'La salle de réunion a été créée avec succès.');
    }

    /**
     * Fiche détaillée d'une salle.
     */
    public function show(Room $room)
    {
        // Charger les réunions à venir
        $room->load(['meetings' => function ($q) {
            $q->where('status', '!=', 'annulee')
              ->where('start_at', '>=', now())
              ->orderBy('start_at')
              ->limit(10);
        }]);

        // Réunions passées (pour historique)
        $pastMeetings = $room->meetings()
            ->where('status', '!=', 'annulee')
            ->where('start_at', '<', now())
            ->orderByDesc('start_at')
            ->limit(5)
            ->get();

        return view('rooms.show', [
            'room' => $room,
            'upcomingMeetings' => $room->meetings,
            'pastMeetings' => $pastMeetings,
            'equipmentOptions' => Room::$equipmentLabels,
        ]);
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(Room $room)
    {
        return view('rooms.edit', [
            'room' => $room,
            'equipmentOptions' => Room::$equipmentLabels,
        ]);
    }

    /**
     * Mise à jour.
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        $data = $request->validated();

        // Gestion de l'upload d'image
        $imagePath = $room->image;
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }
            $imagePath = $request->file('image')->store('rooms', 'public');
        }

        // Suppression d'image si demandé
        if ($request->boolean('remove_image') && $room->image) {
            Storage::disk('public')->delete($room->image);
            $imagePath = null;
        }

        $room->update([
            'name'        => $data['name'],
            'code'        => strtoupper($data['code']),
            'capacity'    => $data['capacity'],
            'location'    => $data['location'] ?? null,
            'description' => $data['description'] ?? null,
            'image'       => $imagePath,
            'equipments'  => $request->input('equipments', []),
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
        // Vérifier s'il y a des réunions futures
        $futureMeetings = $room->meetings()
            ->where('status', '!=', 'annulee')
            ->where('start_at', '>', now())
            ->count();

        if ($futureMeetings > 0) {
            return back()->with('error', "Impossible de supprimer cette salle : {$futureMeetings} réunion(s) sont encore programmées.");
        }

        $room->delete();

        return redirect()
            ->route('rooms.index')
            ->with('success', 'La salle de réunion a été supprimée.');
    }

    /**
     * Désactiver/Activer une salle.
     */
    public function toggleStatus(Room $room)
    {
        $this->authorize('update', $room);

        $room->update(['is_active' => !$room->is_active]);

        $status = $room->is_active ? 'activée' : 'désactivée';
        return back()->with('success', "La salle a été {$status}.");
    }

    /**
     * API : Vérifier la disponibilité d'une salle.
     */
    public function checkAvailability(Request $request, Room $room)
    {
        $request->validate([
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'exclude_meeting_id' => 'nullable|integer',
        ]);

        $isAvailable = $room->isAvailableFor(
            $request->input('start_at'),
            $request->input('end_at'),
            $request->input('exclude_meeting_id')
        );

        return response()->json([
            'available' => $isAvailable,
            'room' => [
                'id' => $room->id,
                'name' => $room->name,
            ],
        ]);
    }
}
