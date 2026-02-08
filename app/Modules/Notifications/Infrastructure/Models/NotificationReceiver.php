<?php

namespace App\Modules\Notifications\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property int $notification_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $sent_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $notifiable
 * @property-read \App\Modules\Notifications\Infrastructure\Models\Notification $notification
 */
class NotificationReceiver extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'notifiable_type',
        'notifiable_id',
        'notification_id',
        'status',
        'read_at',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class);
    }
}
