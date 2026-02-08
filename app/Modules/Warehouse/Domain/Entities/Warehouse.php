<?php

namespace App\Modules\Warehouse\Domain\Entities;

readonly class Warehouse
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $location = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            location: $data['location'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => $this->location,
        ];
    }
}
