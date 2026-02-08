<?php

namespace App\Modules\Warehouse\Infrastructure\Models;

use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Warehouse\Infrastructure\Factories\InventoryItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $name
 * @property string $SKU
 * @property float|null $price
 * @property string|null $description
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Modules\Auth\Infrastructure\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\Warehouse\Infrastructure\Models\WarehouseInventoryItem[] $warehouseInventoryItems
 */
class InventoryItem extends Model
{
    use HasFactory, LogsActivity;

    protected static function newFactory(): InventoryItemFactory
    {
        return InventoryItemFactory::new();
    }

    protected $fillable = ['name', 'SKU', 'price', 'description', 'created_by'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function warehouseInventoryItems(): HasMany
    {
        return $this->hasMany(WarehouseInventoryItem::class, 'inventory_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'SKU', 'price', 'description'])
            ->logOnlyDirty();
    }
}
