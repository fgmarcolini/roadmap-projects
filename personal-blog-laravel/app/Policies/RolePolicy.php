<?php

namespace App\Policies;

use App\Enums\RolePermissionEnum;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    /*
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(RolePermissionEnum::READ);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(RolePermissionEnum::CREATE);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $model): bool
    {
        return $user->hasPermissionTo(RolePermissionEnum::UPDATE);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $model): bool
    {
        return $user->hasPermissionTo(RolePermissionEnum::DELETE);
    }
}
