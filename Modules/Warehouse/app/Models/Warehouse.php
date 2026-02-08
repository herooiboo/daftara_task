<?php

/**
 * Created by Reliese Model.
 */

namespace Modules\Warehouse\app\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\app\Models\User;

/**
 * Class Warehouse
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $location
 * @property int|null $manager_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User|null $user
 * @property Collection|StockTransfer[] $stock_transfers
 * @property Collection|InventoryItem[] $inventory_items
 *
 * @package App\Models
 */
class Warehouse extends Model
{
	protected $table = 'warehouses';

	protected $casts = [
		'manager_id' => 'int'
	];

	protected $fillable = [
		'name',
		'location',
		'manager_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'manager_id');
	}

	public function stock_transfers()
	{
		return $this->hasMany(StockTransfer::class, 'target_warehouse_id');
	}

	public function inventory_items()
	{
		return $this->belongsToMany(InventoryItem::class, 'warehouse_inventory_items', 'warehouse_id', 'inventory_id')
					->withPivot('id', 'stock', 'low_stock_threshold', 'last_updated_by')
					->withTimestamps();
	}
}
