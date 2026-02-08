<?php

namespace App\Modules\Warehouse\Infrastructure\Models;

use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Warehouse\Infrastructure\Factories\WarehouseInventoryItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $inventory_id
 * @property int $warehouse_id
 * @property float $stock
 * @property float|null $low_stock_threshold
 * @property int $last_updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Modules\Warehouse\Infrastructure\Models\Warehouse $warehouse
 * @property-read \App\Modules\Warehouse\Infrastructure\Models\InventoryItem $inventoryItem
 * @property-read \App\Modules\Auth\Infrastructure\Models\User $lastUpdatedBy
 */
class WarehouseInventoryItem extends Model
{
    use HasFactory, LogsActivity;

    protected static function newFactory()
    {
        return WarehouseInventoryItemFactory::new();
    }

    protected $fillable = [
        'inventory_id',
        'warehouse_id',
        'stock',
        'low_stock_threshold',
        'last_updated_by',
    ];

    protected function casts(): array
    {
        return [
            'stock' => 'decimal:2',
            'low_stock_threshold' => 'decimal:2',
        ];
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_id');
    }

    public function lastUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['stock', 'low_stock_threshold'])
            ->logOnlyDirty();
    }
}
