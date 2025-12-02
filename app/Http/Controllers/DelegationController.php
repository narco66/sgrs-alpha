<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDelegationRequest;
use App\Http\Requests\UpdateDelegationRequest;
use App\Models\Delegation;
use App\Models\Meeting;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class DelegationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Delegation::class, 'delegation');
    }

    public function index(Request $request)
    {
        $search = $request->get('q');

        $delegations = Delegation::withCount('users')
            ->with('meeting')
            ->when($search, fn($q) => $q->search($search))
            ->orderBy('created_at', 'desc')
            ->orderBy('title')
            ->paginate(15)
            ->withQueryString();

        return view('delegations.index', compact('delegations', 'search'));
    }

    public function create()
    {
        return view('delegations.create', [
            'delegation' => new Delegation(),
            'meetings'   => Meeting::orderByDesc('start_at')->take(50)->get(),
            'users'      => User::orderBy('name')->get(),
        ]);
    }

    public function store(StoreDelegationRequest $request)
    {
        $data = $request->validated();
        $participants = $data['participants'] ?? [];
        unset($data['participants']);

        $delegation = Delegation::create($data);
        $delegation->participants()->sync($participants);

        return redirect()
            ->route('delegations.index')
            ->with('success', 'La delegation a ete creee avec succes.');
    }

    public function show(Delegation $delegation)
    {
        $delegation->load(['users', 'meeting', 'participants']);

        return view('delegations.show', compact('delegation'));
    }

    public function edit(Delegation $delegation)
    {
        $delegation->load(['users', 'meeting', 'participants']);

        return view('delegations.edit', [
            'delegation' => $delegation,
            'meetings'   => Meeting::orderByDesc('start_at')->take(50)->get(),
            'users'      => User::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateDelegationRequest $request, Delegation $delegation)
    {
        $data = $request->validated();
        $participants = $data['participants'] ?? [];
        unset($data['participants']);

        $delegation->update($data);
        $delegation->participants()->sync($participants);

        return redirect()
            ->route('delegations.show', $delegation)
            ->with('success', 'La delegation a ete mise a jour avec succes.');
    }

    public function destroy(Delegation $delegation)
    {
        if ($delegation->users()->count() > 0) {
            return redirect()
                ->route('delegations.index')
                ->with('error', 'Impossible de supprimer cette delegation car elle contient des utilisateurs.');
        }

        $delegation->delete();

        return redirect()
            ->route('delegations.index')
            ->with('success', 'La delegation a ete supprimee avec succes.');
    }

    /**
     * Export PDF des d�tails d'une delegation (infos, reunion, participants, utilisateurs).
     */
    public function exportPdf(Delegation $delegation)
    {
        $this->authorize('view', $delegation);

        $delegation->load([
            'meeting',
            'participants',
            'users',
        ]);

        $pdf = Pdf::loadView('delegations.pdf', [
            'delegation' => $delegation,
        ])->setPaper('A4', 'portrait');

        $fileName = 'delegation-' . ($delegation->code ?? $delegation->id) . '.pdf';

        return $pdf->download($fileName);
    }
}
