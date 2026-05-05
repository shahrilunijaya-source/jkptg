<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Cawangan extends Model
{
    use HasTranslations, LogsActivity;

    protected $table = 'cawangan';

    public array $translatable = ['name', 'address', 'opening_hours'];

    protected $fillable = [
        'slug', 'name', 'state', 'address', 'phone', 'fax', 'email',
        'lat', 'lng', 'opening_hours', 'is_headquarters', 'sort',
    ];

    protected $casts = [
        'is_headquarters' => 'boolean',
        'sort' => 'integer',
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['slug', 'name', 'state', 'is_headquarters'])->logOnlyDirty();
    }
}
