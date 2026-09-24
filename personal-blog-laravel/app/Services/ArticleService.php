<?php

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Throwable;

class ArticleService
{
    public function fetchAll(array $filters, ?User $user = null)
    {
        $query = ($user && !$user->hasRole('admin')) ? $user->articles() : Article::latest('dop');

        return $query
            ->filter($filters)
            ->simplePaginate(5);
    }

    public function storeArticle(User $user, array $data): Article
    {
        Log::info('Attempting to create a new article', ['title' => $data['title']]);

        try {
            $article = $user->articles()->create($data);
            Log::info('Article created successfully', ['articleId' => $article->id]);

            return $article;
        } catch (Throwable $e) {
            Log::error('Failed to create article' . $e->getMessage(), [
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
            Log::error('Failed to update article' . $e->getMessage(), [
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
