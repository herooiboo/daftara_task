<?php

namespace App\Modules\Warehouse\Application\DTOs;

use App\Domain\Contracts\HasPagination;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasName;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPriceMax;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPriceMin;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasSku;

readonly class GetWarehouseInventoryDTO implements HasName, HasSku, HasPriceMin, HasPriceMax, HasPagination
{
    public function __construct(
        public string $warehouseId,
        public ?string $name,
        public ?string $sku,
        public ?float  $priceMin,
        public ?float  $priceMax,
        public int     $perPage = 15,
        public int     $page = 1,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            warehouseId: $data['warehouse_id'],
            name: $data['name'] ?? null,
            sku: $data['sku'] ?? null,
            priceMin: $data['price_min'] ?? null,
            priceMax: $data['price_max'] ?? null,
            perPage: $data['per_page'] ?? 15,
            page: $data['page'] ?? 1,
        );
    }

    public function getWarehouseId(): string
    {
        return $this->warehouseId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function getPriceMin(): ?float
    {
        return $this->priceMin;
    }

    public function getPriceMax(): ?float
    {
        return $this->priceMax;
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
