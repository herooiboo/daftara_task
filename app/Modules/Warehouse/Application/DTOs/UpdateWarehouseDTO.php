<?php

namespace App\Modules\Warehouse\Application\DTOs;

use App\Domain\Contracts\HasToUpdateArray;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasId;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasLocation;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasName;

readonly class UpdateWarehouseDTO implements HasId, HasName, HasLocation, HasToUpdateArray
{
    public function __construct(
        public int     $id,
        public ?string $name,
        public ?string $location,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'] ?? null,
            location: $data['location'] ?? null,
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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function toUpdateArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'location' => $this->location,
        ], fn($v) => $v !== null);
    }
}
