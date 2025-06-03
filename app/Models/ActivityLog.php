<?php

namespace App\Models;

use App\Enums\ActivityLogType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action_type',
        'registration_no',
        'search_parameters',
        'endpoint',
        'ip_address',
        'metadata'
    ];

    protected $casts = [
        'search_parameters' => 'array',
        'metadata' => 'array',
        'action_type' => ActivityLogType::class
    ];

    // Helper scopes
    public function scopeSearches($query)
    {
        return $query->where('action_type', ActivityLogType::SEARCH);
    }

    public function scopeViews($query)
    {
        return $query->where('action_type', ActivityLogType::VIEW);
    }

    // User relationship
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
