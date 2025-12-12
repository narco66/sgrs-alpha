<?php

namespace App\Http\Controllers;

use App\Events\DocumentSubmitted;
use App\Events\DocumentValidated;
use App\Http\Requests\StoreDocumentRequest;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\DocumentVersion;
use App\Models\DocumentValidation;
use App\Models\Meeting;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Document::class, 'document');
    }

    public function index(Request $request)
    {
        // EF32 - Recherche multicritères par titre, auteur, type, réunion, date, statut
        $type      = $request->get('type', 'all');
        $extension = $request->get('ext', 'all');
        $search    = $request->get('q');
        $authorId  = $request->get('author_id');
        $meetingId = $request->get('meeting_id');
        $status    = $request->get('validation_status');
        $dateFrom  = $request->get('date_from');
        $dateTo    = $request->get('date_to');
        $documentTypeId = $request->get('document_type_id');

        $query = Document::with(['meeting', 'uploader', 'type'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('original_name', 'like', "%{$search}%")
                        ->orWhereHas('uploader', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                      ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($authorId, fn($q) => $q->where('uploaded_by', $authorId))
            ->when($meetingId, fn($q) => $q->where('meeting_id', $meetingId))
            ->when($status && $status !== 'all', fn($q) => $q->where('validation_status', $status))
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->when($documentTypeId, fn($q) => $q->where('document_type_id', $documentTypeId))
            ->ofType($type)
            ->withExtension($extension)
            ->orderByDesc('created_at');

        $documents = $query->paginate(12)->withQueryString();

        // Statistiques par type
        $statsByType = Document::selectRaw('document_type, count(*) as total')
            ->groupBy('document_type')
            ->pluck('total', 'document_type');

        // Pour les filtres
        $authors = \App\Models\User::whereHas('documents')->orderBy('name')->get();
        // Proposer les r�unions r�centes, m�me si aucun document n'y est encore attach�
        $meetings = \App\Models\Meeting::orderByDesc('start_at')->take(50)->get();
        $documentTypes = \App\Models\DocumentType::active()->ordered()->get();

        return view('documents.index', [
            'documents'     => $documents,
            'statsByType'   => $statsByType,
            'type'          => $type,
            'extension'     => $extension,
            'search'        => $search,
            'authorId'      => $authorId,
            'meetingId'     => $meetingId,
            'status'        => $status,
            'dateFrom'      => $dateFrom,
            'dateTo'        => $dateTo,
            'documentTypeId' => $documentTypeId,
            'authors'       => $authors,
            'meetings'      => $meetings,
            'documentTypes' => $documentTypes,
        ]);
    }

    public function create()
    {
        $meetings = Meeting::orderByDesc('start_at')->take(50)->get();
        $documentTypes = DocumentType::active()->ordered()->get();

        return view('documents.create', compact('meetings', 'documentTypes'));
    }

    public function store(StoreDocumentRequest $request)
    {
        $file = $request->file('file');

        // Validation de la taille (500 MB max)
        if ($file->getSize() > 500 * 1024 * 1024) {
            return back()->withErrors(['file' => 'Le fichier est trop volumineux. Taille maximale: 500 MB']);
        }

        // Validation de l'extension
        $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedExtensions)) {
            return back()->withErrors(['file' => 'Format de fichier non autorisé. Formats acceptés: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX']);
        }

        $storedName = Str::uuid()->toString() . '.' . $extension;
        $path       = $file->storeAs('documents', $storedName, 'public');

        // Déterminer le document_type si document_type_id est fourni
        $documentType = $request->input('document_type', 'autre');
        if ($request->input('document_type_id')) {
            $docType = \App\Models\DocumentType::find($request->input('document_type_id'));
            if ($docType && $docType->code) {
                // Mapper le code du type de document vers document_type si nécessaire
                $documentType = $docType->code;
            }
        }

        // Harmoniser document_type avec l'enum MySQL
        $codeToEnum = [
            'ODJ'     => 'ordre_du_jour',
            'PV'      => 'pv',
            'RAPPORT' => 'rapport',
            'PRES'    => 'presentation',
            'NOTE'    => 'note',
            'PDEC'    => 'autre',
            'AUTRE'   => 'autre',
        ];
        $documentType = $codeToEnum[strtoupper($documentType)] ?? $documentType;
        $allowedEnumValues = ['ordre_du_jour', 'rapport', 'pv', 'presentation', 'note', 'autre'];
        if (!in_array($documentType, $allowedEnumValues, true)) {
            $documentType = 'autre';
        }

        $uploader = Auth::user();

        // Déterminer si l'uploadeur est habilité à intégrer directement un document sans validation
        $isPrivilegedUploader = $uploader && (
            $uploader->hasAnyRole(['drhmg', 'dsi', 'sg', 'admin', 'super-admin'])
            || $uploader->can('documents.auto_approve')
            || $uploader->can('documents.manage')
            || $uploader->can('documents.validate')
        );

        $doc = Document::create([
            'title'            => $request->input('title'),
            'description'      => $request->input('description'),
            'file_path'        => $path,
            'file_name'        => $storedName,
            'original_name'    => $file->getClientOriginalName(),
            'file_size'        => $file->getSize(),
            'mime_type'        => $file->getMimeType(),
            'extension'        => $extension,
            'document_type'    => $documentType,
            'document_type_id' => $request->input('document_type_id'),
            'meeting_id'       => $request->input('meeting_id'),
            'uploaded_by'      => Auth::id(),
            'is_shared'        => $request->boolean('is_shared', true),
            'validation_status' => 'draft',
        ]);

        // Créer la première version
        DocumentVersion::create([
            'document_id' => $doc->id,
            'version_number' => 1,
            'file_path' => $path,
            'file_name' => $storedName,
            'original_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'extension' => strtolower($file->getClientOriginalExtension()),
            'created_by' => Auth::id(),
        ]);

        // Si le type de document nécessite une validation, créer les validations,
        // sinon marquer directement comme approuvé. Les uploadeurs habilités
        // (DRHMG, DSI, SG, Admin, Super-Admin, ou permissions équivalentes)
        // voient leurs documents approuvés immédiatement.
        $requiresValidation = $doc->type && $doc->type->requires_validation;

        if ($requiresValidation && ! $isPrivilegedUploader) {
            $levels = ['protocole', 'sg', 'president'];
            foreach ($levels as $level) {
                DocumentValidation::create([
                    'document_id'      => $doc->id,
                    'validation_level' => $level,
                    'status'           => 'pending',
                ]);
            }
            $doc->update(['validation_status' => 'pending']);
        } else {
            // Pas de validation requise ou uploadeur habilité : le document est immédiatement utilisable
            $doc->update(['validation_status' => 'approved']);
        }

        // EF40 / EF41 : notifier la soumission d'un document
        event(new DocumentSubmitted($doc, Auth::user()));

        return redirect()
            ->route('documents.index')
            ->with('success', 'Le document a été ajouté avec succès.');
    }

    public function show(Document $document)
    {
        $document->load(['versions.creator', 'validations.validator', 'type', 'meeting', 'uploader']);
        
        return view('documents.show', compact('document'));
    }

    public function destroy(Document $document)
    {
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()
            ->route('documents.index')
            ->with('success', 'Le document a été supprimé.');
    }

    public function download(Document $document)
    {
        $this->authorize('download', $document);

        if (! Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'Le fichier associé à ce document est introuvable.');
        }

        // Audit : téléchargement de document
        AuditLogger::log(
            event: 'document_downloaded',
            target: $document,
            old: null,
            new: null,
            meta: [
                'file_name' => $document->file_name,
                'original_name' => $document->original_name,
                'mime_type' => $document->mime_type,
            ]
        );

        return Storage::disk('public')->download(
            $document->file_path,
            $document->original_name
        );
    }

    /**
     * Uploader une nouvelle version du document
     * EF28 - Versionnage des documents
     */
    public function uploadVersion(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $request->validate([
            'file' => ['required', 'file', 'max:10240'],
            'change_summary' => ['nullable', 'string', 'max:500'],
        ]);

        $file = $request->file('file');
        $latestVersion = $document->versions()->max('version_number') ?? 0;
        $newVersionNumber = $latestVersion + 1;

        $storedName = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('documents', $storedName, 'public');

        // Créer la nouvelle version
        DocumentVersion::create([
            'document_id' => $document->id,
            'version_number' => $newVersionNumber,
            'file_path' => $path,
            'file_name' => $storedName,
            'original_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'extension' => strtolower($file->getClientOriginalExtension()),
            'change_summary' => $request->input('change_summary'),
            'created_by' => Auth::id(),
        ]);

        // Mettre à jour le document avec la nouvelle version
        $document->update([
            'file_path' => $path,
            'file_name' => $storedName,
            'original_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'extension' => strtolower($file->getClientOriginalExtension()),
        ]);

        return redirect()
            ->route('documents.show', $document)
            ->with('success', "La version {$newVersionNumber} du document a été ajoutée avec succès.");
    }

    /**
     * Valider un document
     * EF29 - Validation des documents
     */
    public function validateDocument(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $request->validate([
            'validation_level' => ['required', 'in:protocole,sg,president'],
            'status' => ['required', 'in:approved,rejected'],
            'comments' => ['nullable', 'string', 'max:1000'],
        ]);

        $validation = $document->validations()
            ->where('validation_level', $request->input('validation_level'))
            ->first();

        if ($validation) {
            $validation->update([
                'status' => $request->input('status'),
                'comments' => $request->input('comments'),
                'validated_by' => Auth::id(),
                'validated_at' => now(),
            ]);

            // Mettre à jour le statut global du document
            $allValidations = $document->validations;
            $allApproved = $allValidations->every(fn($v) => $v->status === 'approved');
            $anyRejected = $allValidations->contains(fn($v) => $v->status === 'rejected');

            if ($anyRejected) {
                $document->update(['validation_status' => 'rejected']);
            } elseif ($allApproved) {
                $document->update(['validation_status' => 'approved']);
            } else {
                $document->update(['validation_status' => 'pending']);
            }

            // EF40 : événement de validation / rejet pour la chaîne Protocole → SG → Président
            event(new DocumentValidated($document, $validation, Auth::user()));
        }

        return redirect()
            ->route('documents.show', $document)
            ->with('success', 'La validation a été enregistrée avec succès.');
    }
}
