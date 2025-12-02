<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentTypeRequest;
use App\Http\Requests\UpdateDocumentTypeRequest;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentTypeController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(DocumentType::class, 'documentType');
    }

    /**
     * Liste des types de documents
     * EF34-EF36 - Gestion des types de documents
     */
    public function index(Request $request)
    {
        $search = $request->get('q');

        $documentTypes = DocumentType::withCount('documents')
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->ordered()
            ->paginate(5)
            ->withQueryString();

        return view('document-types.index', compact('documentTypes', 'search'));
    }

    /**
     * Formulaire de création
     * EF34 - Créer un nouveau type de document
     */
    public function create()
    {
        return view('document-types.create', [
            'documentType' => new DocumentType(),
        ]);
    }

    /**
     * Enregistrement d'un nouveau type
     * EF34 - Créer un nouveau type de document
     */
    public function store(StoreDocumentTypeRequest $request)
    {
        DocumentType::create($request->validated());

        return redirect()
            ->route('document-types.index')
            ->with('success', 'Le type de document a été créé avec succès.');
    }

    /**
     * Affichage détaillé
     */
    public function show(DocumentType $documentType)
    {
        $documentType->load('documents');
        
        return view('document-types.show', compact('documentType'));
    }

    /**
     * Formulaire d'édition
     * EF36 - Modifier un type de document
     */
    public function edit(DocumentType $documentType)
    {
        return view('document-types.edit', compact('documentType'));
    }

    /**
     * Mise à jour
     * EF36 - Modifier un type de document
     */
    public function update(UpdateDocumentTypeRequest $request, DocumentType $documentType)
    {
        $documentType->update($request->validated());

        return redirect()
            ->route('document-types.show', $documentType)
            ->with('success', 'Le type de document a été mis à jour avec succès.');
    }

    /**
     * Suppression
     * EF35 - Supprimer un type de document
     */
    public function destroy(DocumentType $documentType)
    {
        if ($documentType->documents()->count() > 0) {
            return redirect()
                ->route('document-types.index')
                ->with('error', 'Impossible de supprimer ce type car il est utilisé par des documents.');
        }

        $documentType->delete();

        return redirect()
            ->route('document-types.index')
            ->with('success', 'Le type de document a été supprimé avec succès.');
    }
}

