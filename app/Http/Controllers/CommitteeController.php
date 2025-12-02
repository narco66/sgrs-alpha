<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommitteeRequest;
use App\Http\Requests\UpdateCommitteeRequest;
use App\Models\Committee;
use App\Models\MeetingType;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q');

        $committees = Committee::with('meetingType')
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('committees.index', compact('committees', 'search'));
    }

    public function create()
    {
        $meetingTypes = MeetingType::orderBy('sort_order')->orderBy('name')->get();

        return view('committees.create', compact('meetingTypes'));
    }

    public function store(StoreCommitteeRequest $request)
    {
        Committee::create([
            'name'            => $request->input('name'),
            'code'            => strtoupper($request->input('code')),
            'meeting_type_id' => $request->input('meeting_type_id'),
            'is_permanent'    => $request->boolean('is_permanent', true),
            'is_active'       => $request->boolean('is_active', true),
            'description'     => $request->input('description'),
            'sort_order'      => $request->input('sort_order', 0),
        ]);

        return redirect()
            ->route('committees.index')
            ->with('success', 'Le comité a été créé avec succès.');
    }

    public function show(Committee $committee)
    {
        $committee->load('meetingType');

        return view('committees.show', compact('committee'));
    }

    public function edit(Committee $committee)
    {
        $meetingTypes = MeetingType::orderBy('sort_order')->orderBy('name')->get();

        return view('committees.edit', compact('committee', 'meetingTypes'));
    }

    public function update(UpdateCommitteeRequest $request, Committee $committee)
    {
        $committee->update([
            'name'            => $request->input('name'),
            'code'            => strtoupper($request->input('code')),
            'meeting_type_id' => $request->input('meeting_type_id'),
            'is_permanent'    => $request->boolean('is_permanent', true),
            'is_active'       => $request->boolean('is_active', true),
            'description'     => $request->input('description'),
            'sort_order'      => $request->input('sort_order', 0),
        ]);

        return redirect()
            ->route('committees.index')
            ->with('success', 'Le comité a été mis à jour.');
    }

    public function destroy(Committee $committee)
    {
        $committee->delete();

        return redirect()
            ->route('committees.index')
            ->with('success', 'Le comité a été supprimé.');
    }
}
