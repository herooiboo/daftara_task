<?php

namespace App\Modules\Notifications\Domain\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;

interface NotificationChannelRepositoryInterface
{
    public function findByName(string $name): ?Model;
}
