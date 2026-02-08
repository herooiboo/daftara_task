<?php

namespace App\Modules\Notifications\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $type
 * @property string $subject
 * @property string $content
 * @property int $channel_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read \App\Modules\Notifications\Infrastructure\Models\NotificationChannel $channel
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\Notifications\Infrastructure\Models\NotificationReceiver[] $receivers
 */
class Notification extends Model
{
    public $timestamps = false;

    protected $fillable = ['type', 'subject', 'content', 'channel_id', 'created_at'];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->created_at = $model->created_at ?? now();
        });
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(NotificationChannel::class, 'channel_id');
    }

    public function receivers(): HasMany
    {
        return $this->hasMany(NotificationReceiver::class, 'notification_id');
    }
}
