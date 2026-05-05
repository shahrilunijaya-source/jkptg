<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'page_path', 'ip_address', 'user_agent_hash', 'country',
        'locale', 'referer', 'user_id', 'created_at',
    ];

    protected $casts = ['created_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
