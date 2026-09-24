<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleFilterRequest;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ArticleService $service, ArticleFilterRequest $request)
    {
        Gate::authorize('viewAny', Article::class);

        $filters = $request->validated();
        $articles = $service->fetchAll($filters);

        return view('articles.index', compact('articles'));
    }

    /**
     * Display a listing of the resource.
     */
    public function admin(ArticleService $service, ArticleFilterRequest $request)
    {
        Gate::authorize('viewAny', Article::class);

        $user = Auth::user();
        $filters = $request->validated();
        $articles = $service->fetchAll($filters, $user);

        return view('articles.admin', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Article::class);

        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleService $service, StoreArticleRequest $request)
    {
        $service->storeArticle(Auth::user(), $request->validated());

        return redirect()
            ->route('articles.admin')
            ->with('success', 'Article created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        Gate::authorize('view', $article);

        return view(
            'articles.show',
            ['article' => $article]
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        Gate::authorize('update', $article);

        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleService $service, UpdateArticleRequest $request, Article $article)
    {
        $service->updateArticle($article, $request->validated());

        return redirect()
            ->route('articles.admin')
            ->with('success', 'Article updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ArticleService $service, Article $article)
    {
        Gate::authorize('delete', $article);

        $service->deleteArticle($article);

        return redirect()
            ->route('articles.admin')
            ->with('success', 'Article deleted succesfully!');
    }
}
