<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Throwable;

class ArticleService
{
    public function fetchAll(array $filters, ?User $user = null)
    {
        return $this
            ->applyFilters($user ? $user->articles() : Article::query(), $filters)
            ->latest('dop')
            ->simplePaginate(5);
    }

    private function applyFilters(Builder|HasMany $query, array $filters)
    {
        return $query
            ->when($filters['title'] ?? null, function ($query, $title) {
                $query->where('title', 'like', "%{$title}%");
            })
            ->when($filters['dop'] ?? null, function ($query, $dop) {
                $query->whereDate('dop', $dop);
            });
    }

    public function storeArticle(User $user, array $data): Article
    {
        Log::info('Attempting to create a new article', ['title' => $data['title']]);

        try {
            $article = $user->articles()->create($data);
            Log::info('Article created successfully', ['articleId' => $article->id]);

            return $article;
        } catch (Throwable $e) {
            Log::error('Failed to create article'.$e->getMessage(), [
                'exception' => $e,
                'data' => $data,
            ]);

            throw $e;
        }
    }

    public function updateArticle(Article $article, array $data): Article
    {
        Log::info('Attempting to update an article', ['articleId' => $article->id]);

        try {
            $article->update($data);
            Log::info('Article updated successfully', ['articleId' => $article->id]);

            return $article;
        } catch (Throwable $e) {
            Log::error('Failed to updatearticle'.$e->getMessage(), [
                'exception' => $e,
                'data' => $data,
            ]);

            throw $e;
        }
    }

    public function deleteArticle(Article $article)
    {
        $article->delete();
    }
}
