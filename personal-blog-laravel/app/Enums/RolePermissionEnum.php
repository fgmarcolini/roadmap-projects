<?php

namespace App\Enums;

enum RolePermissionEnum: string
{
    case READ = 'read_roles';
    case CREATE = 'create_roles';
    case UPDATE = 'update_roles';
    case DELETE = 'delete_roles';

    // extra helper to allow for greater customization of displayed values, without disclosing the name/value data directly
    public function label(): string
    {
        return match ($this) {
            static::READ => 'Read role',
            static::CREATE => 'Create role',
            static::UPDATE => 'Update role',
            static::DELETE => 'Delete role'
        };
    }
}
