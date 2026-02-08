<?php

namespace App\Modules\Notifications\Infrastructure\Models;

use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Warehouse\Infrastructure\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $warehouse_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Modules\Auth\Infrastructure\Models\User $user
 * @property-read \App\Modules\Warehouse\Infrastructure\Models\Warehouse $warehouse
 */
class WarehouseNotificationSubscription extends Model
{
    protected $fillable = ['user_id', 'warehouse_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
