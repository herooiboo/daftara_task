<?php

namespace App\Modules\Warehouse\Application\DTOs;

use App\Domain\Contracts\HasToUpdateArray;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasDescription;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasName;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPrice;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasSku;

readonly class UpdateInventoryItemDTO implements HasId, HasName, HasSku, HasPrice, HasDescription, HasToUpdateArray
{
    public function __construct(
        public int     $id,
        public ?string $name,
        public ?string $sku,
        public ?float  $price,
        public ?string $description,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'] ?? null,
            sku: $data['sku'] ?? null,
            price: $data['price'] ?? null,
            description: $data['description'] ?? null,
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function toUpdateArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'SKU' => $this->sku,
            'price' => $this->price,
            'description' => $this->description,
        ], fn($v) => $v !== null);
    }
}
