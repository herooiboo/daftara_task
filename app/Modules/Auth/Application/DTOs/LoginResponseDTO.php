<?php

namespace App\Modules\Auth\Application\DTOs;

use App\Modules\Auth\Infrastructure\Models\User;

readonly class LoginResponseDTO
{
    public function __construct(
        public User   $user,
        public string $token,
    ) {}

    public function getUser(): User
    {
        return $this->user;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
