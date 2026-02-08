<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DaftaraInstallCommand extends Command
{
    protected $signature = 'daftara:install {--seed : Seed sample data}';

    protected $description = 'Install the Daftara application';

    public function handle(): int
    {
        $this->info('Installing Daftara Inventory Management API...');
        $this->newLine();

        // Copy .env if not exists
        if (! file_exists(base_path('.env'))) {
            copy(base_path('.env.example'), base_path('.env'));
            $this->info('Created .env file from .env.example');
        }

        // Generate app key
        $this->call('key:generate');

        // Run migrations
        $this->info('Running migrations...');
        $this->call('migrate', ['--force' => true]);

        // Seed roles and permissions
        $this->info('Seeding roles and permissions...');
        $this->call('db:seed', ['--class' => 'App\\Modules\\Auth\\Infrastructure\\Seeders\\RolesAndPermissionsSeeder']);

        // Seed notification channels
        $this->info('Seeding notification channels...');
        $this->call('db:seed', ['--class' => 'App\\Modules\\Notifications\\Infrastructure\\Seeders\\NotificationChannelsSeeder']);

        // Seed users
        $this->info('Seeding users...');
        $this->call('db:seed', ['--class' => 'App\\Modules\\Auth\\Infrastructure\\Seeders\\UsersSeeder']);

        // Seed sample data if --seed flag is passed
        if ($this->option('seed')) {
            $this->info('Seeding sample data...');
            $this->call('db:seed', ['--class' => 'App\\Modules\\Warehouse\\Infrastructure\\Seeders\\WarehousesSeeder']);
            $this->call('db:seed', ['--class' => 'App\\Modules\\Warehouse\\Infrastructure\\Seeders\\InventoryItemsSeeder']);
            $this->call('db:seed', ['--class' => 'App\\Modules\\Warehouse\\Infrastructure\\Seeders\\WarehouseInventoryItemsSeeder']);
            $this->call('db:seed', ['--class' => 'App\\Modules\\Notifications\\Infrastructure\\Seeders\\WarehouseNotificationSubscriptionsSeeder']);
        }

        // Clear caches
        $this->call('config:clear');
        $this->call('cache:clear');
        $this->call('route:clear');

        $this->newLine();
        $this->info('Daftara installed successfully!');
        $this->newLine();

        $this->table(
            ['User', 'Email', 'Password', 'Role'],
            [
                ['Super Admin', 'superadmin@daftara.com', 'password', 'superadmin'],
                ['Warehouse Manager', 'manager@daftara.com', 'password', 'manager'],
                ['Staff Member', 'staff@daftara.com', 'password', 'staff'],
            ]
        );

        $this->newLine();
        $this->info('Use POST /api/login with the credentials above to get an API token.');

        return Command::SUCCESS;
    }
}
