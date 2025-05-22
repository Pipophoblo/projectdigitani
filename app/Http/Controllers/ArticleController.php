<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    // No constructor with middleware - we'll handle this in routes

    public function index(Request $request)
{
    $filter = $request->input('filter', 'latest');
    $query = $request->input('query');
    $categoryId = $request->input('category');
    
    // Start with the base query
    $articlesQuery = Article::where('status', 'published');
    
    // Apply search filter if present
    if ($query) {
        $articlesQuery->where(function($q) use ($query) {
            $q->where('title', 'LIKE', "%{$query}%")
              ->orWhere('content', 'LIKE', "%{$query}%")
              ->orWhere('keywords', 'LIKE', "%{$query}%")
              ->orWhere('summary', 'LIKE', "%{$query}%");
        });
    }
    
    // Apply category filter if present
    if ($categoryId) {
        $articlesQuery->where('category_id', $categoryId);
    }
    
    // Apply ordering based on filter
    switch ($filter) {
        case 'trending':
        case 'popular':
            $articlesQuery->orderBy('view_count', 'desc');
            break;
        default:
            $articlesQuery->orderBy('published_at', 'desc');
            break;
    }
    
    // Paginate the results
    $articles = $articlesQuery->paginate(9);
    
    // Get trending/popular articles for sidebar
    $trendingArticles = Article::where('status', 'published')
        ->orderBy('view_count', 'desc')
        ->take(5)
        ->get();
    
    return view('articles.index', [
        'articles' => $articles,
        'trendingArticles' => $trendingArticles,
        'filter' => $filter,
        'query' => $query
    ]);
}

    public function create()
    {
        // Check if user has the right role
        if (!$this->checkAuthorRole()) {
            abort(403, 'Only Dosen and Peneliti can create articles');
        }
        
        return view('articles.create');
    }

    public function store(Request $request)
    {
        // Check if user has the right role
        if (!$this->checkAuthorRole()) {
            abort(403, 'Only Dosen and Peneliti can create articles');
        }
        
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keywords' => 'nullable|string|max:255',
            'summary' => 'nullable|string'
        ]);
        
        $validatedData['user_id'] = Auth::id();
        $validatedData['slug'] = Str::slug($validatedData['title']);
        
        // Handle image upload
                if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'digitani');

            // Supaya file bisa diakses publik lewat URL
            Storage::disk('digitani')->setVisibility($imagePath, 'public');

            $validatedData['image'] = $imagePath;
        }

        
        // Set status based on user role
        if ($this->isUserAdmin()) {
            $validatedData['status'] = 'published';
            $validatedData['published_at'] = now();
        } else {
            $validatedData['status'] = 'pending';
        }
        
        Article::create($validatedData);
        
        return redirect()->route('articles.myarticles')
            ->with('success', 'Article created successfully and sent for approval.');
    }

    public function show(Article $article)
    {
        // Check if article is published or user is authorized
        if ($article->status !== 'published' && !Auth::check()) {
            abort(404);
        }

        if ($article->status !== 'published' && 
            Auth::id() !== $article->user_id && 
            !$this->isUserAdmin()) {
            abort(403);
        }
        
        // Increment view count
        if ($article->status === 'published') {
            $article->increment('view_count');
        }
        
        // Get related articles
        $relatedArticles = Article::where('status', 'published')
            ->where('id', '!=', $article->id)
            ->latest()
            ->take(3)
            ->get();
            
        // Get trending articles for sidebar
        $trendingArticles = Article::where('status', 'published')
            ->where('id', '!=', $article->id)
            ->orderBy('view_count', 'desc')
            ->take(5)
            ->get();
        
        return view('articles.show', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
            'trendingArticles' => $trendingArticles
        ]);
    }

    public function edit(Article $article)
    {
        // Check if user is authorized
        if (Auth::id() !== $article->user_id && !$this->isUserAdmin()) {
            abort(403);
        }
        
        return view('articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        // Check if user is authorized
        if (Auth::id() !== $article->user_id && !$this->isUserAdmin()) {
            abort(403);
        }
        
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keywords' => 'nullable|string|max:255',
            'summary' => 'nullable|string'
        ]);
        
        $validatedData['slug'] = Str::slug($validatedData['title']);
        
        // Handle image upload
if ($request->hasFile('image')) {
    // Delete old image if exists
    if ($article->image && Storage::disk('digitani')->exists($article->image)) {
        Storage::disk('digitani')->delete($article->image);
    }

    // Store new image in Cloudflare R2 (disk: digitani)
    $imagePath = $request->file('image')->store('articles', 'digitani');

    // (Optional) Make the file public
    Storage::disk('digitani')->setVisibility($imagePath, 'public');

    $validatedData['image'] = $imagePath;
}

        
        // Update status if edited
        if (!$this->isUserAdmin() && $article->status === 'published') {
            $validatedData['status'] = 'pending';
        } else if (!$this->isUserAdmin() && in_array($article->status, ['draft', 'rejected'])) {
            $validatedData['status'] = $request->input('submit_type') === 'draft' ? 'draft' : 'pending';
        }
        
        $article->update($validatedData);
        
        $redirectRoute = $this->isUserAdmin() ? 'admin.articles.index' : 'articles.myarticles';
        return redirect()->route($redirectRoute)
            ->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        // Check if user is authorized
        if (Auth::id() !== $article->user_id && !$this->isUserAdmin()) {
            abort(403);
        }
        
        // Delete image if exists
        if ($article->image && Storage::disk('digitani')->exists($article->image)) {
        Storage::disk('digitani')->delete($article->image);
    }
        
        $article->delete();
        
        $redirectRoute = $this->isUserAdmin() ? 'admin.articles.index' : 'articles.myarticles';
        return redirect()->route($redirectRoute)
            ->with('success', 'Article deleted successfully.');
    }

    public function myArticles()
    {
        $articles = Auth::user()->articles()
            ->latest()
            ->paginate(10);
        
        return view('articles.my-articles', compact('articles'));
    }
    
public function search(Request $request)
{
    return redirect()->route('articles.index', [
        'query' => $request->input('query'),
        'category' => $request->category
    ]);
}
    
    // Helper methods to replace the user role methods
    private function isUserAdmin() {
        return Auth::user() && Auth::user()->role === 'Admin';
    }
    
    private function checkAuthorRole() {
        $user = Auth::user();
        return $user && ($user->role === 'Admin' || $user->role === 'Dosen' || $user->role === 'Peneliti');
    }
}