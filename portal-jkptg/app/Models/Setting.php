<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'description'];

    protected $casts = ['value' => 'array'];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("setting:{$key}", function () use ($key, $default) {
            $row = static::where('key', $key)->first();
            return $row ? $row->value : $default;
        });
    }

    public static function put(string $key, mixed $value, ?string $description = null): self
    {
        Cache::forget("setting:{$key}");
        return static::updateOrCreate(['key' => $key], ['value' => $value, 'description' => $description]);
    }

    protected static function booted(): void
    {
        static::saved(fn ($model) => Cache::forget("setting:{$model->key}"));
        static::deleted(fn ($model) => Cache::forget("setting:{$model->key}"));
    }
}
