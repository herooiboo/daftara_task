<?php

namespace App\Modules\Auth\Domain\Contracts\Services;

/**
 * Interface for role management operations.
 * Abstracts away from Spatie Permission package.
 */
interface RoleServiceInterface
{
    /**
     * Find a role by name and guard.
     */
    public function findByName(string $name, string $guard): ?object;

    /**
     * Assign a role to a user.
     */
    public function assignToUser(object $user, object $role): void;

    /**
     * Check if a user has a specific role.
     */
    public function userHasRole(object $user, string $roleName): bool;
}
