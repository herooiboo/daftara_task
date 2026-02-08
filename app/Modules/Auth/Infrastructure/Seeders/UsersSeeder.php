<?php

namespace App\Modules\Auth\Infrastructure\Seeders;

use App\Modules\Auth\Infrastructure\Repositories\UserRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    public function __construct(
        protected UserRepository $userRepository,
    ) {}

    public function run(): void
    {
        $superadmin = $this->userRepository->firstOrCreate(
            ['email' => 'superadmin@daftara.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'preferences' => ['notification_channel' => 'email'],
            ]
        );
        $superadmin->assignRole(Role::findByName('superadmin', 'api'));

        $manager = $this->userRepository->firstOrCreate(
            ['email' => 'manager@daftara.com'],
            [
                'name' => 'Warehouse Manager',
                'password' => Hash::make('password'),
                'preferences' => ['notification_channel' => 'email'],
            ]
        );
        $manager->assignRole(Role::findByName('manager', 'api'));

        $staff = $this->userRepository->firstOrCreate(
            ['email' => 'staff@daftara.com'],
            [
                'name' => 'Staff Member',
                'password' => Hash::make('password'),
                'preferences' => ['notification_channel' => 'email'],
            ]
        );
        $staff->assignRole(Role::findByName('staff', 'api'));
    }
}
