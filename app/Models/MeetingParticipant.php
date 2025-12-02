<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingParticipant extends Model
{
    use HasFactory;

    /**
     * Nom de la table en français
     */
    protected $table = 'participants_reunions';

    protected $fillable = [
        'meeting_id',
        'user_id',
        'role',
        'status',
        'reminder_sent',
        'validated_at',
        'checked_in_at',
    ];

    protected $casts = [
        'reminder_sent' => 'boolean',
        'validated_at'  => 'datetime',
        'checked_in_at' => 'datetime',
    ];

    /* ============================================================
     |  CONSTANTES MÉTIER
     |============================================================
    */
    public const STATUS_INVITED    = 'invited';
    public const STATUS_CONFIRMED  = 'confirmed';
    public const STATUS_PRESENT    = 'present';
    public const STATUS_ABSENT     = 'absent';
    public const STATUS_EXCUSED    = 'excused';

    public static function statuses(): array
    {
        return [
            self::STATUS_INVITED,
            self::STATUS_CONFIRMED,
            self::STATUS_PRESENT,
            self::STATUS_ABSENT,
            self::STATUS_EXCUSED,
        ];
    }

    /* ============================================================
     |  RELATIONS
     |============================================================
    */

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* ============================================================
     |  SCOPES
     |============================================================
    */

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopePresent($query)
    {
        return $query->where('status', self::STATUS_PRESENT);
    }

    public function scopeInvited($query)
    {
        return $query->where('status', self::STATUS_INVITED);
    }

    /* ============================================================
     |  HELPERS MÉTIER
     |============================================================
    */

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isPresent(): bool
    {
        return $this->status === self::STATUS_PRESENT;
    }

    public function isInvited(): bool
    {
        return $this->status === self::STATUS_INVITED;
    }

    public function markPresent(): void
    {
        $this->update([
            'status' => self::STATUS_PRESENT,
            'checked_in_at' => now(),
        ]);
    }
}
