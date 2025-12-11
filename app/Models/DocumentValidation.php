<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class DocumentValidation extends Model
{
    use HasFactory;
    use LogsActivity;

    /**
     * Nom de la table en français
     */
    protected $table = 'validations_documents';

    protected $fillable = [
        'document_id',
        'validation_level',
        'status',
        'comments',
        'validated_by',
        'validated_at',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    /**
     * Document à valider
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Utilisateur qui a validé
     */
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Scope pour un niveau de validation
     */
    public function scopeLevel($query, string $level)
    {
        return $query->where('validation_level', $level);
    }

    /**
     * Scope pour un statut
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Obtenir le label du niveau de validation
     */
    public function getLevelLabelAttribute(): string
    {
        return match ($this->validation_level) {
            'protocole' => 'Protocole',
            'sg' => 'Secrétariat Général',
            'president' => 'Président',
            default => $this->validation_level,
        };
    }

    /**
     * Obtenir le label du statut
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'En attente',
            'approved' => 'Approuvé',
            'rejected' => 'Rejeté',
            default => $this->status,
        };
    }
}

