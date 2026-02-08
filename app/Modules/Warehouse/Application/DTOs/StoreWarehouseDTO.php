<?php

namespace App\Modules\Warehouse\Application\DTOs;

use App\Domain\Contracts\HasToCreateArray;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasLocation;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasName;

readonly class StoreWarehouseDTO implements HasName, HasLocation, HasToCreateArray
{
    public function __construct(
        public string  $name,
        public ?string $location,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            location: $data['location'] ?? null,
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function toCreateArray(): array
    {
        return [
            'name' => $this->name,
            'location' => $this->location,
        ];
    }
}
