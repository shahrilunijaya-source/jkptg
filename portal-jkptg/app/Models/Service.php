<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use HasTranslations, LogsActivity, Searchable;

    public array $translatable = ['name', 'summary', 'eligibility', 'process_steps', 'required_documents'];

    protected $fillable = [
        'slug', 'icon', 'name', 'summary', 'eligibility', 'process_steps',
        'required_documents', 'category', 'sop_path', 'carta_alir_path',
        'processing_days', 'sort', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'sort' => 'integer',
        'processing_days' => 'integer',
        'process_steps' => 'array',
        'required_documents' => 'array',
    ];

    public function toSearchableArray(): array
    {
        return [
            'name_ms' => $this->getTranslation('name', 'ms'),
            'name_en' => $this->getTranslation('name', 'en', false),
            'summary_ms' => $this->getTranslation('summary', 'ms'),
            'summary_en' => $this->getTranslation('summary', 'en', false),
            'category' => $this->category,
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return $this->active;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['slug', 'name', 'active'])->logOnlyDirty();
    }
}
