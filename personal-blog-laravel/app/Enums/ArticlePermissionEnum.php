<?php

namespace App\Enums;

enum ArticlePermissionEnum: string
{
    case VIEW_HOME = 'view_home';
    case VIEW_ADMIN = 'view_admin';
    case CREATE = 'create_articles';
    case READ = 'read_articles';
    case UPDATE = 'update_articles';
    case DELETE = 'delete_articles';

    // extra helper to allow for greater customization of displayed values, without disclosing the name/value data directly
    public function label(): string
    {
        return match ($this) {
            static::VIEW_HOME => 'View home dashboard',
            static::VIEW_ADMIN => 'View admin dashboard',
            static::CREATE => 'Create article',
            static::READ => 'Read article',
            static::UPDATE => 'Update article',
            static::DELETE => 'Delete article'
        };
    }
}
