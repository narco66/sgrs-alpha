<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class Document extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    /**
     * Nom de la table en français
     */
    protected $table = 'documents';

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'file_name',
        'original_name',
        'file_size',
        'mime_type',
        'extension',
        'document_type',
        'document_type_id',
        'meeting_id',
        'uploaded_by',
        'is_shared',
        'validation_status',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'is_shared' => 'boolean',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Type de document (nouveau système)
     */
    public function type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    /**
     * Versions du document
     */
    public function versions()
    {
        return $this->hasMany(DocumentVersion::class)->orderByDesc('version_number');
    }

    /**
     * Dernière version
     */
    public function latestVersion()
    {
        return $this->hasOne(DocumentVersion::class)->latestOfMany('version_number');
    }

    /**
     * Validations du document
     */
    public function validations()
    {
        return $this->hasMany(DocumentValidation::class)->orderBy('validation_level');
    }

    /**
     * Validation en attente
     */
    public function pendingValidations()
    {
        return $this->hasMany(DocumentValidation::class)->where('status', 'pending');
    }

    public function getIconClassAttribute(): string
    {
        $ext = strtolower($this->extension ?? '');

        return match ($ext) {
            'pdf'        => 'bi-file-earmark-pdf-fill text-danger',
            'doc', 'docx'=> 'bi-file-earmark-word-fill text-primary',
            'xls', 'xlsx'=> 'bi-file-earmark-excel-fill text-success',
            'ppt', 'pptx'=> 'bi-file-earmark-ppt-fill text-warning',
            default      => 'bi-file-earmark-text-fill text-secondary',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->document_type) {
            'ordre_du_jour' => 'Ordre du jour',
            'rapport'       => 'Rapport',
            'pv'            => 'Procès-verbal',
            'presentation'  => 'Présentation',
            'note'          => 'Note',
            default         => 'Autre',
        };
    }

    public function scopeOfType($query, ?string $type)
    {
        if ($type && $type !== 'all') {
            $query->where('document_type', $type);
        }
        return $query;
    }

    public function scopeWithExtension($query, ?string $extension)
    {
        if ($extension && $extension !== 'all') {
            $ext = strtolower($extension);
            
            // Gestion des groupes d'extensions
            if ($ext === 'word') {
                $query->whereIn('extension', ['doc', 'docx']);
            } elseif ($ext === 'excel') {
                $query->whereIn('extension', ['xls', 'xlsx']);
            } elseif ($ext === 'powerpoint') {
                $query->whereIn('extension', ['ppt', 'pptx']);
            } else {
                $query->where('extension', $ext);
            }
        }
        return $query;
    }
}
