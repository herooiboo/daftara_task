<?php

namespace App\Modules\Notifications\Infrastructure\Seeders;

use App\Modules\Notifications\Infrastructure\Repositories\NotificationChannelRepository;
use Illuminate\Database\Seeder;

class NotificationChannelsSeeder extends Seeder
{
    public function __construct(
        protected NotificationChannelRepository $notificationChannelRepository,
    ) {}

    public function run(): void
    {
        $this->notificationChannelRepository->firstOrCreate(
            ['name' => 'email'],
            [
                'description' => 'Email notification channel',
                'is_active' => true,
            ]
        );
    }
}
