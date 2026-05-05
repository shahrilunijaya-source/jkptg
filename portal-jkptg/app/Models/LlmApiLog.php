<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LlmApiLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'driver', 'model', 'prompt_tokens', 'completion_tokens',
        'cost_usd', 'cost_rm', 'latency_ms', 'status', 'error_message',
        'chat_session_id', 'created_at',
    ];

    protected $casts = [
        'prompt_tokens' => 'integer',
        'completion_tokens' => 'integer',
        'cost_usd' => 'decimal:6',
        'cost_rm' => 'decimal:4',
        'latency_ms' => 'integer',
        'created_at' => 'datetime',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class, 'chat_session_id');
    }
}
