<?php

namespace App\Modules\Notifications\Infrastructure\Repositories;

use App\Modules\Notifications\Domain\Contracts\Repositories\NotificationChannelRepositoryInterface;
use App\Modules\Notifications\Infrastructure\Models\NotificationChannel;
use Dust\Base\Repository;
use Illuminate\Database\Eloquent\Model;

class NotificationChannelRepository extends Repository implements NotificationChannelRepositoryInterface
{
    public function __construct(NotificationChannel $model)
    {
        parent::__construct($model);
    }

    public function findByName(string $name): ?Model
    {
        return $this->model->query()->where('name', $name)->first();
    }

    public function firstOrCreate(array $attributes, array $values = []): Model
    {
        return $this->model->query()->firstOrCreate($attributes, $values);
    }
}
