<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    // No constructor with middleware - we'll handle this in routes or with a method
    
    // Check admin access for all methods
    public function checkAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            abort(403, 'Unauthorized action. Admin access required.');
        }
    }
    
    public function index(Request $request)
    {
        $this->checkAdmin();
        
        $status = $request->input('status');
        
        $query = Article::with('user')->latest();
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $articles = $query->paginate(10);
        
        return view('admin.articles.index', compact('articles', 'status'));
    }
    
    public function show(Article $article)
    {
        $this->checkAdmin();
        return view('admin.articles.show', compact('article'));
    }
    
    public function edit(Article $article)
    {
        $this->checkAdmin();
        return view('articles.edit', compact('article'));
    }
    
    public function update(Request $request, Article $article)
    {
        $this->checkAdmin();
        
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keywords' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'status' => 'required|in:draft,pending,published,rejected'
        ]);
        
        $validatedData['slug'] = Str::slug($validatedData['title']);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($article->image && Storage::disk('public')->exists($article->image)) {
                Storage::disk('public')->delete($article->image);
            }
            
            $imagePath = $request->file('image')->store('articles', 'public');
            $validatedData['image'] = $imagePath;
        }
        
        // Set published_at if status is changed to published
        if ($validatedData['status'] === 'published' && $article->status !== 'published') {
            $validatedData['published_at'] = now();
        }
        
        $article->update($validatedData);
        
        return redirect()->route('admin.articles.index')
            ->with('success', 'Article updated successfully.');
    }
    
    public function destroy(Article $article)
    {
        $this->checkAdmin();
        
        // Delete image if exists
        if ($article->image && Storage::disk('public')->exists($article->image)) {
            Storage::disk('public')->delete($article->image);
        }
        
        $article->delete();
        
        return redirect()->route('admin.articles.index')
            ->with('success', 'Article deleted successfully.');
    }
    
    function getStatusBadgeClass($status) {
    switch ($status) {
        case 'published': return 'success';
        case 'pending': return 'warning';
        case 'rejected': return 'danger';
        default: return 'secondary';
    }
}

    public function updateStatus(Request $request, Article $article)
    {
        $this->checkAdmin();
        
        $request->validate([
            'status' => 'required|in:draft,pending,published,rejected'
        ]);
        
        $oldStatus = $article->status;
        $newStatus = $request->input('status');
        
        $article->status = $newStatus;
        
        if ($newStatus === 'published' && $oldStatus !== 'published') {
            $article->published_at = now();
        }
        
        $article->save();
        
        return redirect()->back()->with('success', 'Article status updated successfully.');
    }
    
    public function create()
    {
        $this->checkAdmin();
        return view('admin.articles.create');
    }
    
    public function store(Request $request)
    {
        $this->checkAdmin();
        
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keywords' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'status' => 'required|in:draft,pending,published,rejected'
        ]);
        
        $validatedData['user_id'] = Auth::id();
        $validatedData['slug'] = Str::slug($validatedData['title']);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'public');
            $validatedData['image'] = $imagePath;
        }
        
        // Set published_at if status is published
        if ($validatedData['status'] === 'published') {
            $validatedData['published_at'] = now();
        }
        
        Article::create($validatedData);
        
        return redirect()->route('admin.articles.index')
            ->with('success', 'Article created successfully.');
    }
}