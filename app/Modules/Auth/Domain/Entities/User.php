<?php

namespace App\Modules\Auth\Domain\Entities;

readonly class User
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?array $preferences = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            email: $data['email'],
            preferences: $data['preferences'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'preferences' => $this->preferences,
        ];
    }
}
