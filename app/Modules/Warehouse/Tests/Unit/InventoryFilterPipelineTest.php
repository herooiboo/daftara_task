<?php

namespace App\Modules\Warehouse\Tests\Unit;

use App\Modules\Warehouse\Application\DTOs\GetAllInventoryDTO;
use App\Modules\Warehouse\Application\Pipelines\FilterByName;
use App\Modules\Warehouse\Application\Pipelines\FilterByPriceMax;
use App\Modules\Warehouse\Application\Pipelines\FilterByPriceMin;
use App\Modules\Warehouse\Application\Pipelines\FilterBySku;
use App\Modules\Warehouse\Application\Pipelines\FilterByWarehouse;
use App\Modules\Warehouse\Infrastructure\Models\InventoryItem;
use App\Modules\Warehouse\Infrastructure\Models\Warehouse;
use App\Modules\Warehouse\Infrastructure\Models\WarehouseInventoryItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;
use Tests\TestCase;

class InventoryFilterPipelineTest extends TestCase
{
    public function test_filter_by_warehouse_pipe(): void
    {
        $warehouse = Warehouse::factory()->create();
        $item = InventoryItem::factory()->create();

        WarehouseInventoryItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'inventory_id' => $item->id,
        ]);

        $query = WarehouseInventoryItem::query();
        $dto = GetAllInventoryDTO::fromArray(['warehouse_id' => $warehouse->id]);

        $result = app(Pipeline::class)
            ->send($query)
            ->through([new FilterByWarehouse($dto)])
            ->thenReturn();

        $this->assertInstanceOf(Builder::class, $result);
    }

    public function test_filter_by_name_pipe(): void
    {
        $item = InventoryItem::factory()->create(['name' => 'Test Product']);
        $warehouse = Warehouse::factory()->create();

        WarehouseInventoryItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'inventory_id' => $item->id,
        ]);

        $query = WarehouseInventoryItem::query()->with('inventoryItem');
        $dto = GetAllInventoryDTO::fromArray(['name' => 'Test']);

        $result = app(Pipeline::class)
            ->send($query)
            ->through([new FilterByName($dto)])
            ->thenReturn();

        $this->assertInstanceOf(Builder::class, $result);
    }

    public function test_filter_by_sku_pipe(): void
    {
        $item = InventoryItem::factory()->create(['SKU' => 'UNIQUE-SKU']);
        $warehouse = Warehouse::factory()->create();

        WarehouseInventoryItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'inventory_id' => $item->id,
        ]);

        $query = WarehouseInventoryItem::query()->with('inventoryItem');
        $dto = GetAllInventoryDTO::fromArray(['sku' => 'UNIQUE']);

        $result = app(Pipeline::class)
            ->send($query)
            ->through([new FilterBySku($dto)])
            ->thenReturn();

        $this->assertInstanceOf(Builder::class, $result);
    }

    public function test_filter_by_price_min_pipe(): void
    {
        $item = InventoryItem::factory()->create(['price' => 100.00]);
        $warehouse = Warehouse::factory()->create();

        WarehouseInventoryItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'inventory_id' => $item->id,
        ]);

        $query = WarehouseInventoryItem::query()->with('inventoryItem');
        $dto = GetAllInventoryDTO::fromArray(['price_min' => 50]);

        $result = app(Pipeline::class)
            ->send($query)
            ->through([new FilterByPriceMin($dto)])
            ->thenReturn();

        $this->assertInstanceOf(Builder::class, $result);
    }

    public function test_filter_by_price_max_pipe(): void
    {
        $item = InventoryItem::factory()->create(['price' => 100.00]);
        $warehouse = Warehouse::factory()->create();

        WarehouseInventoryItem::factory()->create([
            'warehouse_id' => $warehouse->id,
            'inventory_id' => $item->id,
        ]);

        $query = WarehouseInventoryItem::query()->with('inventoryItem');
        $dto = GetAllInventoryDTO::fromArray(['price_max' => 200]);

        $result = app(Pipeline::class)
            ->send($query)
            ->through([new FilterByPriceMax($dto)])
            ->thenReturn();

        $this->assertInstanceOf(Builder::class, $result);
    }
}
