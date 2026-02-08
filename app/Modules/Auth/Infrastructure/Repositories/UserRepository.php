<?php

namespace App\Modules\Auth\Infrastructure\Repositories;

use App\Modules\Auth\Domain\Contracts\Repositories\UserRepositoryInterface;
use App\Modules\Auth\Infrastructure\Models\User;
use Dust\Base\Repository;

class UserRepository extends Repository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findById(int $id): ?User
    {
        return $this->model->query()->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->query()->where('email', $email)->first();
    }


    public function getAll(): \Illuminate\Support\Collection
    {
        return $this->model->query()->get();
    }

}
