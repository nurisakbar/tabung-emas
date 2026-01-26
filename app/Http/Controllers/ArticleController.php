<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles
     */
    public function index()
    {
        $articles = Article::published()
            ->latest('published_at')
            ->paginate(12);
        
        return view('articles.index', compact('articles'));
    }

    /**
     * Display the specified article
     */
    public function show($slug)
    {
        $article = Article::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
        
        // Increment views
        $article->incrementViews();
        
        // Get related articles
        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(4)
            ->get();
        
        return view('articles.show', compact('article', 'relatedArticles'));
    }
}
