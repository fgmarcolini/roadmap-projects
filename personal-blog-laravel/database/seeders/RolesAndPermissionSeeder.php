<?php

namespace Database\Seeders;

use App\Enums\ArticlePermissionEnum;
use App\Enums\UserPermissionEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User permissions
        Permission::findOrCreate(UserPermissionEnum::CREATE);
        Permission::findOrCreate(UserPermissionEnum::READ);
        Permission::findOrCreate(UserPermissionEnum::UPDATE);
        Permission::findOrCreate(UserPermissionEnum::DELETE);

        // Articles permissions
        $view_home = Permission::findOrCreate(ArticlePermissionEnum::VIEW_HOME);
        $view_admin = Permission::findOrCreate(ArticlePermissionEnum::VIEW_ADMIN);
        $create_articles = Permission::findOrCreate(ArticlePermissionEnum::CREATE);
        $read_articles = Permission::findOrCreate(ArticlePermissionEnum::READ);
        $update_articles = Permission::findOrCreate(ArticlePermissionEnum::UPDATE);
        $delete_articles = Permission::findOrCreate(ArticlePermissionEnum::DELETE);

        // Roles creation
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'writer'])->syncPermissions(
            $view_home,
            $view_admin,
            $create_articles,
            $read_articles,
            $update_articles,
            $delete_articles
        );
        Role::firstOrCreate(['name' => 'user'])
            ->syncPermissions($read_articles, $view_home);
    }
}
