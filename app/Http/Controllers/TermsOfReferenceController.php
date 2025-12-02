<?php

namespace App\Http\Controllers;

use App\Models\TermsOfReference;
use App\Models\Meeting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TermsOfReferenceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Affiche le cahier des charges d'une réunion
     */
    public function show(Meeting $meeting)
    {
        $this->authorize('view', $meeting);

        $termsOfReference = $meeting->termsOfReference;
        
        if (!$termsOfReference) {
            return redirect()
                ->route('meetings.show', $meeting)
                ->with('info', 'Aucun cahier des charges n\'a encore été créé pour cette réunion.');
        }

        $termsOfReference->load([
            'meeting',
            'validator',
            'signerCeeac',
            'signedDocumentUploader',
            'previousVersion',
            'nextVersions',
        ]);

        return view('terms-of-reference.show', [
            'meeting' => $meeting,
            'termsOfReference' => $termsOfReference,
        ]);
    }

    /**
     * Formulaire de création d'un cahier des charges
     */
    public function create(Meeting $meeting)
    {
        $this->authorize('update', $meeting);

        // Vérifier qu'il n'existe pas déjà un cahier des charges actif
        if ($meeting->termsOfReference) {
            return redirect()
                ->route('meetings.show', $meeting)
                ->with('info', 'Un cahier des charges existe déjà pour cette réunion.');
        }

        return view('terms-of-reference.create', [
            'meeting' => $meeting,
            'termsOfReference' => new TermsOfReference(),
        ]);
    }

    /**
     * Enregistrement d'un nouveau cahier des charges
     */
    public function store(Request $request, Meeting $meeting)
    {
        $this->authorize('update', $meeting);

        $validated = $request->validate([
            'host_country' => ['required', 'string', 'max:255'],
            'signature_date' => ['nullable', 'date'],
            'effective_from' => ['nullable', 'date'],
            'effective_until' => ['nullable', 'date'],
            'responsibilities_ceeac' => ['required', 'string'],
            'responsibilities_host' => ['required', 'string'],
            'financial_sharing' => ['nullable', 'string'],
            'logistical_sharing' => ['nullable', 'string'],
            'obligations_ceeac' => ['nullable', 'string'],
            'obligations_host' => ['nullable', 'string'],
            'additional_terms' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'signed_document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // 10 MB max
        ]);

        $validated['meeting_id'] = $meeting->id;
        $validated['status'] = TermsOfReference::STATUS_DRAFT;
        $validated['version'] = 1;

        // Gestion de l'upload du document signé
        if ($request->hasFile('signed_document')) {
            $file = $request->file('signed_document');
            $extension = strtolower($file->getClientOriginalExtension());
            $storedName = Str::uuid()->toString() . '.' . $extension;
            $path = $file->storeAs('cahiers-charges/signed', $storedName, 'public');
            
            $validated['signed_document_path'] = $path;
            $validated['signed_document_name'] = $storedName;
            $validated['signed_document_original_name'] = $file->getClientOriginalName();
            $validated['signed_document_size'] = $file->getSize();
            $validated['signed_document_mime_type'] = $file->getMimeType();
            $validated['signed_document_extension'] = $extension;
            $validated['signed_document_uploaded_at'] = now();
            $validated['signed_document_uploaded_by'] = Auth::id();
        }

        $termsOfReference = TermsOfReference::create($validated);

        return redirect()
            ->route('terms-of-reference.show', $meeting)
            ->with('success', 'Le cahier des charges a été créé avec succès.');
    }

    /**
     * Formulaire d'édition d'un cahier des charges
     */
    public function edit(Meeting $meeting, TermsOfReference $termsOfReference)
    {
        $this->authorize('update', $meeting);

        if ($termsOfReference->meeting_id !== $meeting->id) {
            abort(404);
        }

        return view('terms-of-reference.edit', [
            'meeting' => $meeting,
            'termsOfReference' => $termsOfReference,
        ]);
    }

    /**
     * Mise à jour d'un cahier des charges
     */
    public function update(Request $request, Meeting $meeting, TermsOfReference $termsOfReference)
    {
        $this->authorize('update', $meeting);

        if ($termsOfReference->meeting_id !== $meeting->id) {
            abort(404);
        }

        // Si le cahier est signé, créer une nouvelle version
        if ($termsOfReference->isSigned()) {
            $termsOfReference = $termsOfReference->createNewVersion();
        }

        $validated = $request->validate([
            'host_country' => ['required', 'string', 'max:255'],
            'signature_date' => ['nullable', 'date'],
            'effective_from' => ['nullable', 'date'],
            'effective_until' => ['nullable', 'date'],
            'responsibilities_ceeac' => ['required', 'string'],
            'responsibilities_host' => ['required', 'string'],
            'financial_sharing' => ['nullable', 'string'],
            'logistical_sharing' => ['nullable', 'string'],
            'obligations_ceeac' => ['nullable', 'string'],
            'obligations_host' => ['nullable', 'string'],
            'additional_terms' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'signed_document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // 10 MB max
            'remove_signed_document' => ['nullable', 'boolean'],
        ]);

        // Suppression du document signé si demandé
        if ($request->boolean('remove_signed_document') && $termsOfReference->signed_document_path) {
            if (Storage::disk('public')->exists($termsOfReference->signed_document_path)) {
                Storage::disk('public')->delete($termsOfReference->signed_document_path);
            }
            $validated['signed_document_path'] = null;
            $validated['signed_document_name'] = null;
            $validated['signed_document_original_name'] = null;
            $validated['signed_document_size'] = null;
            $validated['signed_document_mime_type'] = null;
            $validated['signed_document_extension'] = null;
            $validated['signed_document_uploaded_at'] = null;
            $validated['signed_document_uploaded_by'] = null;
        }

        // Gestion de l'upload d'un nouveau document signé
        if ($request->hasFile('signed_document')) {
            // Supprimer l'ancien document s'il existe
            if ($termsOfReference->signed_document_path && Storage::disk('public')->exists($termsOfReference->signed_document_path)) {
                Storage::disk('public')->delete($termsOfReference->signed_document_path);
            }
            
            $file = $request->file('signed_document');
            $extension = strtolower($file->getClientOriginalExtension());
            $storedName = Str::uuid()->toString() . '.' . $extension;
            $path = $file->storeAs('cahiers-charges/signed', $storedName, 'public');
            
            $validated['signed_document_path'] = $path;
            $validated['signed_document_name'] = $storedName;
            $validated['signed_document_original_name'] = $file->getClientOriginalName();
            $validated['signed_document_size'] = $file->getSize();
            $validated['signed_document_mime_type'] = $file->getMimeType();
            $validated['signed_document_extension'] = $extension;
            $validated['signed_document_uploaded_at'] = now();
            $validated['signed_document_uploaded_by'] = Auth::id();
        }

        $termsOfReference->update($validated);

        return redirect()
            ->route('terms-of-reference.show', $meeting)
            ->with('success', 'Le cahier des charges a été mis à jour avec succès.');
    }

    /**
     * Validation interne du cahier des charges
     */
    public function validateTerms(Request $request, Meeting $meeting, TermsOfReference $termsOfReference)
    {
        $this->authorize('update', $meeting);

        if ($termsOfReference->meeting_id !== $meeting->id) {
            abort(404);
        }

        if ($termsOfReference->status !== TermsOfReference::STATUS_DRAFT 
            && $termsOfReference->status !== TermsOfReference::STATUS_PENDING_VALIDATION) {
            return back()->with('error', 'Ce cahier des charges ne peut pas être validé dans son état actuel.');
        }

        $termsOfReference->update([
            'status' => TermsOfReference::STATUS_VALIDATED,
            'validated_by' => Auth::id(),
            'validated_at' => now(),
        ]);

        return back()->with('success', 'Le cahier des charges a été validé avec succès.');
    }

    /**
     * Signature du cahier des charges
     */
    public function sign(Request $request, Meeting $meeting, TermsOfReference $termsOfReference)
    {
        $this->authorize('update', $meeting);

        if ($termsOfReference->meeting_id !== $meeting->id) {
            abort(404);
        }

        if (!$termsOfReference->isValidated()) {
            return back()->with('error', 'Le cahier des charges doit être validé avant d\'être signé.');
        }

        $validated = $request->validate([
            'signed_by_host_name' => ['required', 'string', 'max:255'],
            'signed_by_host_position' => ['required', 'string', 'max:255'],
            'signature_date' => ['nullable', 'date'],
        ]);

        $termsOfReference->update([
            'status' => TermsOfReference::STATUS_SIGNED,
            'signed_by_ceeac' => Auth::id(),
            'signed_by_host_name' => $validated['signed_by_host_name'],
            'signed_by_host_position' => $validated['signed_by_host_position'],
            'signature_date' => $validated['signature_date'] ?? now(),
            'signed_at' => now(),
        ]);

        return back()->with('success', 'Le cahier des charges a été signé avec succès.');
    }

    /**
     * Génération du PDF du cahier des charges
     */
    public function exportPdf(Meeting $meeting, TermsOfReference $termsOfReference)
    {
        $this->authorize('view', $meeting);

        if ($termsOfReference->meeting_id !== $meeting->id) {
            abort(404);
        }

        $termsOfReference->load([
            'meeting',
            'validator',
            'signerCeeac',
        ]);

        $pdf = Pdf::loadView('terms-of-reference.pdf', [
            'meeting' => $meeting,
            'termsOfReference' => $termsOfReference,
        ])->setPaper('A4', 'portrait');

        $fileName = 'cahier-des-charges-' . $meeting->slug . '-v' . $termsOfReference->version . '.pdf';

        // Sauvegarder le PDF
        $pdfPath = 'cahiers-charges/' . $fileName;
        Storage::disk('public')->put($pdfPath, $pdf->output());
        
        $termsOfReference->update(['pdf_path' => $pdfPath]);

        return $pdf->download($fileName);
    }

    /**
     * Création d'une nouvelle version du cahier des charges
     */
    public function createVersion(Request $request, Meeting $meeting, TermsOfReference $termsOfReference)
    {
        $this->authorize('update', $meeting);

        if ($termsOfReference->meeting_id !== $meeting->id) {
            abort(404);
        }

        $newVersion = $termsOfReference->createNewVersion();

        return redirect()
            ->route('terms-of-reference.edit', [$meeting, $newVersion])
            ->with('success', 'Une nouvelle version du cahier des charges a été créée.');
    }

    /**
     * Téléchargement du document signé
     */
    public function downloadSignedDocument(Meeting $meeting, TermsOfReference $termsOfReference)
    {
        $this->authorize('view', $meeting);

        if ($termsOfReference->meeting_id !== $meeting->id) {
            abort(404);
        }

        if (!$termsOfReference->signed_document_path) {
            return back()->with('error', 'Aucun document signé n\'a été uploadé pour ce cahier des charges.');
        }

        if (!Storage::disk('public')->exists($termsOfReference->signed_document_path)) {
            return back()->with('error', 'Le fichier du document signé est introuvable.');
        }

        return Storage::disk('public')->download(
            $termsOfReference->signed_document_path,
            $termsOfReference->signed_document_original_name ?? 'document-signe.pdf'
        );
    }
}

