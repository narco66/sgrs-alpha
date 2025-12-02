<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Committee extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Nom de la table en français
     */
    protected $table = 'comites';

    protected $fillable = [
        'name',
        'code',
        'meeting_type_id',
        'is_permanent',
        'is_active',
        'description',
        'sort_order',
    ];

    protected $casts = [
        'is_permanent' => 'boolean',
        'is_active'    => 'boolean',
        'sort_order'   => 'integer',
    ];

    public function meetingType()
    {
        return $this->belongsTo(MeetingType::class);
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }
}
