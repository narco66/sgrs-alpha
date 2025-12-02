<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeetingType extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Nom de la table en français
     */
    protected $table = 'types_reunions';

    protected $fillable = [
        'name',
        'code',
        'color',
        'sort_order',
        'requires_president_approval',
        'requires_sg_approval',
        'description',
        'is_active',
    ];

    protected $casts = [
        'requires_president_approval' => 'boolean',
        'requires_sg_approval'        => 'boolean',
        'is_active'                   => 'boolean',
        'sort_order'                  => 'integer',
    ];

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    public function getBadgeClassAttribute(): string
    {
        // Si color contient une classe Bootstrap
        if ($this->color && in_array($this->color, ['primary','secondary','success','danger','warning','info','dark'])) {
            return 'badge bg-' . $this->color;
        }

        // Couleur hex ou autre -> badge clair
        if ($this->color && str_starts_with($this->color, '#')) {
            return 'badge text-dark';
        }

        // Défaut
        return 'badge bg-primary';
    }
}
