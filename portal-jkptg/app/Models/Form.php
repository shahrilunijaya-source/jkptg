<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Form extends Model
{
    use HasTranslations, LogsActivity, Searchable;

    public array $translatable = ['name', 'description'];

    protected $fillable = [
        'slug', 'name', 'description', 'file_path', 'category',
        'version', 'file_size_bytes', 'downloads_count', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'file_size_bytes' => 'integer',
        'downloads_count' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['slug', 'name', 'category', 'active'])->logOnlyDirty();
    }

    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size_bytes;
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1024 * 1024) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / (1024 * 1024), 1) . ' MB';
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->getRawOriginal('name'),
            'description' => $this->getRawOriginal('description'),
            'slug' => $this->slug,
            'category' => $this->category,
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return $this->active;
    }
}
