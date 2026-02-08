<?php

namespace App\Modules\Auth\Domain\Contracts\Repositories;

interface UserRepositoryInterface
{
    public function findById(int $id): ?object;

    public function findByEmail(string $email): ?object;
}
