<?php

namespace App\Enums;

enum UserPermissionEnum: string
{
    case READ = 'read_users';
    case CREATE = 'create_users';
    case UPDATE = 'update_users';
    case DELETE = 'delete_users';

    // extra helper to allow for greater customization of displayed values, without disclosing the name/value data directly
    public function label(): string
    {
        return match ($this) {
            static::READ => 'Read user',
            static::CREATE => 'Create user',
            static::UPDATE => 'Update user',
            static::DELETE => 'Delete user'
        };
    }
}
