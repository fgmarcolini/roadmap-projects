<?php

namespace App\Policies;

use App\Enums\ArticlePermissionEnum;
use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{

    /**
     * Optional before interceptor for super admins.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(ArticlePermissionEnum::VIEW_HOME);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Article $article): bool
    {
        return $user->hasPermissionTo(ArticlePermissionEnum::READ);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(ArticlePermissionEnum::CREATE);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Article $article): bool
    {
        return $user->hasPermissionTo(ArticlePermissionEnum::UPDATE) &&
            $user->id === $article->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Article $article): bool
    {
        return $user->hasPermissionTo(ArticlePermissionEnum::DELETE) &&
            $user->id === $article->user_id;
    }
}
