<?php

namespace App\Modules\Auth\Infrastructure\Services;

use App\Modules\Auth\Domain\Contracts\Services\RoleServiceInterface;
use Spatie\Permission\Models\Role;

/**
 * Spatie Permission implementation of RoleServiceInterface.
 */
class SpatieRoleService implements RoleServiceInterface
{
    public function findByName(string $name, string $guard): ?object
    {
        try {
            return Role::findByName($name, $guard);
        }
        catch (\Spatie\Permission\Exceptions\RoleDoesNotExist $e) {
            return null;
        }
    }

    public function assignToUser(object $user, object $role): void
    {
        $user->assignRole($role);
    }

    public function userHasRole(object $user, string $roleName): bool
    {
        return $user->hasRole($roleName);
    }
}
