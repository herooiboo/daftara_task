<?php

namespace App\Modules\Notifications\Domain\Contracts\Repositories;

interface NotificationChannelRepositoryInterface
{
    public function findByName(string $name): ?object;
}
