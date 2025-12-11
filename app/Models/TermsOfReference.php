<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\LogsActivity;

/**
 * Modèle pour le Cahier des charges entre la CEEAC et le pays hôte
 * 
 * Conforme au modèle institutionnel de la CEEAC
 */
class TermsOfReference extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    /**
     * Nom de la table en français
     */
    protected $table = 'cahiers_charges';

    protected $fillable = [
        'meeting_id',
        'host_country',
        'signature_date',
        'effective_from',
        'effective_until',
        'responsibilities_ceeac',
        'responsibilities_host',
        'financial_sharing',
        'logistical_sharing',
        'obligations_ceeac',
        'obligations_host',
        'additional_terms',
        'status',
        'validated_by',
        'validated_at',
        'signed_by_ceeac',
        'signed_by_host_name',
        'signed_by_host_position',
        'signed_at',
        'version',
        'previous_version_id',
        'pdf_path',
        'signed_document_path',
        'signed_document_name',
        'signed_document_original_name',
        'signed_document_size',
        'signed_document_mime_type',
        'signed_document_extension',
        'signed_document_uploaded_at',
        'signed_document_uploaded_by',
        'notes',
    ];

    protected $casts = [
        'signature_date' => 'date',
        'effective_from' => 'date',
        'effective_until' => 'date',
        'validated_at' => 'datetime',
        'signed_at' => 'datetime',
        'signed_document_uploaded_at' => 'datetime',
        'version' => 'integer',
        'signed_document_size' => 'integer',
    ];

    /* ============================================================
     |  CONSTANTES MÉTIER
     |============================================================
    */
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING_VALIDATION = 'pending_validation';
    public const STATUS_VALIDATED = 'validated';
    public const STATUS_SIGNED = 'signed';
    public const STATUS_CANCELLED = 'cancelled';

    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_PENDING_VALIDATION,
            self::STATUS_VALIDATED,
            self::STATUS_SIGNED,
            self::STATUS_CANCELLED,
        ];
    }

    /* ============================================================
     |  RELATIONS
     |============================================================
    */

    /**
     * Réunion associée
     */
    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

    /**
     * Utilisateur ayant validé le cahier des charges
     */
    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Utilisateur CEEAC ayant signé
     */
    public function signerCeeac(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by_ceeac');
    }

    /**
     * Utilisateur ayant uploadé le document signé
     */
    public function signedDocumentUploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_document_uploaded_by');
    }

    /**
     * Version précédente
     */
    public function previousVersion(): BelongsTo
    {
        return $this->belongsTo(TermsOfReference::class, 'previous_version_id');
    }

    /**
     * Versions suivantes
     */
    public function nextVersions(): HasMany
    {
        return $this->hasMany(TermsOfReference::class, 'previous_version_id');
    }

    /* ============================================================
     |  SCOPES
     |============================================================
    */

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeValidated($query)
    {
        return $query->where('status', self::STATUS_VALIDATED);
    }

    public function scopeSigned($query)
    {
        return $query->where('status', self::STATUS_SIGNED);
    }

    public function scopeCurrentVersion($query)
    {
        return $query->whereNull('previous_version_id')
            ->orWhereNotExists(function ($subquery) {
                $subquery->selectRaw(1)
                    ->from('cahiers_charges as next')
                    ->whereColumn('next.previous_version_id', 'cahiers_charges.id');
            });
    }

    /* ============================================================
     |  MÉTHODES MÉTIER
     |============================================================
    */

    /**
     * Vérifie si le cahier des charges est signé
     */
    public function isSigned(): bool
    {
        return $this->status === self::STATUS_SIGNED && $this->signed_at !== null;
    }

    /**
     * Vérifie si le cahier des charges est validé
     */
    public function isValidated(): bool
    {
        return $this->status === self::STATUS_VALIDATED && $this->validated_at !== null;
    }

    /**
     * Crée une nouvelle version du cahier des charges
     */
    public function createNewVersion(array $attributes = []): self
    {
        $newVersion = $this->replicate();
        $newVersion->version = $this->version + 1;
        $newVersion->previous_version_id = $this->id;
        $newVersion->status = self::STATUS_DRAFT;
        $newVersion->validated_at = null;
        $newVersion->validated_by = null;
        $newVersion->signed_at = null;
        $newVersion->signed_by_ceeac = null;
        $newVersion->pdf_path = null;
        
        foreach ($attributes as $key => $value) {
            $newVersion->$key = $value;
        }
        
        $newVersion->save();
        
        return $newVersion;
    }
}

