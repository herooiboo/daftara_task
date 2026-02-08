<?php

namespace App\Modules\Warehouse\Tests\Unit;

use App\Modules\Auth\Infrastructure\Models\User;
use App\Modules\Warehouse\Application\DTOs\CreateStockTransferDTO;
use App\Modules\Warehouse\Application\Services\CreateStockTransferService;
use App\Modules\Warehouse\Domain\Exceptions\InsufficientStockException;
use App\Modules\Warehouse\Infrastructure\Models\InventoryItem;
use App\Modules\Warehouse\Infrastructure\Models\Warehouse;
use App\Modules\Warehouse\Infrastructure\Models\WarehouseInventoryItem;
use Tests\TestCase;

/**
 * @group warehouse
 */
class StockTransferInsufficientStockTest extends TestCase
{
    public function test_service_throws_exception_when_stock_insufficient(): void
    {
        $baseWarehouse = Warehouse::factory()->create();
        $targetWarehouse = Warehouse::factory()->create();
        $item = InventoryItem::factory()->create();

        WarehouseInventoryItem::factory()->create([
            'warehouse_id' => $baseWarehouse->id,
            'inventory_id' => $item->id,
            'stock' => 10,
        ]);

        $user = User::factory()->create();
        
        $dto = new CreateStockTransferDTO(
            inventoryId: $item->id,
            baseWarehouseId: $baseWarehouse->id,
            targetWarehouseId: $targetWarehouse->id,
            amount: 100,
            createdBy: $user->id
        );

        $service = app(CreateStockTransferService::class);

        $this->expectException(InsufficientStockException::class);

        $service->handle($dto);
    }
}
