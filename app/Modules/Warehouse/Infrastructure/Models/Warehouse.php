<?php

namespace App\Modules\Warehouse\Infrastructure\Models;

use App\Modules\Warehouse\Infrastructure\Factories\WarehouseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $name
 * @property string|null $location
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\Warehouse\Infrastructure\Models\WarehouseInventoryItem[] $warehouseInventoryItems
 */
class Warehouse extends Model
{
    use HasFactory, LogsActivity;


    protected static function newFactory()
    {
        return WarehouseFactory::new();
    }

    protected $fillable = ['name', 'location'];

    public function warehouseInventoryItems(): HasMany
    {
        return $this->hasMany(WarehouseInventoryItem::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'location'])
            ->logOnlyDirty();
    }
}
