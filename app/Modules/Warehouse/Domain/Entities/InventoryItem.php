<?php

namespace App\Modules\Warehouse\Domain\Entities;

readonly class InventoryItem
{
    public function __construct(
        public int $id,
        public string $name,
        public string $sku,
        public ?string $description = null,
        public float $lowStockThreshold = 0.0,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            sku: $data['sku'],
            description: $data['description'] ?? null,
            lowStockThreshold: (float) ($data['low_stock_threshold'] ?? 0.0),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,
            'low_stock_threshold' => $this->lowStockThreshold,
        ];
    }
}
