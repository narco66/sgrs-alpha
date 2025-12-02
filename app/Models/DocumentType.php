<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentType extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nom de la table en français
     */
    protected $table = 'types_documents';

    protected $fillable = [
        'name',
        'code',
        'description',
        'requires_validation',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'requires_validation' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Documents de ce type
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'document_type_id');
    }

    /**
     * Scope pour les types actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour trier par ordre
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}

