<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasTranslations, LogsActivity, Searchable;

    public array $translatable = ['title', 'body', 'meta_title', 'meta_description'];

    protected $fillable = [
        'slug', 'title', 'body', 'meta_title', 'meta_description',
        'parent_id', 'sort', 'published',
    ];

    protected $casts = [
        'published' => 'boolean',
        'sort' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function toSearchableArray(): array
    {
        return [
            'title_ms' => $this->getTranslation('title', 'ms'),
            'title_en' => $this->getTranslation('title', 'en', false),
            'body_ms' => strip_tags($this->getTranslation('body', 'ms')),
            'body_en' => strip_tags($this->getTranslation('body', 'en', false)),
            'slug' => $this->slug,
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return $this->published;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['slug', 'title', 'published'])->logOnlyDirty();
    }
}
