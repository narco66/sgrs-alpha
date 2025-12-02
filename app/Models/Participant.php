<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Nom de la table en français
     */
    protected $table = 'participants';

    protected $fillable = [
        'last_name',
        'first_name',
        'email',
        'phone',
        'position',
        'institution',
        'country',
        'is_internal',
        'is_active',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
        'is_active'   => 'boolean',
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function meetings()
    {
        // Pivot orienté vers les participants : participants_reunions.participant_id
        return $this->belongsToMany(Meeting::class, 'participants_reunions', 'participant_id', 'meeting_id')
            ->withTimestamps();
    }

    public function scopeActifs($query)
    {
        return $query->where('is_active', true);
    }
}
