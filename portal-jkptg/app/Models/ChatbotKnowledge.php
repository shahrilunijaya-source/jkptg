<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class ChatbotKnowledge extends Model
{
    use HasTranslations, LogsActivity, Searchable;

    protected $table = 'chatbot_knowledge';

    public array $translatable = ['question', 'answer'];

    protected $fillable = ['slug', 'question', 'answer', 'keywords', 'source_url', 'category', 'active'];

    protected $casts = [
        'active' => 'boolean',
        'keywords' => 'array',
    ];

    public function toSearchableArray(): array
    {
        return [
            'question' => $this->getRawOriginal('question'),
            'answer' => $this->getRawOriginal('answer'),
            'slug' => $this->slug,
            'category' => $this->category,
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return $this->active;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['slug', 'question', 'category', 'active'])->logOnlyDirty();
    }
}
