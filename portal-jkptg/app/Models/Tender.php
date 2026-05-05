<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Tender extends Model
{
    use HasTranslations, LogsActivity;

    public array $translatable = ['title', 'description'];

    protected $fillable = [
        'slug', 'reference_no', 'title', 'description', 'doc_path',
        'opens_at', 'closes_at', 'status', 'estimated_value_rm',
    ];

    protected $casts = [
        'opens_at' => 'date',
        'closes_at' => 'datetime',
        'estimated_value_rm' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['reference_no', 'title', 'status', 'closes_at'])->logOnlyDirty();
    }

    public function isOpen(): bool
    {
        return $this->status === 'open' && $this->closes_at?->isFuture();
    }
}
