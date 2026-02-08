<?php

namespace App\Modules\Notifications\Infrastructure\Repositories;

use App\Modules\Notifications\Domain\Contracts\Repositories\NotificationReceiverRepositoryInterface;
use App\Modules\Notifications\Infrastructure\Models\NotificationReceiver;
use Dust\Base\Repository;

class NotificationReceiverRepository extends Repository implements NotificationReceiverRepositoryInterface
{
    public function __construct(NotificationReceiver $model)
    {
        parent::__construct($model);
    }

}
