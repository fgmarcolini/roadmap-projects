<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::all()
            ->take(50)
            ->sortByDesc('created_at');

        return view('home', compact('articles'));
    }

    /**
     * Display a listing of the resource.
     */
    public function admin()
    {
        $articles = Article::all()
            ->take(50)
            ->sortByDesc('created_at');

        return view('admin', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('new');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate article
        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'dop' => ['required', Rule::date()->beforeOrEqual(today())],
            'content' => 'required|max:500',
        ]);

        // create the article
        Article::create([
            'title' => $validated['title'],
            'dop' => $validated['dop'],
            'content' => $validated['content'],
        ]);

        return redirect('admin')->with('success', 'Article created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return view('article', ['article' => $article]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        //
    }
}
