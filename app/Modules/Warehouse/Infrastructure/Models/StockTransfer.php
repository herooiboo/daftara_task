<?php

namespace App\Modules\Warehouse\Infrastructure\Models;

use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Warehouse\Infrastructure\Factories\StockTransferFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $inventory_id
 * @property int $base_warehouse_id
 * @property int $target_warehouse_id
 * @property float $amount
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Modules\Warehouse\Infrastructure\Models\InventoryItem $inventoryItem
 * @property-read \App\Modules\Warehouse\Infrastructure\Models\Warehouse $baseWarehouse
 * @property-read \App\Modules\Warehouse\Infrastructure\Models\Warehouse $targetWarehouse
 * @property-read \App\Modules\Auth\Infrastructure\Models\User $creator
 */
class StockTransfer extends Model
{
    use HasFactory, LogsActivity;

    protected static function newFactory()
    {
        return StockTransferFactory::new();
    }

    protected $fillable = [
        'inventory_id',
        'base_warehouse_id',
        'target_warehouse_id',
        'amount',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_id');
    }

    public function baseWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'base_warehouse_id');
    }

    public function targetWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'target_warehouse_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['inventory_id', 'base_warehouse_id', 'target_warehouse_id', 'amount'])
            ->logOnlyDirty();
    }
}
