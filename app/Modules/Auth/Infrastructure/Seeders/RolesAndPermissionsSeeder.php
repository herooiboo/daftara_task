<?php

namespace App\Modules\Auth\Infrastructure\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view-warehouses',
            'create-warehouses',
            'update-warehouses',
            'delete-warehouses',
            'view-inventory-items',
            'create-inventory-items',
            'update-inventory-items',
            'delete-inventory-items',
            'view-inventory',
            'view-stock-transfers',
            'create-stock-transfers',
            'manage-warehouse-subscriptions',
            'view-activity-logs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'api']);
        }

        $superadmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'api']);
        $superadmin->syncPermissions($permissions);

        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'api']);
        $manager->syncPermissions([
            'view-warehouses',
            'create-warehouses',
            'update-warehouses',
            'view-inventory-items',
            'create-inventory-items',
            'update-inventory-items',
            'view-inventory',
            'view-stock-transfers',
            'create-stock-transfers',
            'view-activity-logs',
        ]);

        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'api']);
        $staff->syncPermissions([
            'view-warehouses',
            'view-inventory-items',
            'view-inventory',
            'view-stock-transfers',
            'create-stock-transfers',
        ]);
    }
}
