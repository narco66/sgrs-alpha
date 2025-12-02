<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delegation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'delegations';

    protected $fillable = [
        'title',
        'code',
        'country',
        'description',
        'contact_email',
        'contact_phone',
        'address',
        'meeting_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'delegation_participants')
            ->withPivot(['role'])
            ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%")
              ->orWhere('country', 'like', "%{$search}%");
        });
    }
}
