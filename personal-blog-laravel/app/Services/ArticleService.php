<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

class ArticleService
{
    public function fetchAll(): Collection
    {
        return Article::all()
            ->take(50)
            ->sortByDesc('updated_at');
    }

    public function storeArticle(array $data): Article
    {
        Log::info('Attempting to create a new article', ['title' => $data['title']]);

        try {
            $article = Article::create($data);
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
