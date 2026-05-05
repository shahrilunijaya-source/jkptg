<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ChatbotSetting extends Model
{
    use LogsActivity;

    protected $fillable = [
        'driver', 'model', 'cost_month_to_date_rm', 'cost_cap_rm',
        'alert_threshold_pct', 'kill_switch_active', 'cap_reset_at',
    ];

    protected $casts = [
        'cost_month_to_date_rm' => 'decimal:2',
        'cost_cap_rm' => 'decimal:2',
        'alert_threshold_pct' => 'integer',
        'kill_switch_active' => 'boolean',
        'cap_reset_at' => 'datetime',
    ];

    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], []);
    }

    public function isOverBudget(): bool
    {
        return $this->cost_month_to_date_rm >= $this->cost_cap_rm;
    }

    public function isOverAlertThreshold(): bool
    {
        $threshold = $this->cost_cap_rm * ($this->alert_threshold_pct / 100);
        return $this->cost_month_to_date_rm >= $threshold;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['driver', 'model', 'cost_cap_rm', 'kill_switch_active'])
            ->logOnlyDirty();
    }
}
