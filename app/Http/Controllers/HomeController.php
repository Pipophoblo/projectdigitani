<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Thread;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get trending articles for headline slider (limit to 5)
        $headlineArticles = Article::trending()
            ->take(5)
            ->get();
            
        // Get trending articles for sidebar
        $trendingArticles = Article::trending()
            ->take(6)
            ->get();
            
        // Get recent articles for thumbnail grid
        $recentArticles = Article::recent()
            ->take(6)
            ->get();
            
        // Get trending threads
        $trendingThreads = Thread::with(['user', 'category'])
            ->orderBy('view_count', 'desc')
            ->take(4)
            ->get();

        return view('home', [
            'headlineArticles' => $headlineArticles,
            'trendingArticles' => $trendingArticles,
            'recentArticles' => $recentArticles,
            'trendingThreads' => $trendingThreads
        ]);
    }
    
    /**
     * Search for both articles and threads
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        // Search articles
        $articles = Article::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%")
                  ->orWhere('keywords', 'LIKE', "%{$query}%");
            })
            ->take(10)
            ->get();
            
        // Search threads
        $threads = Thread::where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%");
            })
            ->take(10)
            ->get();
            
        return view('search-results', [
            'query' => $query,
            'articles' => $articles,
            'threads' => $threads
        ]);
    }
}