<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ChatbotQuickReply extends Model
{
    use HasTranslations;

    public array $translatable = ['label'];

    protected $fillable = ['label', 'payload_query', 'sort', 'active'];

    protected $casts = [
        'active' => 'boolean',
        'sort' => 'integer',
    ];
}
