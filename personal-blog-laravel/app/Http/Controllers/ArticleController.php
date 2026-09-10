<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleFilterRequest;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Services\ArticleService;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ArticleService $service, ArticleFilterRequest $request)
    {
        $filters = $request->validated();
        $articles = $service->fetchAll($filters);

        return view('articles.index', compact('articles'));
    }

    /**
     * Display a listing of the resource.
     */
    public function admin(ArticleService $service, ArticleFilterRequest $request)
    {
        $filters = $request->validated();
        $articles = $service->fetchAll($filters);

        return view('articles.admin', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleService $service, ArticleRequest $request)
    {
        $service->storeArticle($request->validated());

        return redirect()
            ->route('articles.admin')
            ->with('success', 'Article created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return view('articles.show',
            ['article' => $article]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleService $service, ArticleRequest $request, Article $article)
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
        $service->deleteArticle($article);

        return redirect()
            ->route('articles.admin')
            ->with('success', 'Article deleted succesfully!');
    }
}
