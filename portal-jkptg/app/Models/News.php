<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class News extends Model
{
    use HasTranslations, LogsActivity, Searchable;

    protected $table = 'news';

    public array $translatable = ['title', 'excerpt', 'body'];

    protected $fillable = [
        'slug', 'title', 'excerpt', 'body', 'banner_path',
        'type', 'important', 'published_at', 'expires_at', 'author_id',
    ];

    protected $casts = [
        'important' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function toSearchableArray(): array
    {
        return [
            'title_ms' => $this->getTranslation('title', 'ms'),
            'title_en' => $this->getTranslation('title', 'en', false),
            'body_ms' => strip_tags($this->getTranslation('body', 'ms')),
            'type' => $this->type,
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return $this->published_at !== null && $this->published_at->isPast();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['slug', 'title', 'type', 'published_at'])->logOnlyDirty();
    }
}
