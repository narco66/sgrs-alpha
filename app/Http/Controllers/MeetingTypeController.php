<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMeetingTypeRequest;
use App\Http\Requests\UpdateMeetingTypeRequest;
use App\Models\MeetingType;
use Illuminate\Http\Request;

class MeetingTypeController extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Vérification des permissions via middleware
        $this->middleware('permission:meeting_types.view')->only(['index', 'show']);
        $this->middleware('permission:meeting_types.create')->only(['create', 'store']);
        $this->middleware('permission:meeting_types.update')->only(['edit', 'update']);
        $this->middleware('permission:meeting_types.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $search = $request->get('q');

        $types = MeetingType::query()
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('meeting_types.index', compact('types', 'search'));
    }

    public function create()
    {
        return view('meeting_types.create');
    }

    public function store(StoreMeetingTypeRequest $request)
    {
        MeetingType::create([
            'name'                       => $request->input('name'),
            'code'                       => strtoupper($request->input('code')),
            'color'                      => $request->input('color'),
            'sort_order'                 => $request->input('sort_order', 0),
            'requires_president_approval'=> $request->boolean('requires_president_approval'),
            'requires_sg_approval'       => $request->boolean('requires_sg_approval', true),
            'description'                => $request->input('description'),
            'is_active'                  => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('meeting-types.index')
            ->with('success', 'Le type de réunion a été créé avec succès.');
    }

    public function show(MeetingType $meetingType)
    {
        return view('meeting_types.show', compact('meetingType'));
    }

    public function edit(MeetingType $meetingType)
    {
        return view('meeting_types.edit', compact('meetingType'));
    }

    public function update(UpdateMeetingTypeRequest $request, MeetingType $meetingType)
    {
        $meetingType->update([
            'name'                       => $request->input('name'),
            'code'                       => strtoupper($request->input('code')),
            'color'                      => $request->input('color'),
            'sort_order'                 => $request->input('sort_order', 0),
            'requires_president_approval'=> $request->boolean('requires_president_approval'),
            'requires_sg_approval'       => $request->boolean('requires_sg_approval', true),
            'description'                => $request->input('description'),
            'is_active'                  => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('meeting-types.index')
            ->with('success', 'Le type de réunion a été mis à jour.');
    }

    public function destroy(MeetingType $meetingType)
    {
        $meetingType->delete();

        return redirect()
            ->route('meeting-types.index')
            ->with('success', 'Le type de réunion a été supprimé.');
    }
}
