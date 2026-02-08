<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\Warehouse\app\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Auth\app\Models\User;

/**
 * Class WarehouseInventoryItem
 * 
 * @property int $id
 * @property int|null $inventory_id
 * @property int|null $warehouse_id
 * @property float|null $stock
 * @property float|null $low_stock_threshold
 * @property int|null $last_updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property InventoryItem|null $inventory_item
 * @property Warehouse|null $warehouse
 * @property User|null $user
 *
 * @package App\Models
 */
class WarehouseInventoryItem extends Model
{
	protected $table = 'warehouse_inventory_items';

	protected $casts = [
		'inventory_id' => 'int',
		'warehouse_id' => 'int',
		'stock' => 'float',
		'low_stock_threshold' => 'float',
		'last_updated_by' => 'int'
	];

	protected $fillable = [
		'inventory_id',
		'warehouse_id',
		'stock',
		'low_stock_threshold',
		'last_updated_by'
	];

	public function inventory_item(): BelongsTo
    {
		return $this->belongsTo(InventoryItem::class, 'inventory_id');
	}

	public function warehouse(): BelongsTo
    {
		return $this->belongsTo(Warehouse::class);
	}

	public function user(): BelongsTo
    {
		return $this->belongsTo(User::class, 'last_updated_by');
	}
}
