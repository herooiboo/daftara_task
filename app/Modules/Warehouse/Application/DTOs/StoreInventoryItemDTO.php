<?php

namespace App\Modules\Warehouse\Application\DTOs;

use App\Domain\Contracts\HasToCreateArray;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasDescription;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasName;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasPrice;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasSku;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasUserId;

readonly class StoreInventoryItemDTO implements HasName, HasSku, HasPrice, HasDescription, HasUserId, HasToCreateArray
{
    public function __construct(
        public string  $name,
        public string  $sku,
        public ?float  $price,
        public ?string $description,
        public int     $createdBy,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            sku: $data['sku'],
            price: $data['price'] ?? null,
            description: $data['description'] ?? null,
            createdBy: $data['created_by'],
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSku(): string
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

    public function getUserId(): int
    {
        return $this->createdBy;
    }

    public function toCreateArray(): array
    {
        return [
            'name' => $this->name,
            'SKU' => $this->sku,
            'price' => $this->price,
            'description' => $this->description,
            'created_by' => $this->createdBy,
        ];
    }
}
