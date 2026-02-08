<?php

namespace App\Modules\Auth\Domain\Contracts\Repositories;

use App\Modules\Auth\Infrastructure\Models\User;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;

    public function findByEmail(string $email): ?User;

}
