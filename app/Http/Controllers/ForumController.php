<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Thread;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    /**
     * Display a listing of threads.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');
        $categoryId = $request->input('category');
        
        $query = Thread::with(['user', 'category', 'comments', 'likes']);
        
        // Apply category filter if specified
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        // Apply filters
        switch ($filter) {
            case 'trending':
                $query->orderBy('view_count', 'desc');
                break;
            case 'popular':
                $query->withCount('likes')->orderBy('likes_count', 'desc');
                break;
            default:
                $query->latest();
                break;
        }
        
        $threads = $query->get();
        
        // Format the threads data for the view
        $formattedThreads = $threads->map(function ($thread) {
            $isLiked = false;
            
            // Check if the current user has liked this thread
            if (Auth::check()) {
                $isLiked = $thread->isLikedByUser(Auth::id());
            }
            
            return [
                'id' => $thread->id,
                'title' => $thread->title,
                'content' => $thread->content,
                'user' => $thread->user->name,
                'role' => $thread->user->role ?? 'Member',
                'comments' => $thread->comments->count(),
                'likes' => $thread->likes->count(),
                'created_at' => $thread->created_at->format('d M Y, H:i'),
                'is_liked' => $isLiked
            ];
        });
        
        $categories = Category::all();
        
        return view('forum', [
            'threads' => $formattedThreads,
            'categories' => $categories,
            'filter' => $filter
        ]);
    }
    
    /**
     * Display the thread creation form.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk membuat thread.');
        }
        
        $categories = Category::all();
        
        return view('forum.create', [
            'categories' => $categories,
            'user' => [
                'name' => Auth::user()->name,
                'role' => Auth::user()->role
            ]
        ]);
    }
    
    /**
     * Store a newly created thread.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk membuat thread.');
        }
        
        // Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id'
        ]);
        
        // Create thread
        $thread = Thread::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'content' => $validated['content']
        ]);
        
        return redirect()->route('forum.show', $thread->id)
            ->with('success', 'Thread berhasil dibuat!');
    }
    
    /**
     * Display the specified thread.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $thread = Thread::with(['user', 'category', 'comments.user'])->findOrFail($id);
        
        // Increment view count
        $thread->increment('view_count');
        
        return view('forum.show', [
            'thread' => $thread,
            'isLiked' => Auth::check() ? $thread->isLikedByUser(Auth::id()) : false,
            'user' => Auth::check() ? [
                'name' => Auth::user()->name,
                'role' => Auth::user()->role
            ] : null
        ]);
    }
    
    /**
     * Toggle like status for a thread
     *
     * @param Request $request
     * @param int $id Thread ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleLike(Request $request, $id)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
        $thread = Thread::findOrFail($id);
        $userId = Auth::id();

        // Check if user already liked this thread
        $existingLike = Like::where('user_id', $userId)
                            ->where('thread_id', $id)
                            ->first();

        if ($existingLike) {
            // User already liked the thread, so unlike it
            $existingLike->delete();
            $action = 'unliked';
        } else {
            // User hasn't liked the thread yet, so like it
            Like::create([
                'user_id' => $userId,
                'thread_id' => $id
            ]);
            $action = 'liked';
        }
        // Get updated like count
        $likesCount = $thread->likes()->count();

        return response()->json([
            'success' => true,
            'action' => $action,
            'likes_count' => $likesCount
        ]);
    }
    
    /**
     * Store a new comment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $threadId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeComment(Request $request, $threadId)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk berkomentar.');
        }
        
        // Validate request
        $validated = $request->validate([
            'content' => 'required|string'
        ]);
        
        // Find thread
        $thread = Thread::findOrFail($threadId);
        
        // Create comment
        Comment::create([
            'user_id' => Auth::id(),
            'thread_id' => $threadId,
            'content' => $validated['content']
        ]);
        
        return redirect()->route('forum.show', $threadId)
            ->with('success', 'Komentar berhasil ditambahkan!');
    }
    
    /**
     * Search for threads.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function search(Request $request)
    {
        return redirect()->route('forum.index', [
            'search' => $request->search,
            'category' => $request->category
        ]);
    }
}