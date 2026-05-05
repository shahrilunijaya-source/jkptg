<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasTranslations, LogsActivity;

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
}
