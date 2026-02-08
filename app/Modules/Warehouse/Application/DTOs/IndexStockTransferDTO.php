<?php

namespace App\Modules\Warehouse\Application\DTOs;

use App\Domain\Contracts\HasPagination;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasBaseWarehouseId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasInventoryId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasTargetWarehouseId;

readonly class IndexStockTransferDTO implements HasBaseWarehouseId, HasTargetWarehouseId, HasInventoryId, HasPagination
{
    public function __construct(
        public ?int $baseWarehouseId = null,
        public ?int $targetWarehouseId = null,
        public ?int $inventoryId = null,
        public int $perPage = 15,
        public int $page = 1,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            baseWarehouseId: $data['base_warehouse_id'] ?? null,
            targetWarehouseId: $data['target_warehouse_id'] ?? null,
            inventoryId: $data['inventory_id'] ?? null,
            perPage: $data['per_page'] ?? 15,
            page: $data['page'] ?? 1,
        );
    }

    public function getBaseWarehouseId(): ?int
    {
        return $this->baseWarehouseId;
    }

    public function getTargetWarehouseId(): ?int
    {
        return $this->targetWarehouseId;
    }

    public function getInventoryId(): ?int
    {
        return $this->inventoryId;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getPage(): int
    {
        return $this->page;
    }
}
