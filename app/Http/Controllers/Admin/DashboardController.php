<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Thread;
use App\Models\Comment;
use App\Models\Article;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalThreads = Thread::count();
        $totalComments = Comment::count();
            $totalArticles = Article::count(); // Add this
        
        $recentUsers = User::latest()->take(5)->get();
        $recentThreads = Thread::with('user')->latest()->take(5)->get();
            $recentArticles = Article::latest()->take(5)->get();
        
        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalThreads', 
            'totalArticles',
            'totalComments',
            'recentUsers',
            'recentArticles',
            'recentThreads'
        ));
    }
}