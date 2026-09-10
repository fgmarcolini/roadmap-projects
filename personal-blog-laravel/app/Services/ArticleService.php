<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Facades\Log;
use Throwable;

class ArticleService
{
    public function fetchAll(array $filters)
    {
        return $this->getArticlesFiltered($filters)
            ->latest('updated_at')
            ->simplePaginate(5);
    }

    private function getArticlesFiltered(array $filters)
    {
        return Article::query()
            ->when($filters['title'] ?? null, function ($query, $title) {
                $query->where('title', 'like', "%{$title}%");
            })
            ->when($filters['dop'] ?? null, function ($query, $dop) {
                $query->where('dop', '>=', "DATE %{$dop}%", 'and', 'dop', '<', "DATE %{$dop}%");
            });
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
