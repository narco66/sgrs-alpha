<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentVersion extends Model
{
    use HasFactory;

    /**
     * Nom de la table en français
     */
    protected $table = 'versions_documents';

    protected $fillable = [
        'document_id',
        'version_number',
        'file_path',
        'file_name',
        'original_name',
        'file_size',
        'mime_type',
        'extension',
        'change_summary',
        'created_by',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'version_number' => 'integer',
    ];

    /**
     * Document parent
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Utilisateur qui a créé cette version
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

