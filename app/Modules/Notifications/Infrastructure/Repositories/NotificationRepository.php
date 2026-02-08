<?php

namespace App\Modules\Notifications\Infrastructure\Repositories;

use App\Modules\Notifications\Domain\Contracts\Repositories\NotificationRepositoryInterface;
use App\Modules\Notifications\Infrastructure\Models\Notification;
use Dust\Base\Repository;

class NotificationRepository extends Repository implements NotificationRepositoryInterface
{
    public function __construct(Notification $model)
    {
        parent::__construct($model);
    }

}
