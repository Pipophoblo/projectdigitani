<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Models\Category;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function index()
    {
        $threads = Thread::with(['user', 'category'])
            ->latest()
            ->paginate(10);
        
        return view('admin.threads.index', compact('threads'));
    }
    
    public function show(Thread $thread)
    {
        $thread->load(['user', 'category', 'comments.user']);
        return view('admin.threads.show', compact('thread'));
    }
    
    public function edit(Thread $thread)
    {
        $categories = Category::all();
        return view('admin.threads.edit', compact('thread', 'categories'));
    }
    
    public function update(Request $request, Thread $thread)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'is_resolved' => 'boolean',
        ]);
        
        $thread->update($validatedData);
        
        return redirect()->route('admin.threads.index')
            ->with('success', 'Thread updated successfully.');
    }
    
    public function destroy(Thread $thread)
    {
        // Delete related comments first
        $thread->comments()->delete();
        
        // Delete thread likes
        $thread->likes()->delete();
        
        // Delete thread
        $thread->delete();
        
        return redirect()->route('admin.threads.index')
            ->with('success', 'Thread and all related comments deleted successfully.');
    }
}