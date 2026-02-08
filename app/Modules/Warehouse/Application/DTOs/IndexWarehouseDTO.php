<?php

namespace App\Modules\Warehouse\Application\DTOs;

use App\Domain\Contracts\HasPagination;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasLocation;
use App\Modules\Warehouse\Domain\Contracts\Filters\HasName;

readonly class IndexWarehouseDTO implements HasName, HasLocation, HasPagination
{
    public function __construct(
        public ?string $name = null,
        public ?string $location = null,
        public int     $perPage = 15,
        public int     $page = 1,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            location: $data['location'] ?? null,
            perPage: $data['per_page'] ?? 15,
            page: $data['page'] ?? 1,
        );
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getLocation(): ?string
    {
        return $this->location;
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
