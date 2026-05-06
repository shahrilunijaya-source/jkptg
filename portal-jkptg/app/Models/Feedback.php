<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Feedback extends Model
{
    use LogsActivity;

    protected $table = 'feedbacks';

    protected $fillable = [
        'reference_number', 'name', 'email', 'phone',
        'category', 'subject', 'message', 'status', 'ip_address',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['reference_number', 'category', 'status'])
            ->logOnlyDirty();
    }

    public static function generateReferenceNumber(): string
    {
        return 'JKPTG-' . date('Y') . '-' . str_pad((string) (static::max('id') + 1), 5, '0', STR_PAD_LEFT);
    }
}
