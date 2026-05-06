<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasTranslations, LogsActivity, Searchable;

    public array $translatable = ['question', 'answer'];

    protected $fillable = ['category', 'question', 'answer', 'sort', 'active'];

    protected $casts = [
        'active' => 'boolean',
        'sort' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['category', 'question', 'active'])->logOnlyDirty();
    }

    public function toSearchableArray(): array
    {
        return [
            'question' => $this->getRawOriginal('question'),
            'answer' => $this->getRawOriginal('answer'),
            'category' => $this->category,
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return $this->active;
    }
}
